<?php

/** @Persist */
class Paraguay extends Noticia
{
    public static function is($url)
    {
        return preg_match('/paraguay\.com\//', $url);
    }

    public function crawl()
    {
        if ($this->crawled) return;

        $page = Http::wget($this->url);
        $content = $page->query('//div[@class="interior_main_column"]')->item(0);

        $categoria = Http::text($page->query('.//*[@class="news_category_and_date"]/a', $content));
        $title     = Http::text($page->query('.//h1', $content));
        $copete    = Http::text($page->query('.//*[@class="copete"]', $content));
        $texto     = Http::text($page->query('.//*[@class="news_story"]', $content));

        $images = [];
        $links  = [];
        foreach ($page->query('.//a', $content) as $link) {
            $links[] = [
                $link->getAttribute('href'),
                $link->textContent,
            ];
        }
        foreach ($page->query('.//img', $content) as $img) {
            $images[] = $img->GetAttribute('src');
        }

        $this->crawled_data = compact('title', 'copete', 'texto', 'links', 'images', 'categoria');
        $this->crawled  = true;
    }
}
