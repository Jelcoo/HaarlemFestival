<?php

namespace App\Repositories;

use App\Models\EventYummy;
use App\Helpers\QueryBuilder;

class YummyRepository extends Repository
{
    public function getEventById(int $id): EventYummy
    {
        $queryBuilder = new QueryBuilder($this->getConnection());

        $queryEvent = $queryBuilder->table('yummy_events')->where('id', '=', $id)->first();

        return $queryEvent ? new EventYummy($queryEvent) : null;
    }
}
