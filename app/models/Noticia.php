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

    /** @ReferenceMany("corruptos", [uri, nombre]) */
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

    /** @Int */
    protected $version = 0;

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
            if (!$class) return false;
            $col = new $class;
            $col->url = $url;
        }

        return $col;
    }

    public function isAbout(Corrupto $corrupto)
    {
        return $this->checkContext($corrupto->nombre) 
            || $this->checkContext($corrupto->apodo)
            || $this->checkContext($corrupto->partido . ' ' . $corrupto->nombre)
            || $this->checkContext($corrupto->cargo   . ' ' . $corrupto->nombre);
    }

    public function checkContext($name)
    {
        if (empty($name)) { 
            return false; 
        }

        $names = explode(" ", strtolower(iconv('UTF-8','ASCII//TRANSLIT',$name)));
        $args  = array_merge([$this->titulo, $this->texto], $this->crawled_data);

        foreach ($args as $texto) {
            if (is_array($texto)) continue;

            $parts = array_filter(preg_split('/[^a-z]+/', strtolower(iconv('UTF-8','ASCII//TRANSLIT', $texto))));

            $index = [];
            foreach ($names as $name) {
                $index[$name] = array_search_all($name, $parts);
            }

            if (check_context($names, $index)) {
                return true;
            }

        }

        return false;
    }

    /** @onHydratation */
    public function onHydratation()
    {
        if (empty($this->texto)) {
            if (!empty($this->crawled_data['texto'])) {
                try {
                    $this->texto = mb_substr($this->crawled_data['texto'], 0, 500, "UTF-8") . "...";
                    Service::get('db')->save($this);
                } catch (\Exception $e) {
                }
            }
        }
    }


    public static function getType($url)
    {
        foreach (array('Ultimahora', 'Hoy', 'Paraguay', 'Abc', 'Cardinal', 'Nanduti') as $type) {
            if ($type::is($url)) {
                return $type;
            }
        }
        return false;
    }


    public function fuente()
    {
        return get_parent_class($this);
    }

    public function render()
    {
        echo $this->texto;
    }

    abstract public function crawl();

}
