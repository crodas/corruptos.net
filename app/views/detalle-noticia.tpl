    <li class="news-item">
        <h4 class="title">
            <a target="_blank" href="{{{$noticia->url}}}" onmousedown="return go(this, '{{$noticia->id}}')" class="text-info">
                {{{$noticia->titulo}}}
            </a>
        </h4>
        <div class="post-wrap">
        <!-- votos -->
        <div class="news-shakeit mnm-published pull-left clearfix">
            <div class="votes">
                <a id="a-votes-{{$noticia->id}}" href="/noticia/{{$noticia->uri}}" style="display: block;">
                    78
                </a> 
                botos
            </div>  
            <div class="menealo" id="a-va-{{$noticia->id}}">
                <span>¡botado!</span>
            </div>  
            <div class="clics">  650 clics  </div> 
        </div>
        <!-- /votos -->

            <div class="post" class="pull-left">
                <p class="meta">
                    @if (is_object($noticia->creado))
                        hace {{ time_ago($noticia->creado->sec) }} atrás en                                                
                    @else
                        hace {{ time_ago(strtotime($noticia->creado)) }} atrás en                                                
                    @end
                    <strong>
                        <a href="/fuente/{{{$noticia->fuente}}}" class="text-warning">{{{$noticia->fuente}}}</a>
                    </strong>
                </p> 
                <p class="article">
                    @if (!empty($noticia->is_audio))
                        @include('mp3', ['id' => $noticia->id, 'noticia' => $noticia])
                    @else
                        {{{$noticia->texto}}}
                    @end
                </p>
                <p class="comments-count">
                    <a href="/noticia/{{$noticia->uri}}" class="text-success">
                        {{$noticia->total_comentarios}} comentarios
                    </a>
                    @foreach ($noticia->corruptos as $corrupto)
                        @set($corrupto, (object)$corrupto)
                        <a href="/{{$corrupto->uri}}">#{{{$corrupto->nombre}}}</a>
                    @end
                </p>
            </div> 
        </div>
    </li>
