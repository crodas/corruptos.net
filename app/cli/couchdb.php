<?php

/** @Cli("couchdb") */
function cli_couchdb()
{
    $noticias = Service::get('db')
        ->getCollection('noticias')
        ->find();

    foreach ($noticias as $noticia) {
        if (!empty($noticia->corruptos)) {
            $personas = array();
            foreach ($noticia->corruptos as $corrupto) {
                $personas[] = [
                    '_id' => $corrupto->uri,
                    'nombre' => $corrupto->nombre,
                ];
            }
            $save = [
                '_id' => urlencode('noticia/' . $noticia->uri),
                'fuente' => $noticia->fuente(),
                'url'    => $noticia->url,
                'titulo' => $noticia->titulo,
                'copete' => $noticia->texto,
                'personas' => $personas,
                'extracted_data' => $noticia->crawled_data,
            ];
            save_couchdb($save);
        }
    }
}
