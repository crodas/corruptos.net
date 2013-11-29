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
<hr />

<ul class="news-feed unstyled">
    @set($datos, $corrupto->getNoticias($page, $has_next, $filter))
    @foreach($datos as $dato)
    <li class="news-item">
        <h4 class="title">
            <a target="_blank" href="/go/{{{$dato->id}}}" class="text-info">
                {{{$dato->titulo}}}
            </a>
        </h4>
        <div class="post-wrap">
            <a href="http://www.websterfolks.com/demo/reddish/user/2-demo" class="avatar">
                <img src="http://www.gravatar.com/avatar/7c4ff521986b4ff8d29440beec01972d?" alt="demo user" class="pull-left clearfix" />
            </a>
            <div class="post" class="pull-left">
                <p class="meta">
                    hace {{ time_ago($dato->creado->sec) }} atrás en                                                
                    <strong>
                        <a target="_blank" href="/go/{{{$dato->fuente()}}}" class="text-warning">{{{$dato->fuente()}}}</a>                                                
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
                </p>
            </div> 
        </div>
    </li>
    @end
</ul>
@end

@section('js')
<link href="/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
@end

