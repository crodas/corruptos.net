<?php

use crodas\SitemapGenerator\SitemapGenerator;

/** @Cli("generate:sitemap") */
function sitemap()
{
    $db   = Service::get('db');
    $base = __DIR__ . '/../../public_html/sitemap/';

    $generator = new SitemapGenerator("https://corruptos.net/sitemap", $base);
    $generator->addMap($db->getCollection('corruptos')->find(), function($corrupto) {
        return [
            "/" . $corrupto->uri,
            "/" . $corrupto->uri . "/audio"
        ];
    }, 'corruptos.xml');

    $generator->limit(1000);
    $generator->addMap($db->getCollection('noticias')->find()->sort(['$natural' => -1])->limit(20000), function($corrupto) {
        return "/noticia/" . $corrupto->uri;
    }, 'noticias.xml');
}

/** @Cli("generate:images", "host corruptos' images locally") */
function images()
{
    $db   = Service::get('db');
    $base = realpath(__DIR__ . '/../../public_html/images/photos/');
    foreach($db->getCollection('corruptos')->find() as $corrupto) {
        $ext = explode('.', $corrupto->image);
        $ext = strtolower(end($ext));
        `wget "{$corrupto->image}" -O "{$base}/{$corrupto->id}.{$ext}"`;
        `wget "{$corrupto->avatar}" -O "{$base}/{$corrupto->id}:large.{$ext}"`;
    }
    `cd $base ; jpegoptim --strip-all * ; chmod +r *`;
}
