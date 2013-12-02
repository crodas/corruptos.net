@extends('layout')

@section('seo')
    <title>{{{$corrupto->nombre}}} | Botame</title>
    <meta name="description" content="{{{mb_substr($corrupto->summary, 0, 200)}}}" />
    <meta name="keywords" content="paraguay, botame, {{{$corrupto->nombre}}}" />
@end

@section('header')
<div itemscope itemtype="http://data-vocabulary.org/Person">
    <small class="byline">
        <span itemprop="role">{{{$corrupto->cargo}}}</span> -
        <span itemprop="affiliation">{{{$corrupto->partido}}}</span>
    </small>
    <h2 itemprop="name">{{{$corrupto->nombre}}}</h2>
    <p>Esto es lo que hice ¿Merezco tu 
        <a target="_blank" href="http://lema.rae.es/drae/?val=botar">boto</a>?</p>
</div>
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
                            @foreach($newspaper as $uri => $name)
                            <a class="button small"  href="/{{$corrupto->uri}}/medio/{{$uri}}">{{$name}}</a>
                            @end
                        <p><small>{{{$corrupto->summary}}}</small></p>
                    </section>
                </div>
        
        </div>
        <div class="8u">

            <!-- Content -->
                <div id="content">
                    @set($datos, $corrupto->getNoticias($page, $has_next, $filter))
                    @if ($datos->count() > 0)
                        <h2>Noticias</h2>
                    @else
                        <h2>Todavia no hay nada</h2>
                    @end
                    @foreach($datos as $noticia)
                    <section class="last">
                        @include('detalle-noticia',compact('noticia'))
                    </section>
                    @end

                    @if ($page > 0)
                        <a href="{{{$base}}}/{{$page-1}}"
                        class="button fa
                        fa-arrow-circle-left">Siguiente anterior</a>
                    @end
                    
                    @if ($has_next)
                        <a href="{{{$base}}}/{{$page+1}}"
                        class="button fo pull-right fa-arrow-right
                        fa-arrow-circle-right">Siguiente página</a>
                    @end

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
