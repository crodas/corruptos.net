<?php

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/functions.php";

use Whoops\Run;
use Whoops\Handler\PrettyPageHandler;

mb_internal_Encoding("UTF-8");

$service = new \ServiceProvider\Composer(
    __DIR__ . "/configs/app.yml",
    'Service',
    __DIR__ . "/../tmp/services.php",
    array(__DIR__ . '/services/*.php')
);

if (Service::Get('devel') && PHP_SAPI !== 'cli') {
    $run     = new Run;
    $handler = new PrettyPageHandler;
    $run->pushHandler($handler);
    $run->register();
}

