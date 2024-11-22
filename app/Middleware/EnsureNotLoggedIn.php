<?php

namespace App\Middleware;

use App\Application\Session;

class EnsureNotLoggedIn implements Middleware
{
    public function verify(array $params = []): bool
    {
        return !Session::isValidSession();
    }
}
