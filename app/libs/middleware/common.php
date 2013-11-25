<?php

namespace middleware;

use Service;
use Mobile_Detect;

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
    $args['menu'] = array_merge([
            '/ver_los23' => ['Los 23', false],
            '/ver_senador' => ['Senadores', false],
            '/ver_diputado' => ['Diputados', false],
            '/locales' => ['Locales Adheridos', false],
        ]
        , empty($args['menu']) ? [] : $args['menu']
    );
    if (!empty($args['menu'][$_SERVER['REQUEST_URI']])) {
        // Go home @crodas you're drunk!
        $args['menu'][$_SERVER['REQUEST_URI']][1] = true;
    }
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

