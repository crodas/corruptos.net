<?php

use Seld\JsonLint\JsonParser;

class Crawler
{
    static function post($url, $post)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec ($ch);
        curl_close ($ch);

        die($output);
    }

    static function get($url)
    {
        $json = file_get_contents($url);
        $bom  = "\xEF\xBB\xBF";
        if (substr($json, 0, 3) === $bom) {
            $json = substr($json, 3);
        }
        $parser = new JsonParser();
        return $parser->parse($json);
    }

    static function uh($text)
    {
        $alls = [];
        $url  = "http://www.ultimahora.com/_post/ultimahora/getMoreNewsBySearch.php";
        for ($i=0; ; $i++) {
            $args = [
                'text' => $text,
                'filterType' => '',
                'fechaDesde' => '',
                'fechaHasta' => '',
                'idCMSObjeto' => 47,
                'page' => 10,
                'id' => 212,
                'idCMSSeccion' => 195,
                'offset' => 5,
            ];

            $obj = self::post($url, $args);
        }
    }

    static function nanduti($text)
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
        $xpath = Http::wget($url);
        $alls  = [];
        foreach ($xpath->query('//div[@class="BAcaja"]') as $div) {
            $titulo = Http::text($xpath->query('./div[@class="BAcajaTitu"]', $div));
            $url    = $xpath->query('.//a', $div)->item(0)->getAttribute('href');
            $publicacion = implode("/", array_reverse(explode("/", Http::text($xpath->query('./div[@class="BAcajaFec"]', $div)))));
            $texto  = Http::text($xpath->query('./div[@class="BAcajaTxt"]', $div));

            $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits');
        }

        return $alls;
    }

    static function cardinal($text)
    {
        $alls  = [];
        $max   = 2;
        $meses = [
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
            'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ];

        $month = array_map(function($index) {
            $index++;
            return date('F', strtotime("{$index}/1/2013"));
        }, array_keys($meses));

        $alls = array();
        $hits = 0;
        $comentarios = 0;
        for ($i=0; $i < $max; $i++) {
            $xpath = Http::wget('http://www.cardinal.com.py/buscar.html?' . http_build_query(['busqueda' => $text, 'page'=>$i+1]));
            $max   = (int)Http::text($xpath->query('(//*[@id="resultados-busqueda"]/div[@class="paginacion"]/a)[last()]'));


            foreach ($xpath->query('//*[@id="resultados-busqueda"]//ul//a') as $link) {
                $parent = $link->parentNode->parentNode;
                $titulo = Http::text($link);
                $url    = "http://www.cardinal.com.py" . $link->getAttribute('href');
                $creado = explode(",", Http::text($xpath->query('.//i', $parent)));
                $partes = explode(" ", trim(str_replace($meses, $month, $creado[1])));

                $publicacion = $partes[0] . ' ' . $partes[2] . ' ' . $partes[3]  . ' ' . substr($creado[2], 0, -2);

                $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits');
            }
        }

        return $alls;
    } 

    static function abc($text)
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
                $obj = self::get($url);
                $alls[] = $obj->articulos;
                if (empty($obj->articulos)) {
                    break;
                }
            } catch (\Exception $e) {
                echo (string)$e;
            }
        }

        $alls =  call_user_func_array('array_merge', $alls);
        foreach ($alls as &$noticia) {
            $noticia->url = "http://www.abc.com.py/" . $noticia->url;
        }
    }
}

/**
 *  @Service("search", {
 *      api: {required: true},
 *  }, {shared: true}) 
 */
function search_service(Array $config)
{

    return function($text) use ($config) {
        return array_merge([]
            , Crawler::nanduti($text)
            , Crawler::cardinal($text)
            //, Crawler::abc($text)
        );
    };
}
