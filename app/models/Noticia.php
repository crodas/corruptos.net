<?php

/**
 *  @Persist("noticias")
 *  @Sluggable("titulo", "uri")
 *  @SingleCollection
 */
abstract class Noticia
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

    /** @Int @Inc */
    public $visitas = 0;

    /** @Int */
    public $comentarios;

    /** @Hash */
    public $keywords;

    /** @Bool */
    public $crawled = false;

    /** @Hash */
    public $crawled_data = array();

    protected function text($object)
    {
        $text = [];
        foreach ($object as $node) {
            $text[] = trim($node->textContent);
        }
        return implode("\n", $text);
    }

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

    public static function getType($url)
    {
        foreach (array('Abc') as $type) 
        {
            if ($type::is($url)) {
                return $type;
            }
        }
        return false;
    }

    public static function is_useful($url) 
    {
        $type = self::getType($url);

        if ($type) {
            return $type::is_useful_internal($url);
        }
        return false;
    }

    abstract public function crawl();

}
