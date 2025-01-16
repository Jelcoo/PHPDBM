<?php

$GLOBALS['APP_START_TIME'] = microtime(true);

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../bootstrap/app.php';

use App\Application\Router;
use App\Application\Application;
use App\Config\Config;

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

if (Config::getKey('APP_ENV') === 'development') {
    $endtime = microtime(true);
    printf("Page loaded in %f seconds", $endtime - $GLOBALS['APP_START_TIME'] );
}
