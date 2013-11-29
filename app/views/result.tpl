@extends('layout')

@section('seo')
    <title>Search | {{{$text}}}</title>
    <meta name="description" content="search results for {{{$text}}}" />
    <meta name="keywords" content="paraguay,search,botame" />
@end

@section('header')
    {{$form->open('/busqueda', 'GET')}}
        {{$form->text('q')}}
        <input type="submit" value="Buscar" class="fa" />
    {{$form->close()}}

    La busqueda tomó {{ $results->getTotalTime() }}ms y encontró {{ $results->getTotalHits() }} noticias.
@end

@section('content')

<div id="main-wrapper">
<div class="container">
    <div class="row">
        <div id="content">
            @set($hash_tag, true)
            @foreach($results as $noticia)
            <section class="last">
                @include('detalle-noticia',compact('noticia', 'hash_tag'))
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
