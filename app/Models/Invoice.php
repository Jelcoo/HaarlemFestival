<?php

namespace App\Models;

use Carbon\Carbon;
use App\Enum\InvoiceStatusEnum;

class Invoice
{
    public int $id;
    public int $user_id;
    public InvoiceStatusEnum $status;
    public ?string $stripe_payment_id;
    public Carbon $created_at;
    public Carbon $completed_at;

    public function __construct(array $collection)
    {
        $this->id = $collection['id'];
        $this->user_id = $collection['user_id'];
        $this->status = InvoiceStatusEnum::from($collection['status']);
        $this->stripe_payment_id = $collection['stripe_payment_id'];
        $this->created_at = Carbon::parse($collection['created_at']);
        $this->completed_at = Carbon::parse($collection['completed_at']);
    }
}
