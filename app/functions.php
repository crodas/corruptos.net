<?php

function has_something(Array $values)
{
    $i = 0;
    foreach ($values as $val) {
        if (!empty($val)) {
            $i++;
        }
    }

    return $i >= 2;
}

function dlog($text, $type = 'info')
{
    static $logger;
    if (empty($logger)) {
        $logger = Service::get('logger');
    }
    $logger->{'add'  . $type}($text);
}

function save_couchdb($object) 
{
    static $conn;
    if (!$conn) {
        $conn = Service::get('couchdb');
    }
    if (!empty($object['_id'])) {
        $origin = $conn->getDocument($object['_id']);
        if ($origin) {
            $object = array_merge($origin, $object);
        }
    }
    $conn->saveDocument($object['_id'], $object);
}


function array_search_all($needle, $haystack)
{
    $array = [];
    foreach ($haystack as $k=>$v) {
        if($haystack[$k] == $needle){
            $array[] = $k;
        }
    }
    return ($array);
}

function check_context(Array $names, Array $index)
{
    if (!has_something($index)) {
        return false;
    }

    $checks = [];
    $total  = count($names) - 1;
    for ($i=0; $i < $total; $i++) {
        for($e=$i; $e < $total; $e++) {
            $checks[] = [$index[$names[$i]], $index[$names[$e+1]]];
        }
    }
    foreach ($checks as $check) {
        $i = 0;
        $ti = count($check[0]);
        $te = count($check[1]);
        for ($i=0; $i < $ti; $i++) {
            $e = 0;
            for ($e; $e < $te; $e++) {
                if (abs($check[0][$i] - $check[1][$e]) < 5) {
                    return true;
                }
            }
        }
    }

    return false;
}
