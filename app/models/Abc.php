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

    public static function search($text, $force = false)
    {
        $alls = [];
        for ($i=0; ; $i++) {
            $url = "http://www.abc.com.py/ajax.php?" . http_build_query([
                'seccion'   => 'listados',
                'tipo'      => 4, 
                'tipoplant' => 0,
                'id' => $text,
                'begin' => $i*20,
                'limit' => 20,
                'aditional' => ''
            ]);
            try {
                $obj   = Http::wget($url, 3600, true, 'json');
                $break = false;
                foreach ($obj->articulos as &$noticia) {
                    $noticia->url = "http://www.abc.com.py/" . $noticia->url;
                    if (self::exists($noticia->url) && !$force) {
                        $break = true; 
                    }
                }
                $alls[] = $obj->articulos;
                if (empty($obj->articulos) || $break) {
                    break;
                }
            } catch (\Exception $e) {
                dlog($e, "error");
            }
        }

        $alls =  call_user_func_array('array_merge', $alls);

        return $alls;
    }

    public function crawl()
    {
        if ($this->crawled && $this->version == 2) return;

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

        $this->crawled_data = compact('title', 'copete', 'texto', 'links', 'images', 'tags');
        $this->crawled = true;
        $this->version = 2;
    }
}
