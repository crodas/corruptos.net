<?php

/** @postRoute */
function inject_menu($req, $unused, $args)
{
    $args['menu'] = [
        '/' => ['Portada', false],
        '/locales' => ['Locales Adheridos', false],
    ];
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

/** @Filter uri */
function uri($req, $name, $value)
{
    $db  = Service::get('db');
    $doc = $db->GetCollection('corrupto')->findOne(['uri' => $value]);
    if (empty($doc)) {
        return false;
    }
    $req->set($name, $doc);
    return true;
}

/** @Route("/go/{id:noticia}") */
function go($req)
{
    $noticia = $req->get('noticia');
    $noticia->visitas++;
    Service::get('db')->save($noticia);
    header("Location: {$noticia->url}");
    exit;
}


/**
 *  @Route /locales
 *  @View locales.tpl
 */
function get_locales() {
    return [];
}

/**
 *  @Route /
 *  @View layout.tpl
 */
function get_home($req)
{
    $db = Service::get('db');
    $corruptos = $db->getCollection('corrupto')->find()->sort(['hits' => -1]);
    return compact('corruptos');
}

/**
 *  @Route "/audio/{uri:corrupto}"
 *  @Route "/audio/{uri:corrupto}/{page}"
 *  @View corrupto.tpl
 */
function get_corruptos_audio($req)
{
    $corrupto = $req->get('corrupto');
    $page     = $req->get('page') ?: 0;
    $filter   = ['is_audio' => true];
    return compact('corrupto', 'filter', 'page');
}

/**
 *  @Route "/{uri:corrupto}"
 *  @Route "/{uri:corrupto}/{page}"
 *  @View corrupto.tpl
 */
function get_corruptos($req)
{
    $corrupto = $req->get('corrupto');
    $page     = $req->get('page') ?: 0;
    $filter   = [];
    return compact('corrupto', 'page', 'filter');
}
