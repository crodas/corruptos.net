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
    $sitemap->generate($base . '/corruptos.xml', 'https://corruptos.net');

    $sitemap = new Sitemap($db->getCollection('noticias')->find()->sort(['$natural' => -1])->limit(20000), function($corrupto) {
        return "/noticias" . $corrupto->uri;
    });
    $sitemap->generate($base . '/noticias.xml', 'https://corruptos.net');
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
