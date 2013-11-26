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
        $this->crawled = true;
        $this->version = 2;
    }

}
