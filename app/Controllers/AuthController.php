<?php

namespace App\Controllers;

use App\Application\Request;
use App\Application\Session;
use App\Application\Response;
use App\Validation\UniqueRule;
use Rakit\Validation\Validator;
use App\Helpers\TurnstileHelper;
use App\Repositories\UserRepository;

class AuthController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();

        $this->userRepository = new UserRepository();
    }

    public function register(): string
    {
        return $this->pageLoader->setPage('auth/register')->render();
    }

    public function registerPost(): string
    {
        $token = $_POST['cf-turnstile-response'];

        if (TurnstileHelper::verify($token) === false) {
            return $this->rerenderRegister([
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
        ]);

        if ($validation->fails()) {
            return $this->rerenderRegister([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $firstname = Request::getPostField('firstname');
            $lastname = Request::getPostField('lastname');
            $email = Request::getPostField('email');
            $password = Request::getPostField('password');

            $createdUser = $this->userRepository->createUser([
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $_SESSION['user_id'] = $createdUser->id;
            Response::redirect('/');
        } catch (\Exception $e) {
            return $this->rerenderRegister([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->pageLoader->setPage('auth/register')->render();
    }

    public function login(): string
    {
        return $this->pageLoader->setPage('auth/login')->render();
    }

    public function loginPost(): string
    {
        $token = $_POST['cf-turnstile-response'];

        if (TurnstileHelper::verify($token) === false) {
            return $this->rerederLogin([
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
            return $this->rerederLogin([
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
                Response::redirect('/');
            } else {
                return $this->rerederLogin([
                    'error'=> 'Invalid credentials',
                    'fields' => $_POST,
                ]);
            }
        } catch (\Exception $e) {
            return $this->rerederLogin([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->pageLoader->setPage('auth/login')->render();
    }

    public function logout(): void
    {
        Session::destroy();
        Response::redirect('/');
    }

    private function rerenderRegister(array $parameters = []): string
    {
        return $this->pageLoader->setPage('auth/register')->render($parameters);
    }

    private function rerederLogin(array $parameters = []): string
    {
        return $this->pageLoader->setPage('auth/login')->render($parameters);
    }
}
