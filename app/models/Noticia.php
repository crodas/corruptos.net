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

    /** @Bool */
    public $crawled = false;

    /** @Hash */
    public $crawled_data = array();

    private function text($object)
    {
        $text = [];
        foreach ($object as $node) {
            $text[] = trim($node->textContent);
        }
        return implode("\n", $text);
    }

    public function crawl()
    {
        if ($this->crawled) return;

        echo "Crawling {$this->url}\n";

        $content = file_get_contents($this->url);
        $dom = new \DomDocument;
        @$dom->loadHTML($content);
        $xpath = new \DOMXPath($dom);
        
        $title  = $this->text($xpath->query('//*[@id="article"]/h1'));
        $copete = $this->text($xpath->query('//*[@id="article"]/p'));
        $texto  = $this->text($xpath->query('//*[@id="article"]/div[@class="text"]/p'));
        $links  = [];
        $images = [];

        foreach ($xpath->query('//*[@id="article"]/div[@class="text"]/p/a') as $l) {
            $links[] = [
                $l->getAttribute('href'),
                $l->textContent
            ];
        }

        foreach ($xpath->query('//*[@id="thumbs-nav"]//a') as $l) {
            $images[] = $l->getAttribute('data-large');
        }

        $this->crawled_data = compact('title', 'copete', 'texto', 'links', 'images');
        $this->crawled = true;
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

}
