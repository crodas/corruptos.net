@extends('layout')

        @section('seo')
        <title>{{{$noticia->titulo}}} | Políticos del  Paraguay</title>
        @if ($noticia->fuente() == 'Cardinal')
        <meta name="description" content="{{{$noticia->titulo}}}" />
        @else
        <meta name="description" content="{{{mb_substr($noticia->texto, 0,200)}}}" />
        @end
        <meta name="keywords" content="paraguay, {{{$corrupto->nombre}}}" />
            @if (!empty($noticia->is_audio))
            <meta name="twitter:card" content="player">
            <meta name="twitter:site" content="@botame_org">
            <meta name="twitter:title" content="{{{ $noticia->titulo }}}">
            @if ($noticia->fuente() == 'Cardinal')
            <meta name="twitter:creator" content="@cardinalam">
            <meta name="twitter:description" content="{{{ $noticia->titulo }}}">
            @else
            <meta name="twitter:description" content="{{{ mb_substr($noticia->texto, 0, 200) }}}">
            @end
            <meta name="twitter:image:src" content="https://botame.org/{{$corrupto->getImage()}}">
            <meta name="twitter:player" content="https://botame.org/noticia/{{$noticia->uri}}/twitter">
            <meta name="twitter:player:stream" content="https://botame.org/play/audio/{{$noticia->id}}">
            <meta name="twitter:player:stream:content_type" content="audio/mp3">
            <meta name="twitter:player:height" content="114">
            <meta name="twitter:player:width" content="418">
            <meta name="twitter:domain" content="botame.org">
            @end
        @show

@section('header')
    <h2>{{{$corrupto->nombre}}}</h2>
    <p>Esto es lo que hice ¿Merezco tu 
        <a target="_blank" href="http://lema.rae.es/drae/?val=botar">boto</a>?</p>
@end

@section('content')

<div id="main-wrapper">
<div class="container">
    <div class="row">
        <div class="4u">
        
            <!-- Sidebar -->
                <div id="sidebar">
                    <section class="widget-thumbnails">
                        <h2>Resumen</h2>
	                    <div class="wide">
                            <img alt="corrupto {{{$corrupto->nombre}}}" src="{{{ $corrupto->getImage(false) }}}" alt="" />
                        </div>
                        <p><small>{{{$corrupto->summary}}}</small></p>
                    </section>
                </div>
        
        </div>
        <div class="8u">
            <div id="content">
                <h2>Noticias</h2>
                @include('detalle-noticia',compact('noticia', 'autoplay'))
            </div>
        </div>
    </div>
</div>
</div>
@end


@section('js')
<link href="/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
@end
