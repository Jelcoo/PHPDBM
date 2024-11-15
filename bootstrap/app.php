<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Config;

// Set error handling
if (Config::getKey('APP_ENV') === 'development') {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}
