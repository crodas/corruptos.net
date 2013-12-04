@extends('layout/new')

@section('seo')
    <title>Search | {{{$text}}}</title>
    <meta name="description" content="search results for {{{$text}}}" />
    <meta name="keywords" content="paraguay,search,botame" />
@end

@section('content')
<ul class="news-feed unstyled">
    @foreach ($results as $noticia)
        @include('detalle-noticia', ['noticia' => $noticia]);
    @end
</ul>
@end

@section('js')
<link href="/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
@end
