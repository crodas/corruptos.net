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
    $pages  = range(1, min($config['show_pages'], $tpages));

    if ($tpages > $config['show_pages']+1) {
        $offset = max($config['show_pages']+1, $tpages-2);
        $pages = array_merge($pages, ['...'], range($offset, $tpages-1));
    }
    return $pages;
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

function array_pick_min(Array &$index)
{
    $total = [];
    foreach($index as $id => $content) {
        $total[$id] = count($content);
    }
    asort($total);

    $min = $index[key($total)];
    unset($index[key($total)]);
    return $min;
}

function check_context(Array $names, Array $index)
{
    if (!has_something($index)) {
        return false;
    }

    foreach (array_pick_min($index) as $word => $pos) {
        $founds = array();
        $i = 1;
        foreach ($index as $id => $word1) {
            $found = false;
            foreach($word1 as $pos1) {
                $found = abs($pos - $pos1) < (4*$i++);
                if ($found) {
                    break;
                }
            }
            if (!$found) {
                continue 2;
            }
            $founds[$id] = abs($pos - $pos1);
        }
        return true;
    }

    return false;
}
