
<h3><a href="/go/{{$noticia->id}}" target="_blank">{{{$noticia->titulo}}}</a></h3>
<small>{{date("Y/m/d H:i:s", $noticia->creado->sec)}}</small>
@if (!empty($args['is_mobile']))
    <div>
        <a href="https://twitter.com/share" class="twitter-share-button" data-size="large" data-url="http://corruptos.net/noticia/{{$noticia->uri}}" data-text="{{{substr($noticia->titulo,0, 80)}}}..." data-via="corruptos_net" data-hashtags="corruptos" data-lang="es">Tweet</a>
    </div>
@end
@if (empty($noticia->is_audio)) {
    <p>{{{ $noticia->render() }}}</p>
@else
    @include("mp3", ['id' => $noticia->id, 'noticia' => $noticia])
    @if (!empty($noticia->has_text))
        <p>{{{ $noticia->render() }}}</p>
    @end
@end

<a href="/go/{{{$noticia->id}}}" target="_blank" class="button fa ">Leer más</a>
