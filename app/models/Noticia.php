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

        return trim(implode("\n", $text));
    }

    protected function wget($url)
    {
        $ch = curl_init($url);
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => 1,
            CURLOPT_USERAGENT =>  'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5',
        ]);
        $html = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

        curl_close($ch);

        if (is_callable('tidy_repair_string')) {
            // thanks cardinal for given me a hard time serving broken html
            $html = tidy_repair_string($html, array('wrap' => 0), 'utf8');
        }

        return ForceUTF8\Encoding::toUTF8($html);
    }

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

    public static function getType($url)
    {
        foreach (array('Abc', 'Cardinal') as $type) 
        {
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
