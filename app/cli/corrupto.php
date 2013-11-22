<?php

/** 
 *  @Cli("db:index") 
 */
function index($input, $output)
{
    Service::get('db')->ensureIndex();
}

/** 
 *  @Cli("corrupto:avatar") 
 *  @Arg("uri", REQUIRED)
 */
function select_frontimage_one($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find(['uri' => $input->GetArgument('uri')]) as $corrupto) {
        echo "{$corrupto->nombre}\n";
        $candidates = [];
        foreach ($conn->getCollection('noticias')->find(['corruptos.uri' => $corrupto->uri]) as $noticia) {
            if (!empty($noticia->crawled_data['images'])) {
                $candidates = array_merge($candidates, $corrupto->selectImage($noticia->crawled_data['images']));
            }
        }
        shuffle($candidates);
        $corrupto->avatar = current($candidates);
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:avatar:all") 
 */
function select_frontimage($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find() as $corrupto) {
        echo "{$corrupto->nombre}\n";
        $candidates = [];
        foreach ($conn->getCollection('noticias')->find(['corruptos.uri' => $corrupto->uri]) as $noticia) {
            if (!empty($noticia->crawled_data['images'])) {
                $candidates = array_merge($candidates, $corrupto->selectImage($noticia->crawled_data['images']));
            }
        }
        shuffle($candidates);
        $corrupto->avatar = current($candidates);
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:clean-up") 
 */
function cleaup_things($input, $output)
{
    $noticia = Noticia::getOrCreate("http://www.cardinal.com.py/noticias/senadora_blanca_fonseca_mi_nica_torpeza_fue_no_medir_la_reaccin_de_la_ciudadana_23326.html");
    $noticia->crawl();
    var_dump($noticia);exit;

    $conn = Service::get('db');
    foreach ($conn->getCollection('noticias')->Find() as $noticia) {
        if (!Noticia::is_useful($noticia->url)) {
            $conn->delete($noticia);
        }
    }
}


/** 
 *  @Cli("corrupto:actualizar") 
 */
function update($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find() as $corrupto) {
        echo "Actualizando {$corrupto->nombre}\n";
        $corrupto->update();
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:agregar") 
 *  @Arg("nombre", REQUIRED)
 */
function agregar($input, $output)
{
    $nombre = $input->GetArgument('nombre');

    if ($nombre == "CARLOS NÚÑEZ AGÜERO") {
        // seems odd but abc's search is a bit silly
        $nombre = "SENADOR CARLOS NÚÑEZ";
    }

    $corrupto = Corrupto::getOrCreate($nombre);
    $corrupto->update();
}
