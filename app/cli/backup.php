<?php

/** 
 *  @Cli("db:restore") 
 */
function restore($input, $output)
{
    $target = __DIR__ . '/../../';
    $uniqid = uniqid(True) . ".tar.xz";
    `wget -O {$uniqid} http://corruptos.net/db.tar.xz`;
    `tar xfv $uniqid`;
    `mongorestore --drop dump`;
    `rm -rf dump`;
    unlink($uniqid);
}

/** 
 *  @Cli("db:backup") 
 */
function backup($input, $output)
{
    $target = __DIR__ . '/../../public_html';
    `cd /tmp; mongodump -d corruptos_net; tar cfv db.tar dump; xz db.tar ; mv db.tar.xz $target`;
}
