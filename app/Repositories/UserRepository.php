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

    public function getAllUsers(): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryUsers = $queryBuilder->table('users')->get();

        return $queryUsers ? array_map(fn($userData) => new User($userData), $queryUsers) : [];
    }

    public function getSortedUsers(string $searchQuery, string $sortColumn = 'id', string $sortDirection = 'asc'): array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $query = $queryBuilder->table('users');

        if (!empty($searchQuery)) {
            $query->where('firstname', 'LIKE', "%{$searchQuery}%")
                ->orWhere('lastname', 'LIKE', "%{$searchQuery}%")
                ->orWhere('email', 'LIKE', "%{$searchQuery}%")
                ->orWhere('city', 'LIKE', "%{$searchQuery}%");
        }

        $queryUsers = $query->orderBy($sortColumn, $sortDirection)->get();

        return $queryUsers ? array_map(fn($userData) => new User($userData), $queryUsers) : [];
    }

    public function deleteUser(int $id): ?User
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryUser = $this->getUserById($id);

        if ($queryUser) {
            $queryBuilder->table('users')->where('id', '=', $id)->delete();

            return $queryUser;
        }

        return null;
    }

    public function updateUserAdmin(User $user): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $updateFields = [
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
            'phone_number' => $user->phone_number,
            'address' => $user->address,
            'city' => $user->city,
            'postal_code' => $user->postal_code,
        ];

        if (isset($user->role)) {
            $updateFields['role'] = $user->role->value;
        }

        $queryBuilder->table('users')->where('id', '=', $user->id)->update($updateFields);
    }

    public function updateUser(User $user): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('users')->where('id', '=', $user->id)->update(
            [
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'city' => $user->city,
                'postal_code' => $user->postal_code,
            ]
        );
    }

    public function updatePassword(int $userId, string $newPassword): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('users')->where('id', '=', $userId)->update(
            [
                'password' => $newPassword,
            ]
        );
    }

    public function createPasswordResetToken(int $userId, string $token, string $expiresAt): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('password_reset_tokens')->insert([
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => $expiresAt,
        ]);
    }

    public function getValidPasswordResetToken(string $token): ?array
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        return $queryBuilder->table('password_reset_tokens')
            ->where('token', '=', $token)
            ->where('expires_at', '>', date('Y-m-d H:i:s'))
            ->first();
    }

    public function deletePasswordResetToken(string $token): void
    {
        $queryBuilder = new QueryBuilder($this->getConnection());
        $queryBuilder->table('password_reset_tokens')
            ->where('token', '=', $token)
            ->delete();
    }
}
