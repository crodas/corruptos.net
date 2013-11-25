<?php

/** 
 *  @NotFound 
 *  @View not-found
 */
function not_found()
{
    header('HTTP/1.0 404 Not Found');
    return [];
}
