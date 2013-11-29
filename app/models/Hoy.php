<?php

/**
 *  @Persist
 */
class Hoy extends Noticia
{
    public static function is($url)
    {
        return preg_match('/hoy\.com\.py/', $url);
    }

    public function crawl()
    {
        if ($this->crawled && $this->version == 2) return;

        $xpath = Http::wget($this->url);

        $selector = '//*[@class="main_content"]/*[@class="body"]';

        $title  = http::text($xpath->query('//*[@class="main_content"]/h1'));
        $copete = http::text($xpath->query($selector . '/*[@class="summary"]'));
        $texto  = http::text($xpath->query($selector));

        $links  = [];
        $images = [];

        foreach ($xpath->query($selector . '//a') as $l) {
            if ($l->firstChild && !empty($l->firstChild->tagName) && $l->firstChild->tagName == "img") {
                $links[] = $l->getAttribute('href');
            } else {
                $links[] = [
                    $l->getAttribute('href'),
                    $l->textContent
                ];
            }
        }

        $this->crawled_data = compact('title', 'copete', 'texto', 'links', 'images');
        $this->crawled = true;
        $this->version = 2;
    }
}
