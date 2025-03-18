<?php

namespace App\Enum;

enum ItemQuantityEnum: string
{
    case GENERAL = 'general';
    case ADULT = 'adult';
    case CHILD = 'child';
    case SINGLE = 'single';
    case FAMILY = 'family';
}
