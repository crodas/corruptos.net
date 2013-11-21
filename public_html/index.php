<?php

require __DIR__ . '/../app/boot.php';

Service::get('dispatcher')
    ->doRoute();
