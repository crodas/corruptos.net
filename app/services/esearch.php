<?php

/**
 *  @Service("esearch", {
 *      server:  {"required": true},
 *      name:  {"required": true},
 *  }, {shared: true})
 */
function esearch($config)
{
    $elastica = new \Elastica\Client($config['server']);
    return $elastica->getIndex($config['name']);
}
