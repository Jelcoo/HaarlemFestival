<?php

namespace App\Validation;

use Rakit\Validation\Rule;
use App\Repositories\Repository;

class UniqueRule extends Rule
{
    protected $message = ':attribute :value has been used';

    protected $fillableParams = ['table', 'column'];

    private Repository $repository;

    public function __construct()
    {
        $this->repository = new Repository();
    }

    public function check($value): bool
    {
        $this->requireParameters(['table', 'column']);

        $column = $this->parameter('column');
        $table = $this->parameter('table');

        $stmt = $this->repository->prepare("select count(*) as count from `{$table}` where `{$column}` = :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        $data = $stmt->fetch(\PDO::FETCH_ASSOC);

        return intval($data['count']) === 0;
    }
}
