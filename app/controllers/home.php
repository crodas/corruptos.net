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

/** @Route("/play/audio/{id:noticia}") */
function get_audio($req)
{
    $noticia = $req->get('noticia');
    if (!$noticia->is_audio || empty($noticia->crawled_data['mp3'])) {
        $req->notFound();
    }
    $noticia->listened++;
    Service::get('db')->save($noticia);
    header("Location: {$noticia->crawled_data['mp3']}");
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
