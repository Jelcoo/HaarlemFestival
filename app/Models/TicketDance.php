<?php

namespace App\Models;

class TicketDance extends Ticket
{
    public int $dance_event_id;
    public bool $all_access;

    public function __construct(array $collection)
    {
        parent::__construct($collection);

        $this->dance_event_id = $collection['dance_event_id'];
        $this->all_access = $collection['all_access'];
    }
}
