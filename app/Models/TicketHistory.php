<?php

namespace App\Models;

class TicketHistory
{
    public int $id;
    public int $invoice_id;
    public int $history_event_id;
    public int $total_seats;
    public bool $family_ticket;
    public string $qrcode;
    public bool $ticket_used;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->invoice_id = $collection['invoice_id'];
        $this->history_event_id = $collection['history_event_id'];
        $this->total_seats = $collection['total_seats'];
        $this->family_ticket = $collection['family_ticket'];
        $this->qrcode = $collection['qrcode'];
        $this->ticket_used = (bool) ['ticket_used'];
    }
}
