<?php

namespace App\Enum;

enum InvoiceStatusEnum: string
{
    case COMPLETED = 'completed';
    case STARTED = 'started';
    case FAILED = 'failed';
}
