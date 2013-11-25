
<h3><a href="/go/{{$noticia->id}}" target="_blank">{{{$noticia->titulo}}}</a></h3>
<small>{{date("Y/m/d H:i:s", $noticia->creado->sec)}}</small>
    <div>
        <i class="button small fa fa-2x fa-twitter"  data-text="{{{substr($noticia->titulo,0, 80)}}}..." data-url="https://corruptos.net/noticia/{{$noticia->uri}}"></i>
        <i class="button small fa fa-2x fa-facebook" data-text="{{{substr($noticia->titulo,0, 80)}}}..." data-url="https://corruptos.net/noticia/{{$noticia->uri}}"></i>
    </div>
@if (empty($noticia->is_audio)) {
    <p>{{{ $noticia->render() }}}</p>
@else
    @include("mp3", ['id' => $noticia->id, 'noticia' => $noticia])
    @if (!empty($noticia->has_text))
        <p>{{{ $noticia->render() }}}</p>
    @end
@end

