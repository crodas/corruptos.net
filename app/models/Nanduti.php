<?php

/**
 *  @Persist
 */
class Nanduti extends Noticia
{
    /** @Bool */
    public $is_audio = true;

    /** @Bool */
    public $has_text = true;

    public static function is($url)
    {
        return preg_match('/nanduti\.com/', $url);
    }

    public static function search($text, $force = false)
    {
        $text = iconv('UTF-8','ASCII//TRANSLIT',$text);
        $url  = 'http://www.nanduti.com.py/v1/buscador_avanzado.php?' . http_build_query([
            'buscar' => $text, 
            'noti' => 'si',
            'audi' => 'si',
            'quin' => 'si',
            'secciones' => '', 
            'fech1'=> '1979-11-23', 
            'fech2'=> '2099-11-23',
            'paginas'=> 10000000000000, 
            'button' => 'Empezar busqueda',
        ]);

        $hits = 0;
        $comentarios = 0;
        $xpath = Http::wget($url, 3600);
        $alls  = [];
        foreach ($xpath->query('//div[@class="BAcaja"]') as $div) {
            $titulo = Http::text($xpath->query('./div[@class="BAcajaTitu"]', $div));
            $url    = $xpath->query('.//a', $div)->item(0)->getAttribute('href');
            $publicacion = implode("/", array_reverse(explode("/", Http::text($xpath->query('./div[@class="BAcajaFec"]', $div)))));
            $copete  = Http::text($xpath->query('./div[@class="BAcajaTxt"]', $div));

            if (!parse_url($url, PHP_URL_HOST)) {
                $url = "http://nanduti.com.py/v1/{$url}";
            }
            if (self::exists($url) && !$force) {
                continue;
            }
            

            $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits', 'copete');
        }

        return $alls;
    }

    public function crawl()
    {
        if ($this->crawled && $this->version == 2) return;

        $xpath = Http::wget($this->url);

        /** audio info */
        $title = Http::text($xpath->query('//*[@class="UMTitulo"]//center'));
        $texto = $title;
        if (empty($title)) {
            $title = Http::text($xpath->query('//*[@class="UMTitulo"]'));
            $texto = Http::text($xpath->query('//*[@class="UMmed_1"]/p | //*[@class="UMmed_1"]//span'));
        }
        $mp3   =  $xpath->query('//embed')->item(0);

        $this->is_audio = false;
        if ($mp3) {
            parse_str(parse_url($mp3->getAttribute('src'), PHP_URL_QUERY), $data);
            if (!empty($data['mp3'])) {
                $mp3 = $data['mp3'];
                $this->is_audio = true;
            }
        }

        $this->titulo = $title;
        $this->texto  = $texto;

        $this->crawled_data = compact('title', 'texto', 'mp3');
        $this->crawled = true;
        $this->version = 2;
    }

    public function render()
    {
        return substr($this->texto, 0, 500) . '...';
    }

}

