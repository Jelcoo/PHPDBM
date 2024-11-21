<?php

namespace App\Middleware;

use App\Application\Response;
use App\Application\Session;

class EnsureLoggedIn implements Middleware
{
    public function verify(): bool
    {
        if (!Session::isValidSession()) {
            Response::redirect('/login');
        }

        return Session::isValidSession();
    }
}
