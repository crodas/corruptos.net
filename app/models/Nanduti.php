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

    public static function search($text)
    {
        $url = 'http://www.nanduti.com.py/v1/buscador_avanzado.php?' . http_build_query([
            'buscar' => $text, 
            'noti' => 'si',
            'secciones' => '', 
            'fech1'=> '1999-11-23', 
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

            if (self::exists($url)) {
                break;
            }
            

            $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits', 'copete');
        }

        return $alls;
    }

    public function crawl()
    {
        if ($this->crawled) return;

        $xpath = Http::wget($this->url);

        $title = Http::text($xpath->query('//*[@class="UMTitulo"]'));
        $texto = Http::text($xpath->query('//*[@class="UMmed_1"]/p | //*[@class="UMmed_1"]//span'));
        $mp3   =  $xpath->query('//*[@class="UMmed_1"]//embed')->item(0);

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
    }

    public function render()
    {
        return substr($this->texto, 0, 500) . '...';
    }

}

