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
}
