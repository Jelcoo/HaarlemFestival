<?php

namespace App\Models;

class TicketYummy
{
    public int $id;
    public int $invoice_id;
    public string $qrcode;
    public bool $ticket_used;
    public int $yummy_event_id;
    public int $kids_count;
    public int $adult_count;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->invoice_id = $collection['invoice_id'];
        $this->yummy_event_id = $collection['yummy_event_id'];
        $this->kids_count = $collection['kids_count'];
        $this->adult_count = $collection['adult_count'];
        $this->qrcode = $collection['qrcode'];
        $this->ticket_used =(bool)['ticket_used'];
    }
}
