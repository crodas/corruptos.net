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

function strtowords($text)
{
    $parts = preg_split(
        '/([^a-z]+)/m', 
        strtolower(iconv('UTF-8','ASCII//TRANSLIT', $text)),
        -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY
    );

    if (empty($parts)) {
        return [];
    }

    if (preg_match('/[^a-z]/', $parts[0])) {
        // remove first element as we expect <word> <separator>
        // and not <separator> <word>
        array_shift($parts);
    }

    $parts = array_chunk($parts, 2);

    $words = [];
    $id    = 0;
    foreach ($parts as $word) {
        $words[$id] = $word[0];
        if (!empty($word[1]) && preg_match('/[,\n.;0-9]/', $word[1])) {
            if (strlen($word[0]) != 1 && trim($word[1]) != '.') {
                // "foo c. rodas" is still the same sentence
                $id += 100;
            }
        }
        $id++;
    }

    return $words;

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
