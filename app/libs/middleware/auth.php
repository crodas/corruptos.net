<?php

namespace Lib\Middleware;

use Auth, Service;

/**
 *  @preRoute Auth 
 *  @Last
 */
function user_check($req)
{
    if (!Auth::check()) {
        $url = Service::get("url");
        $_SESSION['intended'] = $_SERVER['REQUEST_URI'];
        header("Location: " . $url("user_login"));
        exit;
    }

    return true;
}
