<?php

/** @Cli(diputados) */
function diputados()
{
    $q = Http::wget('http://www.diputados.gov.py/ww2/?pagina=dip-listado');
    foreach ($q->query('//td/img') as $r) {
        $img = 'http://www.diputados.gov.py/' . substr($r->getAttribute('src'), 3);
        if (!preg_match('/fotos/', $img)) continue;

        $parent  = $r->parentNode->parentNode;
        $profile = $q->query('.//td', $parent);
        $nombre  = array_map(function($name) {
            $name = explode(" ", trim($name));
            return $name;
        }, explode(",", Http::text($profile->item(1))));
        $partido = Http::text($profile->item(2));
        $email   = iconv('UTF-8','ASCII//TRANSLIT',Http::text($profile->item(3)));
        $nombre  = $nombre[1][0] . " " . implode(" ", $nombre[0]);
        $url     = 'http://www.diputados.gov.py/ww2/' . $q->query('.//a', $parent)->item(0)->getAttribute('href');
        $telefono = "";
        $depto    = "";

        $profile = Http::wget($url)->query('.//tr/td[@align="left"]');

        foreach ($profile as $id => $td) {
            if (preg_match('/departamento:/i', trim(Http::text($td)))) {
                $depto = trim(Http::text($profile->item($id+1)), "\240 \t\r\n\302");
            }
            if (preg_match('/tel.*fono:/i', trim(Http::text($td)))) {
                $telefono = preg_replace('/[^0-9\-]/', '', Http::text($profile->item($id+1)));
            }
        }

        $corrupto = Corrupto::getOrCreate($nombre);
        $corrupto->cargo = 'diputado';
        $corrupto->image  = $img;
        $corrupto->avatar = $img;
        $corrupto->email = $email;
        $corrupto->tel   = $telefono;
        $corrupto->partido = $partido;
        $corrupto->tags    = ['diputado', 'diputado:' . $depto];
        try {
            service::get('db')->save($corrupto);
        } catch (\Exception $e) {
            var_dump([$email, $nombre]);exit;
        }
        $corrupto->update(true);
    }
}
