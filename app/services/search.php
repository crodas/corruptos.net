<?php

use Symfony\Component\CssSelector\CssSelector as j;

class Crawler
{
    static function hoy($text)
    {
        return Hoy::Search($text);
    }

    static function uh($text)
    {
        return Ultimahora::Search($text);
    }

    static function paraguay_com($text)
    {
        return Paraguay::search($text);
    }

    static function nanduti($text)
    {
        return Nanduti::search($text);
    }

    static function cardinal($text)
    {
        return Cardinal::search($text);
    } 

    static function abc($text)
    {
        return Abc::search($text);
    }
}

/**
 *  @Service("search", {
 *  }, {shared: true}) 
 */
function search_service(Array $config)
{
    return function($text) use ($config) {
        return array_merge([]
            /**/
            , Crawler::nanduti($text)
            , Crawler::hoy($text) 
            , Crawler::uh($text) 
            , Crawler::cardinal($text)
            , Crawler::paraguay_com($text) 
            , Crawler::abc($text)
            /**/
        );
    };
}
