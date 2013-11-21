<?php

/**
 *  @Persist("noticias")
 *  @Sluggable("titulo", "uri")
 */
class Noticia
{
    /** @Id */
    public $id;

    /** @String @Unique */
    public $uri;

    /** @ReferenceMany("corruptos", [uri, nombre]) @Required */
    public $corruptos;

    /** @String @Required */
    public $titulo;

    /** @Date */
    public $creado;

    /** @String */
    public $texto;

    /** @String @Unique */
    public $url;

    /** @Int */
    public $hits;

    /** @Int */
    public $comentarios;

    /** @Hash */
    public $keywords;

    public static function getOrCreate($url)
    {
        $db  = Service::get("db");
        $col = $db->getCollection(__CLASS__)->findOne(['url' => $url]);
        if (empty($col)) {
            $col = new self;
            $col->url = $url;
        }

        return $col;
    }

}
