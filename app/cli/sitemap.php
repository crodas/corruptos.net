<?php

/** @Cli("generate:sitemap") */
function sitemap()
{
    $db   = Service::get('db');
    $base = __DIR__ . '/../../public_html/sitemap/';
    $sitemap = new Sitemap($db->getCollection('corruptos')->find(), function($corrupto) {
        return [
            "/" . $corrupto->uri,
            "/" . $corrupto->uri . "/audio"
        ];
    });
    $sitemap->generate($base . '/corruptos.xml');
    $sitemap = new Sitemap($db->getCollection('noticias')->find()->limit(1000), function($corrupto) {
        return"/noticias" . $corrupto->uri;
    });
    $sitemap->generate($base . '/noticias.xml');
}
