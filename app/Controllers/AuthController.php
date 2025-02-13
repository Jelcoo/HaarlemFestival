<?php

namespace App\Controllers;

use App\Application\Request;
use App\Application\Response;
use App\Application\Session;
use App\Repositories\UserRepository;
use App\Validation\UniqueRule;
use Rakit\Validation\Validator;

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
                'firstname'=> $firstname,
                'lastname' => $lastname,
                'email' => $email,
                'password'=> password_hash($password, PASSWORD_DEFAULT),
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

    public function logout(): void
    {
        Session::destroy();
        Response::redirect('/register');
    }

    private function rerenderRegister(array $parameters = []): string
    {
        return $this->pageLoader->setPage('auth/register')->render($parameters);
    }
}
