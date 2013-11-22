@extends('layout')

@section('header')
    <h2>Aquí los corruptos no son bienvenidos</h2>
	<p>
        Mapa de locales adheridos a los escraches a los corruptos. Mapa mantenido por <a href="https://twitter.com/cesanz">@cesanz</a>
    </p>
@end

@section('content')
    <iframe src="https://mapsengine.google.com/map/u/0/embed?mid=zyi2EjHjL4xk.knPTWWCWJG9c" width="640" height="480"></iframe>
    <script>
    $(function() {
        var relation = 480/640;
        function resize() {
            var w = $(window).width();
            $('iframe').attr({width: w, height: w*relation});
        }
        resize();
        $(window).resize(resize);
    });
    </script>
@end

