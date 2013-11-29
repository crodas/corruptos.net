<html>
    <head>
        <link href="/skin/blue.monday/jplayer.blue.monday.css" rel="stylesheet" type="text/css" />
        <script src="/js/jquery.min.js"></script>
        <style>
            body {
                margin: 0px;
            }
        </style>
    </head>
    <body>
        @include("mp3", ['id' => $noticia->id, 'noticia' => $noticia, 'autoplay' => true])
        <script type="text/javascript" src="/js/jquery.jplayer.min.js"></script>
    </body>
</html>
