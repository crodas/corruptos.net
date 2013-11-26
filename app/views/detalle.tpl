@extends('layout')

        @section('seo')
        <title>{{{$noticia->titulo}}} | Políticos del  Paraguay</title>
        <meta name="description" content="{{{mb_substr($noticia->texto, 0,200)}}}" />
        <meta name="keywords" content="paraguay, {{{$corrupto->nombre}}}" />
        @show

@section('header')
    <h2>{{{$corrupto->nombre}}}</h2>
    <p></p>
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
