<?php

namespace App\Middleware;

use App\Application\Session;

class EnsureLoggedIn implements Middleware
{
    public function verify(): bool
    {
        return Session::isValidSession();
    }
}
