<?php
error_reporting(E_ALL);
ini_set('display_error', 'on');

require __DIR__ . "/app/boot.php";

$cli = new crodas\cli\Cli(__DIR__ . '/tmp/cli.php.tmp');
$cli->addDirectory(__DIR__ . '/vendor');
$cli->addDirectory(__DIR__ . '/app/cli');
$cli->main();
