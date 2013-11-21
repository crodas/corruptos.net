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

        return call_user_func_array('array_merge', $alls);
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
        return Crawler::abc($text);
    };
}
