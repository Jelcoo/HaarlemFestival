<?php

namespace App\Enum;

enum UserRoleEnum: string
{
    case USER = 'user';
    case EMPLOYEE = 'employee';
    case ADMIN = 'admin';
}
