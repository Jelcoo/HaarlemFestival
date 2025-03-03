<?php

namespace App\Controllers;

use App\Validation\UniqueRule;
use Rakit\Validation\Validator;
use App\Repositories\UserRepository;

class ProfileController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function index(array $parameters = []): string
    {
        $user = $this->userRepository->getUserById($_SESSION['user_id']);

        return $this->pageLoader->setPage('account/manage')->render(array_merge($parameters, ['user' => $user]));
    }

    public function update(): string
    {
        try {
            $user = $this->userRepository->getUserById($_SESSION['user_id']);
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        $validator = new Validator();
        $validator->addValidator('unique', new UniqueRule());

        $rules = [
            'firstname' => 'required|max:255',
            'lastname' => 'required|max:255',
            'address' => 'max:255',
            'city' => 'max:255',
            'postal_code' => 'max:255',
        ];
        if ($user->email !== $_POST['email']) {
            $rules['email'] = 'required|email|unique:users,email|max:255';
        }

        $validation = $validator->validate($_POST, $rules);

        if ($validation->fails()) {
            return $this->index([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $user->firstname = $_POST['firstname'] ?? $user->firstname;
            $user->lastname = $_POST['lastname'] ?? $user->lastname;
            $user->email = $_POST['email'] ?? $user->email;
            $user->address = $_POST['address'] ?: $user->address;
            $user->city = $_POST['city'] ?: $user->city;
            $user->postal_code = $_POST['postal_code'] ?: $user->postal_code;

            $this->userRepository->updateUser($user);
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->pageLoader->setPage('account/manage')->render(['user' => $user]);
    }

    public function updatePassword(): string
    {
        try {
            $user = $this->userRepository->getUserById($_SESSION['user_id']);
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        if (!password_verify($_POST['currentPassword'], $user->password)) {
            return $this->index([
                'error' => 'Incorrect password',
                'fields' => $_POST,
            ]);
        }

        $validator = new Validator();

        $rules = [
            'newPassword' => 'required|min:8',
            'confirmNewPassword' => 'required|same:newPassword',
        ];

        $validation = $validator->validate($_POST, $rules);

        if ($validation->fails()) {
            return $this->index([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        try {
            $encryptedPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            $user->password = $encryptedPassword;
            $this->userRepository->updatePassword($user);
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->index();
    }
}
