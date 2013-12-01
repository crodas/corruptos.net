<?php

/**
 *  @Service(couchdb, {
 *      db: {required: true},
 *      host: {default: 'localhost'},
 *      port: {default: 5984},
 *      user: {default: null},
 *      password: {default: null},
 *  }, {shared: true})
 */
function couch_service($config)
{
    $client = new BF\Coucher\Client("http://{$config['host']}:{$config['port']}");
    $client->connect($config['user'], $config['password']);
    $client->selectDatabase($config['db']);
    return $client;
}
