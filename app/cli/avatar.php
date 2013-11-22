<?php

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

