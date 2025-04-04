<?php

namespace App\Controllers;

use App\Config\Config;
use App\Application\Request;
use App\Application\Session;
use App\Application\Response;
use App\Helpers\StripeHelper;
use App\Validation\UniqueRule;
use Rakit\Validation\Validator;
use App\Helpers\TurnstileHelper;
use App\Repositories\UserRepository;
use App\Services\EmailWriterService;

class AuthController extends Controller
{
    private UserRepository $userRepository;
    private EmailWriterService $emailWriterService;

    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();
        $this->emailWriterService = new EmailWriterService();
    }

    public function register(array $parameters = []): string
    {
        return $this->pageLoader->setPage('auth/register')->render($parameters);
    }

    public function registerPost(): string
    {
        $token = $_POST['cf-turnstile-response'];

        if (TurnstileHelper::verify($token) === false) {
            return $this->register([
                'error' => 'Turnstile verification failed',
                'fields' => $_POST,
            ]);
        }

        $validator = new Validator();
        $validator->addValidator('unique', new UniqueRule());
        $validation = $validator->validate($_POST, [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'password' => 'required|min:6',
            'password_verify' => 'required|same:password',
        ]);

        if ($validation->fails()) {
            return $this->register([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $firstname = Request::getPostField('firstname');
            $lastname = Request::getPostField('lastname');
            $email = Request::getPostField('email');
            $password = Request::getPostField('password');

            $stripeCustomer = null;

            if (Config::getKey('ENABLE_STRIPE')) {
                $stripeHelper = new StripeHelper();
                $stripeCustomer = $stripeHelper->createCustomer(
                    $email,
                    "$firstname $lastname"
                );
            }

            $createdUser = $this->userRepository->createUser([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'stripe_customer_id' => $stripeCustomer ?? null,
            ]);

            $this->emailWriterService->sendWelcomeEmail($createdUser);

            $_SESSION['user_id'] = $createdUser->id;
            if (isset($_SESSION['cart'])) {
                Response::redirect('/cart');
            } else {
                Response::redirect('/');
            }
        } catch (\Exception $e) {
            return $this->register([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->register();
    }

    public function login(array $parameters = []): string
    {
        return $this->pageLoader->setPage('auth/login')->render($parameters);
    }

    public function loginPost(): string
    {
        $token = $_POST['cf-turnstile-response'];

        if (TurnstileHelper::verify($token) === false) {
            return $this->login([
                'error' => 'Turnstile verification failed',
                'fields' => $_POST,
            ]);
        }

        $validator = new Validator();
        $validator->addValidator('unique', new UniqueRule());
        $validation = $validator->validate($_POST, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validation->fails()) {
            return $this->login([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $email = Request::getPostField('email');
            $password = Request::getPostField('password');

            $user = $this->userRepository->getUserByEmail($email);

            if (password_verify($password, $user?->password)) {
                $_SESSION['user_id'] = $user->id;
                if (isset($_SESSION['cart'])) {
                    Response::redirect('/cart');
                } else {
                    Response::redirect('/');
                }
            } else {
                return $this->login([
                    'error' => 'Invalid credentials',
                    'fields' => $_POST,
                ]);
            }
        } catch (\Exception $e) {
            return $this->login([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->login();
    }

    public function logout(): void
    {
        Session::destroy();
        Response::redirect('/');
    }

    public function forgotPassword(array $parameters = []): string
    {
        return $this->pageLoader->setPage('auth/forgot-password')->render($parameters);
    }

    public function forgotPasswordPost(): string
    {
        $token = $_POST['cf-turnstile-response'];

        if (TurnstileHelper::verify($token) === false) {
            return $this->forgotPassword([
                'error' => 'Turnstile verification failed',
                'fields' => $_POST,
            ]);
        }

        $validator = new Validator();
        $validation = $validator->validate($_POST, [
            'email' => 'required|email',
        ]);

        if ($validation->fails()) {
            return $this->forgotPassword([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $email = Request::getPostField('email');
            $user = $this->userRepository->getUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));
                $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

                $this->userRepository->createPasswordResetToken($user->id, $token, $expiresAt);

                $resetLink = Config::getKey('APP_URL') . '/reset-password?token=' . $token;

                $this->emailWriterService->sendPasswordResetEmail($user, $resetLink);
            }

            // Always return success to prevent email enumeration
            return $this->forgotPassword([
                'success' => 'If an account exists with this email, you will receive password reset instructions.',
            ]);
        } catch (\Exception $e) {
            return $this->forgotPassword([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }
    }

    public function resetPassword(array $parameters = []): string
    {
        $token = $_GET['token'] ?? null;

        if (!$token) {
            return $this->login([
                'error' => 'Invalid or missing reset token',
            ]);
        }

        $resetToken = $this->userRepository->getValidPasswordResetToken($token);

        if (!$resetToken) {
            return $this->login([
                'error' => 'Invalid or expired reset token',
            ]);
        }

        return $this->pageLoader->setPage('auth/reset-password')->render(array_merge($parameters, [
            'token' => $token,
        ]));
    }

    public function resetPasswordPost(): string
    {
        $token = $_POST['token'] ?? null;

        if (!$token) {
            return $this->login([
                'error' => 'Invalid or missing reset token',
            ]);
        }

        $resetToken = $this->userRepository->getValidPasswordResetToken($token);

        if (!$resetToken) {
            return $this->login([
                'error' => 'Invalid or expired reset token',
            ]);
        }

        $validator = new Validator();
        $validation = $validator->validate($_POST, [
            'password' => 'required|min:6',
            'password_verify' => 'required|same:password',
        ]);

        if ($validation->fails()) {
            return $this->resetPassword([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
                'token' => $token,
            ]);
        }

        try {
            $user = $this->userRepository->getUserById($resetToken['user_id']);
            $newPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $this->userRepository->updatePassword($user->id, $newPassword);
            $this->userRepository->deletePasswordResetToken($token);

            return $this->login([
                'success' => 'Your password has been reset successfully. Please login with your new password.',
            ]);
        } catch (\Exception $e) {
            return $this->resetPassword([
                'error' => $e->getMessage(),
                'fields' => $_POST,
                'token' => $token,
            ]);
        }
    }
}
