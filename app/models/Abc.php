<?php


/**
 *  @Persist
 */
class Abc extends Noticia
{
    public static function is($url)
    {
        return preg_match('/abc\.com\.py/', $url);
    }

    public function crawl()
    {
        if ($this->crawled && !empty($this->crawled_data['texto'])) return;

        echo "Crawling {$this->url}\n";

        $xpath = Http::wget($this->url);
        
        $title  = Http::text($xpath->query('//*[@id="article"]/h1'));
        $copete = Http::text($xpath->query('//*[@id="article"]/p'));
        $texto  = Http::text($xpath->query('//*[@id="article"]/div[@class="text"]'));
        $links  = [];
        $images = [];

        foreach ($xpath->query('//*[@id="article"]/div[@class="text"]/a') as $l) {
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
}
