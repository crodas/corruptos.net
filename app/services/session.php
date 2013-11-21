<?php

namespace Service;

/**
 *  @Service(session, {
 *      name: {default: 'sessionid'}
 *  }, {shared: true})
 */ 
function session_service($config)
{
    session_name($config['name']);
    session_start();

    return true;
}
