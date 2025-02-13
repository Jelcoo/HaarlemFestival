<?php

namespace App\Models;

class Ticket
{
    public int $id;
    public int $invoice_id;
    public string $qrcode;
    public bool $ticket_used;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->invoice_id = $collection['invoice_id'];
        $this->qrcode = $collection['qrcode'];
        $this->ticket_used = $collection['ticket_used'];
    }
}
