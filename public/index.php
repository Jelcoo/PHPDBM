<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Application\Router;
use App\Application\Application;

$app = Application::getInstance();
$router = Router::getInstance();

// Load all routes
$handle = opendir(__DIR__ . '/../routes');
while (false !== ($file = readdir($handle))) {
    if ($file == '.' || $file == '..') {
        continue;
    }
    require_once __DIR__ . '/../routes/' . $file;
}
closedir($handle);

$app->run();
