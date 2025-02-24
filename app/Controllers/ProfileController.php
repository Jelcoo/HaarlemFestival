<?php

namespace App\Controllers;

use App\Repositories\UserRepository;

class ProfileController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        parent::__construct();
        $this->userRepository = new UserRepository();
    }

    public function index()
    {
        $user = $this->userRepository->getUserById($_SESSION['user_id']);

        return $this->pageLoader->setPage('account/manage')->render(['user' => $user]);
    }

    public function update()
    {
        // die(var_dump($_POST));
        $user = $this->userRepository->getUserById($_SESSION['user_id']);
        $user->firstname = $_POST['firstname'] ?? $user->firstname;
        $user->lastname = $_POST['lastname'] ?? $user->lastname;
        $user->email = $_POST['email'] ?? $user->email;
        $user->address = $_POST['address'] ?: $user->address;
        $user->city = $_POST['city'] ?: $user->city;
        $user->postal_code = $_POST['postal_code'] ?: $user->postal_code;

        $this->userRepository->updateUser($user);

        return $this->pageLoader->setPage('account/manage')->render(['user' => $user]);
    }
}
