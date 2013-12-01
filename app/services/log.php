<?php

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

/**
 *  @Service("logger", {
 *      name: {default: 'botame-log'},
 *      dir: { default: '/tmp', type: dir },
 *  }, {shared: true})
 */
function log_service($config, $context)
{
    // create a log channel
    $name  = !empty($context['name']) ? $context['name'] : $config['name'];
    $fname = $name . date("Y-m-d") . ".log"; 
    $log = new Logger($name);
    $log->pushHandler(new StreamHandler($config['dir'] . '/' . $fname, Logger::INFO));
    $log->pushHandler(new StreamHandler($config['dir'] . '/error' . $fname, Logger::ERROR));

    return $log;
}
