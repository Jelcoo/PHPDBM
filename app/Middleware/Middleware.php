<?php

namespace App\Middleware;

interface Middleware
{
    public function verify(): bool;
}
