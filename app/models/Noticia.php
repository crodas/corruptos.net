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

    /** @Int */
    public $total_comentarios = 0;

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
        $nombres = [];
        if (empty($corrupto->nombres)) {
            var_dump($corrupto->uri);exit;
        }

        extract($corrupto->nombres);
        $nombres[] = [$corrupto->cargo,  $apellido[0]];
        $nombres[] = [$corrupto->partido, $apellido[0]]; 

        if (count($apellido) > 1) {
            for($i = 2; $i <= count($apellido); $i++) {
                $nombres[] = array_slice($apellido, 0, $i);
            }
        }
        for ($i = 1; $i <= count($nombre); $i++) {
            for($e = 1; $e <= count($apellido); $e++) {
                $nombres[] = array_merge(
                    array_slice($nombre, 0, $i),
                    array_slice($apellido, 0, $e)
                );
            }
        }

        foreach ($nombres as $nombre) {
            $nombre = implode(" ", $nombre);
            $found = $this->checkContext($nombre) 
                || $this->checkContext($corrupto->apodo)
                || $this->checkContext($corrupto->partido . ' ' . $nombre)
                || $this->checkContext($corrupto->cargo   . ' ' . $nombre);
            if ($found) {
                return true;
            }
        }

        return false;
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

            $parts = strtowords($texto);

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
                    $this->texto = mb_substr($this->crawled_data['texto'], 0, 1000, "UTF-8") . "...";
                    Service::get('db')->save($this);
                } catch (\Exception $e) {
                }
            }
        }
    }


    public static function getClasses()
    {
        return  array(
            'Ultimahora', 'Hoy', 'Paraguay', 'Abc', 'Cardinal', 'Nanduti'
        );
    }

    public static function getType($url)
    {
        foreach (self::getClasses() as $type) {
            if ($type::is($url)) {
                return $type;
            }
        }
        return false;
    }

    /**
     *  Check if a given URL (news) was already crawled 
     *
     *  @return bool
     */
    public static function exists($url)
    {
        return Service::get('db')->getCollection(__CLASS__)->count(compact('url')) == 1;
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
