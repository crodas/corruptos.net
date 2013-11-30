<?php

/**
 *  @Persist
 */
class Cardinal extends Noticia
{
    /** @Bool */
    public $is_audio = true;

    public static function is($url)
    {
        return preg_match('/cardinal\.com/', $url);
    }

    public static function search($text)
    {
        $alls  = [];
        $max   = 2;

        $alls = array();
        $hits = 0;
        $comentarios = 0;
        for ($i=0; $i < $max; $i++) {
            $xpath = Http::wget('http://www.cardinal.com.py/buscar.html?' . http_build_query(['busqueda' => $text, 'page'=>$i+1]), 3600);
            $max   = (int)Http::text($xpath->query('(//*[@id="resultados-busqueda"]/div[@class="paginacion"]/a)[last()]'));


            foreach ($xpath->query('//*[@id="resultados-busqueda"]//ul//a') as $link) {
                $parent = $link->parentNode->parentNode;
                $titulo = Http::text($link);
                $url    = "http://www.cardinal.com.py" . $link->getAttribute('href');
                if (self::exists($url)) {
                    break;
                } 

                $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits');
            }
        }

        return $alls;
    }

    public function crawl()
    {
        if ($this->crawled && $this->version == 2) return;

        $xpath = Http::wget($this->url);

        $title = Http::text($xpath->query('//*[@id="noticias-list"]//div[@class="description"]/a'));
        $mp3   = $xpath->query('//*[@id="noticias-list"]//div[@class="links"]/a[1]')->item(0)->getAttribute('href');
        $html  = $xpath->query('//*[@id="embed_code"]')->item(0)->getAttribute('value');
        $this->titulo = $title;
        $this->texto  = $html;

        $tags = array();
        foreach ($xpath->query('//*[@id="etiquetas"]/a') as $etiqueta) {
            $tags[] = Http::text([$etiqueta]);
        }

        $tags_txt = implode("\n", $tags);
        $this->crawled_data = compact('title', 'html', 'tags', 'mp3', 'tags_txt');
        $this->publicacion = Http::fecha($xpath->query('//*[@class="date"]'));
        $this->crawled = true;
        $this->version = 2;
    }

}
