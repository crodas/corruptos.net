@extends('layout/new')

@section('seo')
    <title>Botame.org</title>
    <meta name="description" content="Botame.org" />
    <meta name="keywords" content="paraguay, botame" />
@end


@section('content')
    <ul class="news-feed unstyled">
    @foreach($corruptos as $corrupto)
    <li class="news-item">
        <h4 class="title">
            <a href="/{{{$corrupto->uri}}}" class="text-info">
                {{{$corrupto->cargo}}}
            </a>
        </h4>
        <div class="post-wrap">
            <img src="{{{ $corrupto->getImage() }}}" alt="demo user" class="pull-left clearfix" width="100" />
        </div>
        <div class="post" class="pull-left">
          <div class="profile">
              <div class="info pull-left">
                <a href="/{{{$corrupto->uri}}}" class="text-info">
                      <h2>{{{$corrupto->nombre}}} <small>
                </a>
                  @foreach($corrupto->tags as $tag)
                    @if  ($tag !== $corrupto->cargo)
                        <a href="/ver/{{{$tag}}}">#{{{$tag}}}</a>
                    @end
                  @end
                  </small></h2>
                  <h4>{{intval($corrupto->total_noticias)}} noticias</h4>
              </div>
              <div class="clearfix"></div>
          </div>
        </div>
    </li>
    @end
    </ul>

<div class="pagination">
    <ul class="pagination">
    @foreach(pagination($page, $total) as $p)
        @if ($page == $p || !$p)
            <li><a>{{$p}}</a></li>
        @else
            <li><a href="{{$base}}/{{$p}}">{{$p}}</a></li>
        @end
    @end
    </ul>
</div>
@end
