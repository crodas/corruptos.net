<?php

/**
 *  @Persist
 */
class Ultimahora extends Noticia
{
    public static function is($url)
    {
        return preg_match('/ultimahora\.com/', $url);
    }

    public function crawl()
    {
        if ($this->crawled && $this->version == 2) return;
        $xpath = Http::wget($this->url);

        $title  = Http::text($xpath->query('//h1'));
        var_Dump($title);
        $fecha  = Http::fecha(Http::text($xpath->query('//*[@class="floatright"]')));
        $copete = Http::text($xpath->query('//*[@class="news-headline-obj"]'));
        $texto  = Http::text($xpath->query('//*[@class="newDetailTextChange"]'));

        $this->creado  = $fecha;
        $this->titulo  = $title;
        $this->texto   = $copete;
        $this->version = 2;
        $this->crawled = true;
        $this->crawled_data = compact('title', 'fecha', 'copete', 'texto');
    }
}
