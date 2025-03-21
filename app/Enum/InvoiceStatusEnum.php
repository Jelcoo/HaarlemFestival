<?php

namespace App\Enum;

enum InvoiceStatusEnum: string
{
    case STARTED = 'started';
    case PENDING = 'pending';
    case COMPLETED = 'completed';
    case FAILED = 'failed';
    case LATER = 'later';
}
