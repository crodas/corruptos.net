<!DOCTYPE HTML>
<!--
    Verti 2.5 by HTML5 UP
    html5up.net | @n33co
    Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8" />
        @section('seo')
        <title>Botame</title>
        <meta name="description" content="Índice de noticias sobre algunos corruptos del paraguay" />
        @if (!empty($corruptos))
        <meta name="keywords" content="{{ implode(", ", array_map(function($n) { 
            return $n->nombre; 
        }, iterator_to_array($corruptos))) }}" />
        @end
        @show
        <link href="//fonts.googleapis.com/css?family=Open+Sans:300,800" rel="stylesheet" type="text/css" />
        <link href="//fonts.googleapis.com/css?family=Oleo+Script:400" rel="stylesheet" type="text/css" />
        <link type="text/plain" rel="author" href="//botame.org/humans.txt" />
        <script src="/js/jquery.min.js"></script>
        <script src="/js/config.js"></script>
        <script src="/js/skel.min.js"></script>
        <script src="/js/skel-panels.min.js"></script>
        <noscript>
            <link rel="stylesheet" href="/css/skel-noscript.css" />
            <link rel="stylesheet" href="/css/style.css" />
            <link rel="stylesheet" href="/css/style-desktop.css" />
        </noscript>
        <!--[if lte IE 8]><script src="/js/html5shiv.js"></script><link rel="stylesheet" href="/css/ie8.css" /><![endif]-->
        <!--[if lte IE 7]><link rel="stylesheet" href="/css/ie7.css" /><![endif]-->
    </head>
    <body class="homepage">

        <!-- Header Wrapper -->
            <div id="header-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="12u">
                        
                            <!-- Header -->
                                <header id="header">
                                
                                    <!-- Logo -->
                                        <div id="logo">
                                            <h1><a href="/">Botame</a></h1>
                                        </div>
                                    
                                        <nav class="nav" id="nav">
                                            <ul>
                                            @foreach($menu as $link => $item)
                                                <li class="{{ $item[1] ? 'current_page_item': ''}}"><a href="{{$link}}">{{$item[0]}}</a></li>
                                            @end
                                            </ul>
                                        </nav>
                                
                                </header>

                        </div>
                    </div>
                </div>
            </div>
        
        <!-- Banner Wrapper -->
            <div id="banner-wrapper">
                <div class="container">
                    <div class="row">
                        <div class="12u">
                        
                            <!-- Banner -->
                                <div id="banner" class="box">

                                    <div>
                                        <div class="row">
                                            <div class="12u">
                                            @section('header')
                                                <h2>Soy político</h2>
                                        <p>Esto es lo que hice ¿Merezco tu 
                                        <a target="_blank" href="http://lema.rae.es/drae/?val=botar">boto</a>?</p>
                                            @show
                                            </div>
                                                <!---
                                            <div class="5u">
                                                <ul>
                                                    <li><a href="/" class="button big fa fa-arrow-circle-right">Top Corruptos</a></li>
                                                    <li><a href="/" class="button alt big fa fa-question-circle">Como funciona</a></li>
                                                </ul>
                                            </div>
                                                -->
                                        </div>
                                    </div>
                                
                                </div>

                        </div>
                    </div>
                </div>
            </div>
        
            @section('content')
            <div id="features-wrapper">
                <div class="container">
                    <div class="row">
                        @set($i, '0')
                        @foreach ($corruptos as $corrupto)
                        <div class="4u" itemscope itemtype="http://data-vocabulary.org/Person">
                            <section class="box box-feature">
                                @if ($corrupto->getImage()) 
                                <a href="/{{{$corrupto->uri}}}" class="image image-full">
                                    <img itemprop="photo" alt="corrupto" alt="{{{$corrupto->nombre}}}" src="{{{ $corrupto->getImage() }}}" alt="" />
                                </a>
                                @end
                                <div class="inner">
                                    <header>
                                        <h2><a href="/{{$corrupto->uri}}"><span itemprop="name">{{{ $corrupto->nombre }}}</span></a></h2>
                                        <small class="byline">
                                            <span itemprop="role">{{{$corrupto->cargo}}}</span> -
                                            <span itemprop="affiliation">{{{$corrupto->partido}}}</span>
                                            <i class="fa fa-small fa-twitter"  data-text="{{{substr($corrupto->nombre,0, 80)}}}" data-url="https://botame.org/{{$corrupto->uri}}"></i>
                                            <i class="fa fa-small fa-facebook" data-text="{{{substr($corrupto->nombre,0, 80)}}}" data-url="https://botame.org/{{$corrupto->uri}}"></i>
                                        </small>
                                    </header>
                                    <div>
                                    <ul>
                                        <li>Tel: <strong>{{{$corrupto->tel}}}</strong></li>
                                        <li>Email: <a href="mailto:{{{$corrupto->email}}}">{{{$corrupto->email}}}</a></li>
                                    </ul>
                                    </div>
                                </div>
                            </section>
                        </div>
                    
                        @if (++$i % 3 == 0)
                        </div>
                        <div class="row">
                        @end
                        @end
                    </div>

                    @if ($page > 0)
                        <a href="/{{{$base}}}/{{$page-1}}"
                        class="button fa
                        fa-arrow-circle-left">Siguiente anterior</a>
                    @end
                    
                    @if ($has_next)
                        <a href="/{{{$base}}}/{{$page+1}}"
                        class="button fo pull-right fa-arrow-right
                        fa-arrow-circle-right">Siguiente página</a>
                    @end

                </div>
            </div>
            @show

        <!-- Footer Wrapper -->
            <div id="footer-wrapper">
                <footer id="footer" class="container">
                    <div class="row">
                        <div class="12u">
                            <div id="copyright">
                                Idea de <a href="https://twitter.com/crodas">@SanTula</a>, programado por
                                 <a href="https://twitter.com/crodas">crodas</a>. | El conocimiento debe ser libre, sin un amo, por eso esto es libre (<a href="https://github.com/crodas/corruptos.net">código</a> y <a href="/db.tar.xz">datos</a>)  | Design: <a href="http://html5up.net/">HTML5 UP</a>
                            </div>
                        </div>
                    </div>
                </footer>
            </div>

        <script>
          (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
             (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
               m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
                 })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-8237210-3', 'botame.org');
    ga('send', 'pageview');

        $('.fa-facebook').click(function() {
            var me = $(this)
                , text = encodeURIComponent(me.data('text'))
                , link = encodeURIComponent(me.data('url'))
                , url = 'https://www.facebook.com/sharer/sharer.php?u=' + link + '&ptitle=' + text + '&display=popup'
            window.open(url, '', "toolbar=0, status=0, width=650, height=360");
        });
        $('.fa-twitter').click(function() {
            var me = $(this)
                , text = encodeURIComponent(me.data('text'))
                , link = encodeURIComponent(me.data('url'))
                , url = 'https://twitter.com/intent/tweet?hashtags=corruptos&original_referer=' + link +  '&text=' + text  + '&tw_p=tweetbutton&url='+ link +'&via=botame_org';
            window.open(url, '', "toolbar=0, status=0, width=650, height=360");
        });
    </script>
    @section('js')
    @show

    </body>
</html>
