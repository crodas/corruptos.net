<?php

/** 
 *  @Cli("db:backup") 
 */
function index($input, $output)
{
    $target = __DIR__ . '/../../public_html';
    `cd /tmp; mongodump -d corruptos_net; tar cfv db.tar dump; xz db.tar ; mv db.tar.xz $target`;
}
