<?php

/**
 *  @Persist("corruptos")
 *  @Sluggable("nombre", "uri")
 *  @RefCache(nombre, uri)
 */
class Corrupto
{
    /** @Id */
    public $id;

    /** @String @Unique */
    public $uri;

    /** @String @Required */
    public $nombre;

    /** @Int */
    public $hits;

    /** @Int */
    public $comentarios;

    /** @Array */
    public $foto;

    /** @String */
    public $summary;

    /** @Int */
    public $total_noticias;

    /** @Array */
    public $keywords;

    public function getImage()
    {
        if (empty($this->foto)) {
            return null;
        }
        shuffle($this->foto);
        return current($this->foto);
    }
    
    public static function getOrCreate($nombre)
    {
        $db  = Service::get('db');
        $col = $db->getCollection(__CLASS__);
        $doc = $col->findOne(['nombre' => $nombre]);
        if (empty($doc)) {
            $doc = new self;
            $doc->nombre = $nombre;
            $db->save($doc);
        }
        return $doc;
    }
}
