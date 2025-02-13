<?php

namespace App\Models;

use App\Enum\DanceSessionEnum;

class EventDance extends Event
{
    public int $artist_id;
    public int $location_id;
    public int $total_tickets;
    public DanceSessionEnum $session;
    public float $price;

    public function __construct(array $collection)
    {
        parent::__construct($collection);

        $this->artist_id = $collection['artist_id'];
        $this->location_id = $collection['location'];
        $this->total_tickets = $collection['total_tickets'];
        $this->session = DanceSessionEnum::from($collection['session']);
        $this->price = $collection['price'];
    }
}
