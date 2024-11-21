<?php

namespace App\Application;

class Session
{
    public static function isValidSession(): bool
    {
        return isset($_SESSION['ip_address'], $_SESSION['port'], $_SESSION['username'], $_SESSION['password']);
    }

    public function destroy(): void
    {
        session_destroy();
    }
}
