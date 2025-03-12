<?php

namespace App\Middleware;

use App\Application\Session;
use App\Application\Response;
use App\Config\Config;

class EnsureLoggedIn implements Middleware
{
    public function verify(array $params = []): bool
    {
        if (!Session::isValidSession()) {
            $_SESSION['origin'] = $_SERVER['HTTP_REFERER'];
            Response::redirect('/login');
        }

        return Session::isValidSession();
    }
}
