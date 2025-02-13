<?php

namespace App\Models;

class EventDance extends Event
{
    public int $id;
    public int $event_id;
    public string $artist;
    public string $location;
    public int $total_tickets;
    public int $tickets_left;
    public float $price;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->event_id = $collection['event_id'];
        $this->artist = $collection['artist'];
        $this->location = $collection['location'];
        $this->total_tickets = $collection['total_tickets'];
        $this->tickets_left = $collection['tickets_left'];
        $this->price = $collection['price'];
    }
}
