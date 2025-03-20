<?php

namespace App\Models;

use App\Enum\DanceSessionEnum;

class EventDance extends Event
{
    public int $location_id;
    public int $total_tickets;
    public DanceSessionEnum $session;
    public float $price;
    public ?Location $location = null;
    public ?array $artists = null;

    public function __construct()
    {
        $arguments = func_get_args();

        if (!empty($arguments)) {
            $this->fill($arguments[0]);
        }
    }

    public function fill(array $collection)
    {
        parent::__construct($collection);

        $this->location_id = $collection['location_id'];
        $this->total_tickets = $collection['total_tickets'];
        $this->session = DanceSessionEnum::from($collection['session']);
        $this->price = $collection['price'];
    }
}
