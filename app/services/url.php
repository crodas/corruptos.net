<?php

namespace Service;

use Service as S;

/**
 *  @Service(url, {
 *  }, {shared: true})
 */ 
function url_service($config)
{
    $router = S::get("dispatcher");
    return function($name, Array $args = array()) use ($router) {
        return $router->getRoute($name, $args);
    };
}
