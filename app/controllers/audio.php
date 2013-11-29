<?php

/** 
 * @Route("/play/audio/{id:noticia}") 
 */
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
 *  @Route("/noticia/{uri:noticia}/twitter") 
 *  @View("embed/twitter")
 */
function get_twitter_audio($req)
{
    $noticia = $req->get('noticia');
    if (!$noticia->is_audio || empty($noticia->crawled_data['mp3'])) {
        $req->notFound();
    }

    return compact('noticia');
}

