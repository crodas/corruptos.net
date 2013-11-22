<?php

/** 
 *  @Cli("db:index") 
 */
function index($input, $output)
{
    Service::get('db')->ensureIndex();
}

/** 
 *  @Cli("corrupto:actualizar") 
 */
function update($input, $output)
{
    $conn = Service::get('db');
    foreach ($conn->getCollection('corruptos')->Find() as $corrupto) {
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
