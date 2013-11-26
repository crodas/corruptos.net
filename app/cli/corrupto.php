<?php

/** 
 *  @Cli("corrupto:cambiar") 
 *  @Arg("uri", REQUIRED)
 *  @Arg("clave", REQUIRED)
 *  @Arg("valor", REQUIRED)
 */
function do_update($input, $output)
{
    $uri   = $input->getArgument('uri');
    $clave = $input->getArgument('clave');
    $valor = $input->getArgument('valor');

    $corrupto = Service::get('db')->getCollection('corrupto')
        ->findOne(compact('uri'));
    if (!empty($corrupto)) {
        $corrupto->$clave = $valor;
        Service::get('db')->save($corrupto);
        echo "Actualizado\n";
    }
}


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
        $cv      = "http://www.senado.gov.py/" . $xpath->query(".//a[1]", $tr)->item(0)->getAttribute('href');
        $partido = Http::text($xpath->query("./td/div/span", $tr)->item(2));
        $tel     = Http::text($xpath->query("./td/div/span", $tr)->item(3));
        $email   = Http::text($xpath->query("./td/div/span", $tr)->item(4));

        $datas[] = compact('image', 'nombre', 'cv', 'partido', 'tel', 'email');
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
    $conn  = Service::get('db');
    $query = [];
    //$query = ['_id' => new \MongoId('528dceffcc216c884c000120')];
    //$query = ['__type' => 'nanduti'];

    foreach ($conn->getCollection('noticias')->Find($query) as $noticia) {
        if (empty($noticia->corruptos)) {
            /** not important */
            continue;
        }
        $noticia->crawl();
        $tmp = [];
        foreach ($noticia->corruptos as $corrupto) {
            $tmp[(string)$corrupto->id] = $corrupto;
        }
        $noticia->corruptos = array_values($tmp);

        foreach ($noticia->corruptos as $index => $corrupto) {
            if (!$noticia->checkContext($corrupto->nombre) 
                && !$noticia->checkContext($corrupto->apodo)
                && !$noticia->checkContext($corrupto->partido . ' ' . $corrupto->nombre)
                && !$noticia->checkContext($corrupto->cargo   . ' ' . $corrupto->nombre)
            ) {
                echo "{$noticia->id}: {$noticia->url} is not about {$corrupto->nombre}\n";
                //unset($noticia->corruptos[$index]);
            }
        }
        $conn->save($noticia);
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
 *  @Arg("tipo", REQUIRED|IS_ARRAY)
 */
function agregar($input, $output)
{
    $nombre = $input->GetArgument('nombre');

    $corrupto = Corrupto::getOrCreate($nombre);
    foreach ($input->getArgument('tipo') as $tipo) {
        $corrupto->tags[] = $tipo;
    }
    $corrupto->tags = array_unique($corrupto->tags);
    Service::get('db')->save($corrupto);
    $corrupto->update();
}
