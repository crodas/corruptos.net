@extends('layout')

@section('header')
    <h2>{{{$corrupto->nombre}}}</h2>
    <nav class="nav">
        <ul>
        <li class="current_page_item"><a href="/audio/{{$corrupto->uri}}">Audio</a></li>
        <li class="current_page_item"><a href="/{{$corrupto->uri}}">Noticias</a></li>
        </ul>
    </nav>
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
                            <img alt="corrupto {{{$corrupto->nombre}}}" src="{{{ $corrupto->avatar }}}" alt="" />
                        </div>
                        <p><small>{{{$corrupto->summary}}}</small></p>
                    </section>
                </div>
        
        </div>
        <div class="8u">
            <div id="content">
                <h2>Noticias</h2>
                @include('detalle-noticia',compact('noticia'))
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
