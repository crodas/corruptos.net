<?php

/**
 *  @Cli("ranking")
 */ 
function ranking()
{
    $config = new \crodas\TextRank\Config;
    $config->addListener(new \crodas\TextRank\Stopword);
    $analizer = new \crodas\TextRank\TextRank($config);

    $db = Service::get('db');
    foreach ($db->getCollection('corrupto')->find() as $corrupto) {
        $text = "";
        foreach ($db->getCollection('noticia')->find(['corruptos.uri' => $corrupto->uri]) as $row) {
            $text .= $row->texto . "\n";
        }
        $corrupto->keywords = $analizer->GetKeywords($text);
        $db->save($corrupto);
    }
}
