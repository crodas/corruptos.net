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
    <li class="news-item">
        <h4 class="title">
            <a target="_blank" href="{{{$dato->url}}}" onmousedown="return go(this, '{{$dato->id}}')" class="text-info">
                {{{$dato->titulo}}}
            </a>
        </h4>
        <div class="post-wrap">
            <a href="http://www.websterfolks.com/demo/reddish/user/2-demo" class="avatar">
                <img src="{{{ $dato->corruptos[array_rand($dato->corruptos)]->getImage() }}}" alt="demo user" class="pull-left clearfix" />
            </a>
            <div class="post" class="pull-left">
                <p class="meta">
                    hace {{ time_ago($dato->creado->sec) }} atrás en                                                
                    <strong>
                        <a target="_blank" href="/fuente/{{{$dato->fuente()}}}" class="text-warning">{{{$dato->fuente()}}}</a>                                                
                    </strong>
                </p> 
                <p class="article">
                    @if (!empty($dato->is_audio))
                        @include('mp3', ['id' => $dato->id, 'noticia' => $dato])
                    @else
                        {{{$dato->texto}}}
                    @end
                </p>
                <p class="comments-count">
                    <a href="/noticia/{{$dato->uri}}" class="text-success">
                        {{$dato->total_comentarios}} comentarios
                    </a>
                    @foreach ($dato->corruptos as $corrupto)
                        <a href="/{{$corrupto->uri}}">#{{{$corrupto->nombre}}}</a>
                    @end
                </p>
            </div> 
        </div>
    </li>
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
