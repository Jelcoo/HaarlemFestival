<?php

namespace App\Middleware;

use App\Enum\UserRoleEnum;
use App\Repositories\UserRepository;

class EnsureEmployee implements Middleware
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function verify(array $params = []): bool
    {
        $user = $this->userRepository->getUserById($_SESSION['user_id']);

        return $user && ($user->role === UserRoleEnum::EMPLOYEE || $user->role === UserRoleEnum::ADMIN);
    }
}
