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

    public static function search($text)
    {
        $page = Http::wget('http://www.ultimahora.com/contenidos/resultado.html?text=' . urlencode($text), 600);

        $urls = array();
        foreach ($page->query('//*[@class="result-obj"]//*[@class="cols1 clearfix"]//*[@class="t2"]//a') as $row) {
            $urls[] = ['url' => $row->getAttribute('href')];
        }
        foreach ($page->query('//*[@class="contenido"]//h3//a') as $row) {
            $urls[] = ['url' => $row->getAttribute('href')];
        }

        $url  = "http://www.ultimahora.com/_post/ultimahora/getMoreNewsBySearch.php";
        for ($i=1; ; $i++) {
            $args = [
                'text' => $text,
                'filterType' => '',
                'fechaDesde' => '',
                'fechaHasta' => '',
                'idCMSObjeto' => 47,
                'page' => 10,
                'id' => 212,
                'idCMSSeccion' => 195,
                'offset' => count($urls),
            ];

            $obj = Http::post($url, $args);
            foreach ($obj->query('//h3/a') as $path) {
                $urls[] = ['url' => $path->getAttribute('href')];
            }
            if ($obj->query('//h3/a')->length != 3) {
                break;
            }
        }

        return $urls;
    }

    public function crawl($v = 0)
    {
        if ($this->crawled && $this->version == 2) return;
        $xpath = Http::wget($this->url);

        $title  = Http::text($xpath->query('//h1'));
        if (empty($title)) {
            if ($v == 2) {
                /** givin' up */
                return;
            }
            $xpath = Http::wipe($this->url);
            return $this->crawl($v++);
        }
        $fecha  = Http::fecha($xpath->query('//*[@class="floatright"]'));
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
