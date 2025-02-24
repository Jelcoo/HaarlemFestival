<?php

namespace App\Repositories;

use App\Models\User;
use App\Helpers\QueryBuilder;

class UserRepository extends Repository
{
    public function getUserById(int $id): ?User
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryUser = $queryBuilder->table('users')->where('id', '=', $id)->first();

        return $queryUser ? new User($queryUser) : null;
    }

    public function getUserByEmail(string $email): ?User
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryUser = $queryBuilder->table('users')->where('email', '=', $email)->first();

        return $queryUser ? new User($queryUser) : null;
    }

    public function createUser(array $data): User
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $userId = $queryBuilder->table('users')->insert($data);
        $user = $this->getUserById((int) $userId);

        return $user;
    }
}
