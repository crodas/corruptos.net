<?php

/**
 *  @Cli("ranking:simple")
 *  @Option('force')
 */ 
function ranking_simple($input)
{
    ini_set('memory_limit', '1G');

    $config = new \crodas\TextRank\Config;
    $config->addListener(new \crodas\TextRank\Stopword);
    $analizer = new \crodas\TextRank\TextRank($config);

    $db  = Service::get("db");
    foreach ($db->getCollection('noticia')->find() as $row) {
        if (!$row->crawled || (!empty($row->keywords) && !$input->getOption('force'))) continue;
        echo "{$row->titulo}\n";
        $texto = ForceUTF8\Encoding::toUTF8(implode(".\n", [
            $row->crawled_data['title'],
            $row->crawled_data['copete'],
            $row->crawled_data['texto'],
        ]));

        try {
            $row->keywords = [];
            foreach ($analizer->GetKeywords($texto) as $tag => $score) {
                $row->keywords[] = ForceUTF8\Encoding::toUTF8($tag);
            }
            $db->save($row);
        } catch (\Exception $e) {}


    }
}

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
        if (!empty($corrupto->keywords)) continue;

        $text = "";
        foreach ($db->getCollection('noticia')->find(['corruptos.uri' => $corrupto->uri]) as $row) {
            $row->texto = ForceUTF8\Encoding::toUTF8($row->texto);
            $db->save($row);
            $text .= $row->texto . "\n";

        }
        echo "{$corrupto->nombre}\n";

        $corrupto->keywords = array_keys($analizer->GetKeywords($text));
        $corrupto->summary  = $summary->GetSummary($text);
        try {
            $db->save($corrupto);
        } catch (\Exception $e) {
            $corrupto->keywords = array();
            $db->save($corrupto);
        }
    }
}
