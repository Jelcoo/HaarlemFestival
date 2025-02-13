<?php

namespace App\Models;

use Carbon\Carbon;

class Ticket
{
    public int $id;
    public int $invoice_id;
    public string $qrcode;
    public string $session;
    public Carbon $created_at;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->invoice_id = $collection['invoice_id'];
        $this->qrcode = $collection['qrcode'];
        $this->session = $collection['session'];
        $this->created_at = Carbon::parse($collection['created_at']);
    }
}
