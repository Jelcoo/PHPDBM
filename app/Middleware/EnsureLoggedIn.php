<?php

namespace App\Middleware;

use App\Application\Session;
use App\Application\Response;

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
