<?php

/** @Persist */
class Paraguay extends Noticia
{
    public static function is($url)
    {
        return preg_match('/paraguay\.com\//', $url);
    }

    public static function search($text)
    {
        $urls = [];
        $col  = Service::get('db')->getCollection(__CLASS__);
        $alls = [];
        $comentarios = $current = $hits = 0;

        while (true) {
            $page    = Http::wget('http://paraguay.com/buscar/' . urlencode($text) . '/pagina/' . (++$current), 3600);
            $stories = $page->query('//div[@class="story"]');
            if ($stories->length == 0) {
                // so long and thanks for all the firsh @mikeotr!
                break;
            }

            $current_page = "/pagina/{$current}";
            foreach ($stories as $story) {
                $link   = $page->query('./h1/a', $story)->item(0);
                $titulo = Http::text($link);
                $url    = 'http://paraguay.com' . $link->getAttribute('href');
                if (substr($url, -1 * strlen($current_page)) == $current_page) {
                    $url = substr($url, 0, -1 * strlen($current_page));
                }
                if ($col->count(['url' => $url]) == 1) {
                    // we already have this in our db
                    break 2;
                }

                $coptete  = Http::text($page->query('.//p', $story));
                $categoria = Http::text($page->query('.//span[@class="news_category"]', $story));

                /* fecha */
                $publicacion = Http::fecha($page->query('.//span[@class="search_results_time_stamp"]', $story));

                $images = array();
                foreach ($page->query('.//img', $story) as $img) {
                    $images[] = $img->GetAttribute('src');
                }
                $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits', 'copete', 'categoria');
            }
        }

        return $alls;
    }

    public function crawl()
    {
        if ($this->crawled && $this->version == 2) return;

        $page = Http::wget($this->url);
        $content = $page->query('//div[@class="interior_main_column"]')->item(0);

        $categoria = Http::text($page->query('.//*[@class="news_category_and_date"]/a', $content));
        $title     = Http::text($page->query('.//h1', $content));
        $copete    = Http::text($page->query('.//*[@class="copete"]', $content));
        $texto     = Http::text($page->query('.//*[@class="news_story"]', $content));

        $images = [];
        $links  = [];
        $tags   = [];

        foreach ($page->query('.//*[@class="tags"]//a', $content) as $tag) {
            $tags[] = Http::text($tag);
        }

        foreach ($page->query('.//a', $content) as $link) {
            $links[] = [
                $link->getAttribute('href'),
                $link->textContent,
            ];
        }
        foreach ($page->query('.//img', $content) as $img) {
            $images[] = $img->GetAttribute('src');
        }

        $tags_txt = implode("\n", $tags);
        $this->crawled_data = compact('title', 'copete', 'texto', 'links', 'images', 'categoria', 'tags', 'tags_txt');
        $this->version = 2;
        $this->crawled = true;
    }
}
