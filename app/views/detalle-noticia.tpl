
<h3><a href="/go/{{$noticia->id}}" target="_blank">{{{$noticia->titulo}}}</a></h3>
    <small>
    @if ($noticia->creado instanceof \MongoDate)
        {{date("Y/m/d H:i:s", $noticia->creado->sec)}}
    @else
        {{date("Y/m/d H:i:s", strtotime($noticia->creado))}}
    @end
    @if (!empty($hash_tag))
        @foreach ($noticia->corruptos as $corrupto)
            <a href="/{{$corrupto['uri']}}">#{{$corrupto['nombre']}}</a>
        @end
    @end
    </small>
    <div>
        <i class="button small fa fa-2x fa-twitter"  data-text="{{{substr($noticia->titulo,0, 80)}}}..." data-url="https://botame.org/noticia/{{$noticia->uri}}"></i>
        <i class="button small fa fa-2x fa-facebook" data-text="{{{substr($noticia->titulo,0, 80)}}}..." data-url="https://botame.org/noticia/{{$noticia->uri}}"></i>
    </div>
@if (empty($noticia->is_audio)) {
    @if ($noticia instanceof Noticia)
        <p>{{{ $noticia->render() }}}</p>
    @else
        <p>{{{ $noticia->texto }}}</p>
    @end
@else
    @include("mp3", ['id' => $noticia->id, 'noticia' => $noticia])
    @if (!empty($noticia->has_text))
        <p>{{{ $noticia->render() }}}</p>
    @end
@end

