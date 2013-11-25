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
    public $corruptos = array();

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

    public static function getOrCreate($url)
    {
        $db  = Service::get("db");
        $col = $db->getCollection(__CLASS__)->findOne(['url' => $url]);
        if (empty($col)) {
            $class = self::getType($url);
            $col = new $class;
            $col->url = $url;
        }

        return $col;
    }

    /** @onHydratation */
    public function onHydratation()
    {
        if (!empty($this->texto)) {
            if (!empty($this->crawled_data['texto'])) {
                try {
                    $this->texto = ForceUTF8\Encoding::toUTF8($this->crawled_data['texto']);
                    Service::get('db')->save($this);
                } catch (\Exception $e) {}
            }
        }
    }


    public static function getType($url)
    {
        foreach (array('Abc', 'Cardinal', 'Nanduti') as $type) {
            if ($type::is($url)) {
                return $type;
            }
        }
        return false;
    }

    protected static function is_useful_internal($url)
    {
        return true;
    }

    public function fuente()
    {
        return get_parent_class($this);
    }

    public function render()
    {
        echo $this->texto;
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
