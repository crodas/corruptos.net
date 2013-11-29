<?php

/**
 *  @Cli("fulltext:create")
 */
function fulltext_create($input, $output)
{
    $index = Service::get('esearch');

    try {
        $index->create([
            'number_of_shards' => 4,
            'number_of_replicas' => 1,
            'properties' => [
                'fecha' => 'date',
                'corruptos' => [
                    'properties' => [
                        'nombre' => ['type' => 'string'],
                    ],
                ],
            ],
            'analysis' => array(
                'analyzer' => array(
                    'indexAnalyzer' => array(
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => array('lowercase', 'mySnowball')
                    ),
                    'searchAnalyzer' => array(
                        'type' => 'custom',
                        'tokenizer' => 'standard',
                        'filter' => array('standard', 'lowercase', 'mySnowball')
                    )
                ),
                'filter' => array(
                    'mySnowball' => array(
                        'type' => 'snowball',
                        'language' => 'Spanish'
                    )
                )
            ),
        ], true);
    } catch (\Exception $e){
        throw $e;
    }

    $type = $index->getType('noticias'); 
    $col  = Service::get('db')->getCollection('noticias');
    $documents = [];
    $i = 0;
    foreach ($col->Find() as $row) {
        $i++;
        if (empty($row->corruptos)) {
            continue;
        }
        $doc = [
            'id'    => (string)$row->id, // to avoid yet another if in the view
            'uri'    => $row->uri,
            'fuente' => $row->fuente(),
            'titulo' => $row->titulo,
            'texto'  => $row->texto,
            'creado'  =>  date('c', $row->creado->sec),
            'extra'  => $row->crawled_data,
            'url'    => $row->url,
            'is_audio' => !empty($row->is_audio),
            'corruptos' => array_map(function($c) {
                return [
                    'id' => (string)$c->id,
                    'nombre' => $c->nombre,
                    'uri' => $c->uri,
                ];
            }, $row->corruptos),
        ];

        $documents[] = new \Elastica\Document((string)$row->id, $doc);
        if (count($documents) % 1000 === 0) {
            echo $i . "\n";
            $type->addDocuments($documents);
            $documents = [];
        }
    }
    if (count($documents) >  0) {
        $type->addDocuments($documents);
    }
    $type->getIndex()->refresh();
}
