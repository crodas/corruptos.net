<?php

/**
 *  @Persist("corruptos")
 *  @Sluggable("nombre", "uri")
 *  @RefCache(nombre, uri)
 */
class Corrupto
{
    const PER_PAGE = 20;

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

    public function update()
    {
        $conn   = Service::get('db');
        $search = Service::get('search');
        $this->fixImage();

        foreach($search($this->nombre) as $news) {
            if (!Noticia::is_useful($news->url)) {
                echo "\t {$news->url}  is not useful\n";
                continue;
            }

            $not = Noticia::getOrCreate("http://www.abc.com.py/" . $news->url);
            $not->titulo   = $news->titulo;
            $not->texto    = $news->copete;
            if (empty($not->creado)) {
                $not->creado   = new MongoDate(strtotime($news->publicacion));
            }
            $not->hits        = $news->hits;
            $not->comentarios = $news->comentarios;
            $not->corruptos[] = $this;
            $not->foto     = $news->corte_url;
            $this->foto[] = $news->corte_url;
            try {
                $not->crawl();
            } catch (\Exception $e) {}
            try {
                $conn->save($not);
            } catch (\Exception $e) {
                echo (string)$e . "\n";
            }
        }
        $this->fixImage();
        $news = $conn->getCollection('noticias');
        $this->hits = $news->sum('hits', ['corruptos.uri' => $this->uri]);
        $this->comentarios = $news->sum('commentarios', ['corruptos.uri' => $this->uri]);
        $this->total_noticias = $news->count(['corruptos.uri' => $this->uri]);
        $conn->save($this);
    }

    public function fixImage()
    {
        $names = explode(" ", strtolower(preg_replace('/\?+/', '.', mb_convert_encoding($this->nombre, 'ascii'))));

        $cands = [];
        foreach ($this->foto as $url) {
            foreach ($names as $name) {
                if (preg_match("/$name/", $url)) {
                    $cands[] = $url;
                    break;
                }
            }
        }

        $this->foto = array_unique($cands);
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

    public function getNoticias($page, &$has_more)
    {
        $db  = Service::get('db');
        $col = $db->getCollection('noticias')
            ->find(['corruptos.uri' => $this->uri])
            ->skip($page * self::PER_PAGE)
            ->limit(self::PER_PAGE)
            ->sort(['hits' => -1]);
        $has_more = $col->count() > ($page+1)*self::PER_PAGE;
        return $col;
    } 
}
