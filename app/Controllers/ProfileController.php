<?php

namespace App\Controllers;

use App\Validation\UniqueRule;
use Rakit\Validation\Validator;
use App\Repositories\UserRepository;
use App\Services\EmailWriterService;

class ProfileController extends Controller
{
    private UserRepository $userRepository;
    private EmailWriterService $emailWriterService;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
        $this->emailWriterService = new EmailWriterService();
    }

    public function index(array $parameters = []): string
    {
        $user = $this->getAuthUser();

        return $this->pageLoader->setPage('account/manage')->render(array_merge($parameters, ['user' => $user]));
    }

    public function update(): string
    {
        try {
            $oldUser = $this->getAuthUser();
            $user = $oldUser;
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
            'phone_number' => 'max:255',
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
            $user->phone_number = $_POST['phone_number'] ?: null;
            $user->address = $_POST['address'] ?: null;
            $user->city = $_POST['city'] ?: null;
            $user->postal_code = $_POST['postal_code'] ?: null;

            $this->userRepository->updateUser($user);

            if ($user->email !== $oldUser->email) {
                $this->emailWriterService->sendEmailUpdate($oldUser);
            } else {
                $this->emailWriterService->sendAccountUpdate($user);
            }
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
            $user = $this->getAuthUser();
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        $validator = new Validator();
        $validation = $validator->validate($_POST, [
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8',
            'confirmNewPassword' => 'required|same:newPassword',
        ]);

        if ($validation->fails()) {
            return $this->index([
                'error' => $validation->errors()->toArray(),
                'fields' => $_POST,
            ]);
        }

        if (!password_verify($_POST['currentPassword'], $user->password)) {
            return $this->index([
                'error' => 'Incorrect password',
                'fields' => $_POST,
            ]);
        }

        try {
            $encryptedPassword = password_hash($_POST['newPassword'], PASSWORD_DEFAULT);
            $user->password = $encryptedPassword;
            $this->userRepository->updatePassword($user->id, $encryptedPassword);

            $this->emailWriterService->sendPasswordUpdate($user);
        } catch (\Exception $e) {
            return $this->index([
                'error' => $e->getMessage(),
                'fields' => $_POST,
            ]);
        }

        return $this->index();
    }
}
