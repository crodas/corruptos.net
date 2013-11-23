<?php

/** 
 *  @Cli("db:index") 
 */
function index($input, $output)
{
    Service::get('db')->ensureIndex();
}

/** 
 *  @Cli("corrupto:avatar") 
 *  @Arg("uri", REQUIRED)
 */
function select_frontimage_one($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find(['uri' => $input->GetArgument('uri')]) as $corrupto) {
        echo "{$corrupto->nombre}\n";
        $candidates = [];
        foreach ($conn->getCollection('noticias')->find(['corruptos.uri' => $corrupto->uri]) as $noticia) {
            if (!empty($noticia->crawled_data['images'])) {
                $candidates = array_merge($candidates, $corrupto->selectImage($noticia->crawled_data['images']));
            }
        }
        shuffle($candidates);
        $corrupto->avatar = current($candidates);
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:profile") 
 */
function uupdate_profiel($input, $output)
{
    $conn  = Service::get('db');
    $col   = $conn->getCollection('corruptos');
    $xpath = Http::wget('http://www.senado.gov.py/nomina');
    foreach ($xpath->query('//div[@class="34ewe"]') as $senador) {
        $tr = $senador->parentNode->parentNode;

        $image   = $xpath->query(".//img[1]", $tr)->item(0)->getAttribute('src');
        $nombre  = Http::text($xpath->query(".//a[1]", $tr)->item(0));
        $profile = "http://www.senado.gov.py/" . $xpath->query(".//a[1]", $tr)->item(0)->getAttribute('href');
        $partido = Http::text($xpath->query("./td/div/span", $tr)->item(2));
        $tel     = Http::text($xpath->query("./td/div/span", $tr)->item(3));
        $email   = Http::text($xpath->query("./td/div/span", $tr)->item(4));

        $datas[] = compact('image', 'nombre', 'profile', 'partido', 'tel', 'email');
    }

    foreach ($datas as $data) {
        list($apellido, $nombre) = explode(",", $data['nombre']);

        // typos?
        $nombre   = str_replace("Oscar", "Óscar", $nombre);
        $apellido = str_replace("Bachetta", "Bacchetta", $apellido);
        $apellido = str_replace("Quiñonez", "Quiñónez", $apellido);

        $nombre   = explode(" ", trim($nombre));
        $apellido = explode(" ", trim($apellido));

        $cursor = $col->find(['nombre' => new MongoRegex(
            '/' . preg_replace('/[^a-z]{2}/i', '.+', $nombre[0] . '.+'. $apellido[0]) . '/i'
        )]);

        $corrupto = null;
        switch ($cursor->count()) {
        case 1:
            $corrupto = $cursor->getNext();
            break;
        case 0:
            echo '/' . preg_replace('/[^a-z]{2}/i', '.+', $nombre[0] . '.+'. $apellido[0]) . '/i' . "\n";

        }

        if (empty($corrupto)) continue;

        foreach ($data as $type => $value) {
            if ($type != 'nombre') {
                $corrupto->$type = $value;
            }
        }
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:avatar:all") 
 */
function select_frontimage($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find() as $corrupto) {
        echo "{$corrupto->nombre}\n";
        $candidates = [];
        foreach ($conn->getCollection('noticias')->find(['corruptos.uri' => $corrupto->uri]) as $noticia) {
            if (!empty($noticia->crawled_data['images'])) {
                $candidates = array_merge($candidates, $corrupto->selectImage($noticia->crawled_data['images']));
            }
        }
        shuffle($candidates);
        $corrupto->avatar = current($candidates);
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:clean-up") 
 */
function cleaup_things($input, $output)
{
    $noticia = Noticia::getOrCreate("http://www.cardinal.com.py/noticias/senadora_blanca_fonseca_mi_nica_torpeza_fue_no_medir_la_reaccin_de_la_ciudadana_23326.html");
    $noticia->crawl();
    var_dump($noticia);exit;

    $conn = Service::get('db');
    foreach ($conn->getCollection('noticias')->Find() as $noticia) {
        if (!Noticia::is_useful($noticia->url)) {
            $conn->delete($noticia);
        }
    }
}


/** 
 *  @Cli("corrupto:actualizar") 
 */
function update($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find() as $corrupto) {
        echo "Actualizando {$corrupto->nombre}\n";
        $corrupto->update();
        $conn->save($corrupto);
    }
}

/** 
 *  @Cli("corrupto:agregar") 
 *  @Arg("nombre", REQUIRED)
 */
function agregar($input, $output)
{
    $nombre = $input->GetArgument('nombre');

    if ($nombre == "CARLOS NÚÑEZ AGÜERO") {
        // seems odd but abc's search is a bit silly
        $nombre = "SENADOR CARLOS NÚÑEZ";
    }

    $corrupto = Corrupto::getOrCreate($nombre);
    $corrupto->update();
}
