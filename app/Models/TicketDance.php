<?php

namespace App\Models;

class TicketDance
{
    public int $id;
    public int $invoice_id;
    public string $qrcode;
    public bool $ticket_used;
    public int $dance_event_id;
    public bool $all_access;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->invoice_id = $collection['invoice_id'];
        $this->dance_event_id = $collection['dance_event_id'];
        $this->all_access = $collection['all_access'];
        $this->qrcode = $collection['qrcode'];
        $this->ticket_used = (bool) ['ticket_used'];
    }
}
