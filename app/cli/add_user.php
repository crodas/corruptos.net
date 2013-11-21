<?php

/** 
 *  @Cli("db:index") 
 */
function index($input, $output)
{
    Service::get('db')->ensureIndex();
}

/** 
 *  @Cli("corrupto:agregar") 
 *  @Arg("nombre", REQUIRED)
 */
function agregar($input, $output)
{
    $nombre = $input->GetArgument('nombre');
    $conn   = Service::get('db');
    $search = Service::get('search');

    $corrupto = Corrupto::getOrCreate($nombre);
    foreach($search($nombre) as $news) {
        $not = Noticia::getOrCreate("http://www.abc.com.py/" . $news->url);
        $not->titulo   = $news->titulo;
        $not->texto    = $news->copete;
        $not->creado   = new MongoDate(strtotime($news->publicacion));
        $not->corruptos[] = $corrupto;
        $not->foto     = $news->corte_url;
        $corrupto->foto[] = $news->corte_url;
        try {
            $corrupto->total_noticias++;
            $conn->save($not);
        } catch (\Exception $e) {}
    }
}
