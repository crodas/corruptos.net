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

    public function crawl()
    {
        //if ($this->crawled) return;

        echo "Crawling {$this->url}\n";

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

