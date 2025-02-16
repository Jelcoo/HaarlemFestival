<?php

namespace App\Controllers;

use App\Application\PageLoader;
use App\Application\Session;
use App\Models\User;
use App\Repositories\UserRepository;

class Controller
{
    protected PageLoader $pageLoader;
    private UserRepository $userRepository;
    private User $user;

    public function __construct()
    {
        $this->pageLoader = new PageLoader();
        $this->userRepository = new UserRepository();
    }

    protected function getAuthUser(): ?User
    {
        if (isset($this->user)) {
            return $this->user;
        }
        
        if (Session::isValidSession()) {
            $this->user = $this->userRepository->getUserById($_SESSION['user_id']);
        }

        return $this->user;
    }
}
