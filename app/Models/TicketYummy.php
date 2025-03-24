<?php

namespace App\Models;

class TicketYummy extends Ticket
{
    public int $yummy_event_id;
    public int $kids_count;
    public int $adult_count;

    public function __construct(array $collection)
    {
        parent::__construct($collection);

        $this->yummy_event_id = $collection['yummy_event_id'];
        $this->kids_count = $collection['kids_count'];
        $this->adult_count = $collection['adult_count'];
    }
}
