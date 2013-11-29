<?php

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
 *  @Route "/"
 *  @Route "/{page}"
 *  @Route "/ver/{tag}"
 *  @Route "/ver/{tag}/{page}"
 *  @View layout.tpl
 */
function get_home($req)
{
    $db    = Service::get('db');
    $query = array();
    $tag   = $req->get('tag');
    $page  = $req->get('page') ?: 0;
    $limit = 30;
    if (!empty($tag)) {
        $query = ['tags' => $tag];
    }

    $corruptos = $db->getCollection('corrupto')
        ->find($query)
        ->limit($limit+1)
        ->skip($page * $limit)
        ->sort(['hits' => -1]);
    if ($corruptos->count(true) == 0) {
        $req->notFound();
    }
    $has_next = $corruptos->count(true) == $limit+1;
    $base     = explode("/", $_SERVER['REQUEST_URI'])[1];
    $corruptos->limit($limit);
    return compact('corruptos', 'page', 'has_next', 'base');
}

/**
 *  @Route "/{uri:corrupto}/medio/{tipo}"
 *  @Route "/{uri:corrupto}/medio/{tipo}/{page}"
 *  @View corrupto.tpl
 */
function get_corruptos_medios($req)
{
    $corrupto = $req->get('corrupto');
    $page     = $req->get('page') ?: 0;
    $filter   = ['__type' => $req->get('tipo')];
    $base     = "/" . $corrupto->uri . "/medio/" . $req->get('tipo');
    $menu     = [
        '/audio/' . $corrupto->uri => ['Audios', false],
        '/' . $corrupto->uri => ['Noticias', false],
    ];
    return compact('corrupto', 'filter', 'page', 'base', 'menu');
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
    $base     = "/audio/" . $corrupto->uri;
    $menu     = [
        '/audio/' . $corrupto->uri => ['Audios', false],
        '/' . $corrupto->uri => ['Noticias', false],
    ];
    return compact('corrupto', 'filter', 'page', 'base', 'menu');
}

/**
 *  @Route "/noticia/{uri:noticia}"
 *  @View detalle.tpl
 */
function get_noticia($req)
{
    $noticia  = $req->get('noticia');
    $corrupto = current($noticia->corruptos); 
    if (empty($corrupto)) {
        $req->notFound();
    }
    $autoplay = true;
    $menu     = [
        '/audio/' . $corrupto->uri => ['Audios', false],
        '/' . $corrupto->uri => ['Noticias', false],
    ];
    return compact('corrupto', 'noticia', 'autoplay', 'menu');
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
    $base     = "/audio/" . $corrupto->uri;
    $menu     = [
        '/audio/' . $corrupto->uri => ['Audios', false],
        '/' . $corrupto->uri => ['Noticias', false],
    ];
    return compact('corrupto', 'page', 'filter', 'base', 'menu');
}
