<?php

namespace App\Application;

class Session
{
    public static function isValidSession(): bool
    {
        return isset($_SESSION['user_id']);
    }

    public static function destroy(): void
    {
        session_destroy();
    }
}
