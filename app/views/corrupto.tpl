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

            <!-- Content -->
                <div id="content">
                    <h2>Noticias</h2>
                    @foreach($corrupto->getNoticias($page, $has_more, $filter) as $noticia)
                    <section class="last">
                        <h3><a href="/go/{{$noticia->id}}" target="_blank">{{{$noticia->titulo}}}</a></h3>
                        <small>{{date("Y/m/d H:i:s", $noticia->creado->sec)}}</small>
                        @if (empty($noticia->is_audio)) {
                            <p>{{{ $noticia->render() }}}</p>
                        @else
                            {{ $noticia->render() }}
                        @end
                        <a href="/go/{{{$noticia->id}}}"
                        target="_blank"
                        class="button fa ">Leer más</a>
                    </section>
                    @end

                    @if ($page > 0)
                        <a href="/{{{$corrupto->uri}}}/{{$page-1}}"
                        class="button fa
                        fa-arrow-circle-left">Siguiente anterior</a>
                    @end
                    
                    @if ($has_more)
                        <a href="/{{{$corrupto->uri}}}/{{$page+1}}"
                        class="button fo pull-right fa-arrow-right
                        fa-arrow-circle-right">Siguiente página</a>
                    @end

                </div>

        </div>

    </div>
</div>
</div>

@end
