<script type="text/javascript">
//<![CDATA[
window.delayed = window.delayed || [];
delayed.push(function() {
    $("#jquery_jplayer_{{$id}}").jPlayer({
        ready: function () {
            $(this).jPlayer("setMedia", {
                mp3:"/play/audio/{{$id}}",
            });
            @if (!empty($autoplay))
            $("#jquery_jplayer_{{$id}}").jPlayer("play");
            @end
        },
        play: function() { // To avoid multiple jPlayers playing together.
            $(this).jPlayer("pauseOthers");
        },
        cssSelectorAncestor: "#jp_container_{{$id}}",
        swfPath: "/js",
        supplied: "mp3",
        wmode: "window",
        smoothPlayBar: true,
        keyEnabled: false,
        preload:'none'
    });
});
//]]>
</script>

<div id="jquery_jplayer_{{$id}}" class="jp-jplayer"></div>
<div id="jp_container_{{$id}}" class="jp-audio">
    <div class="jp-type-single">
        <div class="jp-gui jp-interface">
            <ul class="jp-controls">
                <li><a href="javascript:;" class="jp-play" tabindex="1">play</a></li>
                <li><a href="javascript:;" class="jp-pause" tabindex="1">pause</a></li>
                <li><a href="javascript:;" class="jp-stop" tabindex="1">stop</a></li>
                <li><a href="javascript:;" class="jp-mute" tabindex="1" title="mute">mute</a></li>
                <li><a href="javascript:;" class="jp-unmute" tabindex="1" title="unmute">unmute</a></li>
                <li><a href="javascript:;" class="jp-volume-max" tabindex="1" title="max volume">max volume</a></li>
            </ul>
            <div class="jp-progress">
                <div class="jp-seek-bar">
                    <div class="jp-play-bar"></div>
                </div>
            </div>
            <div class="jp-volume-bar">
                <div class="jp-volume-bar-value"></div>
            </div>
            <div class="jp-time-holder">
                <div class="jp-current-time"></div>
                <div class="jp-duration"></div>

                <ul class="jp-toggles">
                    <li><a href="javascript:;" class="jp-repeat" tabindex="1" title="repeat">repeat</a></li>
                    <li><a href="javascript:;" class="jp-repeat-off" tabindex="1" title="repeat off">repeat off</a></li>
                </ul>
            </div>
        </div>
        <div class="jp-title">
            <ul>
                @if ($noticia instanceof Noticia)
                    <li>Audio de {{$noticia->fuente()}}</li>
                @else
                    <li>Audio de {{$noticia->fuente}}</li>
                @end
            </ul>
        </div>
        <div class="jp-no-solution">
            <span>Update Required</span>
            To play the media you will need to either update your browser to a recent version or update your <a href="http://get.adobe.com/flashplayer/" target="_blank">Flash plugin</a>.
        </div>
    </div>
</div>

