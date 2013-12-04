@extends('layout/new')

@section('seo')
    <title>{{{$corrupto->nombre}}} | Botame.org</title>
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
<div class="profile">
    <a href="#" class="avatar pull-left">
        <img src="{{{$corrupto->getImage()}}}" width="100" />
    </a>
    <div class="info pull-left">
        <h2>{{{$corrupto->nombre}}} <small>
        @foreach($corrupto->tags as $tag)
            <a href="/ver/{{{$tag}}}">#{{{$tag}}}</a>
        @end
        </small></h2>
        <h4>{{{$corrupto->total_noticias}}} noticias</h4>
    </div>
    <div class="clearfix"></div>
</div>

<ul class="nav nav-tabs">
    @foreach($newspaper as $uri => $nombre)
        @if (!empty($medio) && $uri == $medio)
            @set($selected, true)
            <li class="active">
        @else
            <li>
        @end
                <a href="/{{$corrupto->uri}}/medio/{{$uri}}">{{$nombre}}</a>
        </li>
    @end
    @if (empty($selected))
    <li class="active">
    @else
    <li>
    @end
    <a href="/{{$corrupto->uri}}">Todos</a>
  </li>
</ul>

<ul class="news-feed unstyled">
    @set($datos, $corrupto->getNoticias($page, $total, $filter))
    @foreach($datos as $dato)
        @include('detalle-noticia', ['noticia' => $dato]);
    @end
</ul>

<div class="pagination">
    <ul class="pagination">
    @foreach(pagination($page, $total) as $p)
        @if ($page+1 == $p || !$p)
            <li><a>{{$p}}</a></li>
        @else
            <li><a href="{{$base}}/{{$p}}">{{$p}}</a></li>
        @end
    @end
    </ul>
</div>

@end


@section('js')
<link href="/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
@end
