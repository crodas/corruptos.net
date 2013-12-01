<?php

function __($x, $singular, $plural)
{
    return "$x ". ($x == 1 ? $singular : $plural);
}

function time_ago($time)
{
    if (is_string($time)) {
        $time = strtotime($time);
    }
    $time_elapsed = time() - $time;
    
    $seconds = $time_elapsed ; 
    $minutes = floor($time_elapsed / 60 );
    $hours = floor($time_elapsed / 3600); 
    $days = floor($time_elapsed / 86400 ); 
    $weeks = floor($time_elapsed / 604800); 
    $months = floor($time_elapsed / 2600640 ); 
    $years = floor($time_elapsed / 31207680 ); 

    if ($years > 0) return __($years, "año", "años");
    if ($months > 0) return __($months, "mes", "meses");
    if ($weeks > 0) return __($weeks, "semanas", "semanas");
    if ($days > 0) return __($days, "día", "días");
    if ($hours > 0) return __($hours, "hora", "horas");
    if ($minutes > 0) return __($minutes, "minuto", "minutos");
    
    return ___($time_elapsed, "segundo", "segundos");
}

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

function pagination($page, $total)
{
    $config = Service::get('config');
    $tpages = ceil($total / $config['per_page']);
    $pages  = range(1, min($config['show_pages'], $total));

    if ($total > $config['show_pages']+1) {
        $offset = max($config['show_pages']+1, $total-2);
        $pages = array_merge($pages, ['...'], range($offset, $total));
    }
    return $pages;
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
