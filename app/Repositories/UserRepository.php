<?php

namespace App\Repositories;

use App\Helpers\QueryBuilder;

class UserRepository extends Repository
{
    public function getUserById(int $id): null
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryUser = $queryBuilder->table('users')->where('id', '=', $id)->first();

        return null;
    }
}
