<?php


/**
 *  @Persist
 */
class Abc extends Noticia
{
    public static function is($url)
    {
        return preg_match('/abc\.com/', $url);
    }

    protected static function is_useful_internal($url)
    {
        return preg_match('/abc-radio|congresistas|editorial|judicial|locales|interior|politica|policiales|economia|nacionales|articulos/', $url);
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
}
