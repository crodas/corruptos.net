@extends('layout')

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
                        <p><small>{{{$corrupto->summary}}}</small></p>
                    </section>
                </div>
        
        </div>
        <div class="8u">

            <!-- Content -->
                <div id="content">
                    <h2>Noticias</h2>
                    @foreach($corrupto->getNoticias($page, $has_more) as $noticia)
                    <section class="last">
                        <h3>{{{$noticia->titulo}}}</h3>
                        <p>{{{$noticia->texto}}}</p>
                        <a href="{{{$noticia->url}}}"
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
