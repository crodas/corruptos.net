<?php

/**
 *  @Cli("ranking")
 */ 
function ranking()
{
    ini_set('memory_limit', '1G');

    $config = new \crodas\TextRank\Config;
    $config->addListener(new \crodas\TextRank\Stopword);
    $analizer = new \crodas\TextRank\TextRank($config);
    $summary  = new \crodas\TextRank\Summary($config);

    $db = Service::get('db');
    foreach ($db->getCollection('corrupto')->find() as $corrupto) {
        $text = "";
        foreach ($db->getCollection('noticia')->find(['corruptos.uri' => $corrupto->uri]) as $row) {
            $text .= $row->texto . "\n";
        }
        echo "{$corrupto->nombre}\n";
        $corrupto->keywords = array_keys($analizer->GetKeywords($text));
        $corrupto->summary  = $summary->GetSummary($text);
        try {
            $db->save($corrupto);
        } catch (\Exception $e) {}
    }
}
