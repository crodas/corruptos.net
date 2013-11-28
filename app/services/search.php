<?php

use Symfony\Component\CssSelector\CssSelector as j;

class Crawler
{
    static protected $meses = [
        'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio',
        'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
    ];

    static function post($url, $post)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_VERBOSE, true);

        $output = str_replace("\r", "", curl_exec ($ch) . "\n");
        list($header, $data) = explode("\n\n", $output, 2);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if ($http_code == 301 || $http_code == 302) {
            preg_match('/Location:(.*?)\n/', $header, $matches);
            $url = trim(array_pop($matches));
            if (!filter_var($url, FILTER_VALIDATE_URL)) {
                return false;
            }
            return $url;
        }
        curl_close ($ch);

        return trim($data);
    }

    static function get($url)
    {
        return Http::wget($url, 3600, true, 'json');
    }

    static function hoy($text)
    {
        $meses = array_map(function($name) {
            return strtolower($name);
        }, self::$meses);

        $month = array_map(function($index) {
            $index++;
            return date('F', strtotime("{$index}/1/2013"));
        }, array_keys($meses));

        try {
            sleep(15); // they are pussy
            $q = Http::wget('http://www.hoy.com.py/search_form', 2);
        } catch (\Exception $e) {
            return [];
        }
        $form = array();
        foreach ($q->query('//input') as $input) {
            $form[$input->getAttribute('name')] = $input->getAttribute('value');
        }
        $form['keywords'] = $text;
        $search_url = self::post('http://www.hoy.com.py', $form);
        
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

                $categoria = Http::text($page->query('.//h3//a', $scope));
                $copete    = Http::text($page->query('.//p', $scope));
                
                $fecha = Http::text($page->query('.//h3/span', $scope));
                $fecha = preg_replace('/[^a-z0-9\:]/', ' ', $fecha);
                $fecha = preg_replace('/^[^0-9]+/', '', $fecha);
                $fecha = str_replace($meses, $month, $fecha);
                $fecha = str_replace(' de ', ' ', $fecha);
                $publicacion = $fecha;
                
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

    static function uh($text)
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
            foreach (Http::xpath($obj)->query('//h3/a') as $path) {
                $urls[] = ['url' => $path->getAttribute('href')];
            }
            break;
        }
        return $urls;
    }

    static function paraguay_com($text)
    {
        $meses = array_map(function($name) {
            return substr(ucfirst($name), 0, 3);
        }, self::$meses);

        $month = array_map(function($index) {
            $index++;
            return date('F', strtotime("{$index}/1/2013"));
        }, array_keys($meses));

        $urls = [];
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

                $coptete  = Http::text($page->query('.//p', $story));
                $categoria = Http::text($page->query('.//span[@class="news_category"]', $story));

                /* fecha */
                $fecha = Http::text($page->query('.//span[@class="search_results_time_stamp"]', $story));
                $publicacion = str_replace($meses, $month, $fecha);

                $images = array();
                foreach ($page->query('.//img', $story) as $img) {
                    $images[] = $img->GetAttribute('src');
                }
                $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits', 'copete', 'categoria');
            }
        }

        return $alls;
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
        $xpath = Http::wget($url, 3600);
        $alls  = [];
        foreach ($xpath->query('//div[@class="BAcaja"]') as $div) {
            $titulo = Http::text($xpath->query('./div[@class="BAcajaTitu"]', $div));
            $url    = $xpath->query('.//a', $div)->item(0)->getAttribute('href');
            $publicacion = implode("/", array_reverse(explode("/", Http::text($xpath->query('./div[@class="BAcajaFec"]', $div)))));
            $copete  = Http::text($xpath->query('./div[@class="BAcajaTxt"]', $div));

            $alls[] = (object) compact('titulo', 'url', 'publicacion', 'comentarios', 'hits', 'copete');
        }

        return $alls;
    }

    static function cardinal($text)
    {
        $alls  = [];
        $max   = 2;
        $meses = self::$meses;

        $month = array_map(function($index) {
            $index++;
            return date('F', strtotime("{$index}/1/2013"));
        }, array_keys($meses));

        $alls = array();
        $hits = 0;
        $comentarios = 0;
        for ($i=0; $i < $max; $i++) {
            $xpath = Http::wget('http://www.cardinal.com.py/buscar.html?' . http_build_query(['busqueda' => $text, 'page'=>$i+1]), 3600);
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

        return $alls;
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
            /**/
            , Crawler::uh($text) /*
            , Crawler::hoy($text) 
            , Crawler::paraguay_com($text) 
            , Crawler::nanduti($text)
            , Crawler::cardinal($text)
            , Crawler::abc($text)
            /**/
        );
    };
}
