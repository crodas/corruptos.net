<?php

namespace middleware;

use Service;
use Mobile_Detect;
use crodas\Form\Form;

/** @postRoute */
function inject_is_mobile($req, $unused, $args)
{
    $detect = new Mobile_Detect;
    $args['is_mobile'] = $detect->isMobile();
    $args['is_table'] = $detect->isTablet();
    $args['mobile_detect'] = $detect;

    return $args;
}

/** @postRoute */
function inject_menu($req, $unused, $args)
{
    $args['sections']  = Service::get('sections');
    $args['newspaper'] = Service::get('sources');
    if (empty($args['form'])) {
        $args['form'] = new Form;
    }
    
    // be fair
    uasort($args['newspaper'], function() { return rand(-1, 1); });

    return $args;
}

/** @Filter id */
function get_id($req, $name, $value)
{
    $db  = Service::get('db');
    $doc = $db->GetCollection('noticia')->findOne(['_id' => new \MongoId($value)]);
    if (empty($doc)) {
        return false;
    }
    $req->set($name, $doc);
    return true;
}

/** @Filter page */
function page($req, $name, $value)
{
    if (!is_numeric($value) || $value+0 !== (int)$value) {
        return false;
    }
    $req->set($name, $value+0);
    return true;
}

/** @Filter uri */
function uri($req, $name, $value)
{
    $db  = Service::get('db');
    $doc = $db->GetCollection($name)->findOne(['uri' => $value]);
    if (empty($doc)) {
        return false;
    }
    $req->set($name, $doc);
    return true;
}

