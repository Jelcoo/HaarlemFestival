<?php

namespace App\Models;

class Cart
{
    public int $id;
    public ?int $user_id;
    public ?array $items = [];

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->user_id = $collection['user_id'];
    }
}
