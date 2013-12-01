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

    public static function search($text)
    {
        try {
            $q = Http::wget('http://www.hoy.com.py/search_form', 2);
            $form = array();
            foreach ($q->query('//input') as $input) {
                $form[$input->getAttribute('name')] = $input->getAttribute('value');
            }
            $form['keywords'] = $text;
            sleep(15); // they are pussy
            $search_url = Http::post('http://www.hoy.com.py', $form);
            if (!is_string($search_url)) {
                return [];
            }
        } catch (\Exception $e) {
            return [];
        }
        
        $zoffset = 0;
        $results = [];
        $hits    = 0;
        $comentarios = 0;
        do {
            if ($zoffset > 0) {
                $page = Http::wget($search_url . 'P' . $zoffset, 3600);
            } else {
                $page = Http::wget($search_url, 3600);
            }
            $found = 0;
            foreach ($page->query('//*[@class="main_content"]//*[@class="article"]') as $scope) {
                $titulo = Http::text($page->query('.//h2', $scope));
                $url    = $page->query('.//h2/a', $scope)->item(0)->getAttribute('href');

                if (self::exists($url)) {
                    break 2;
                }

                $categoria = Http::text($page->query('.//h3//a', $scope));
                $copete    = Http::text($page->query('.//p', $scope));
                
                $publicacion = Http::fecha($page->query('.//h3/span', $scope));
                
                $results[] = (object)compact('titulo', 'url', 'categoria', 'copete', 'hits', 'comentarios', 'publicacion');
                $found++;
            }
            if ($found < 5) {
                break;
            }
            $zoffset += $found;
        } while (true);
        
        return $results;
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
