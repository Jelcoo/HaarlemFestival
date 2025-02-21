<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;
use App\Models\User;

class UserRepository extends Repository
{
  public function getUserById(int $id): ?User
  {
    $queryBuilder = new QueryBuilder($this->getConnection());

    $queryUser = $queryBuilder->table('users')->where('id', '=', $id)->first();

    return $queryUser ? new User($queryUser) : null;
  }

  public function getAllUsers(): array
  {
    $queryBuilder = new QueryBuilder($this->getConnection());

    $queryUsers = $queryBuilder->table('users')->get();

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

  public function updateUser(User $user): ?User
  {
    $queryBuilder = new QueryBuilder($this->getConnection());

    $existingUser = $this->getUserById($user->id);
    if (!$existingUser) {
      return null;
    }

    $fieldsToCompare = [
      'firstname' => $user->firstname,
      'lastname' => $user->lastname,
      'email' => $user->email,
      'role' => $user->role->value,
      'address' => $user->address,
      'city' => $user->city,
      'postal_code' => $user->postal_code,
    ];

    $updatedFields = [];

    foreach ($fieldsToCompare as $field => $newValue) {
      if ($newValue !== $existingUser->$field) {
        $updatedFields[$field] = $newValue;
      }
    }

    if (!empty($updatedFields)) {
      $queryBuilder->table('users')->where('id', '=', $user->id)->update($updatedFields);
      return $this->getUserById($user->id);
    }

    return $existingUser;
  }
}
