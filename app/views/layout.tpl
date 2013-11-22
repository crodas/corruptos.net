<!DOCTYPE HTML>
<!--
	Verti 2.5 by HTML5 UP
	html5up.net | @n33co
	Free for personal and commercial use under the CCA 3.0 license (html5up.net/license)
-->
<html>
	<head>
		<title>Corruptos de Paraguay</title>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<link href="http://fonts.googleapis.com/css?family=Open+Sans:300,800" rel="stylesheet" type="text/css" />
		<link href="http://fonts.googleapis.com/css?family=Oleo+Script:400" rel="stylesheet" type="text/css" />
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
											<h1><a href="/" id="logo">Corruptos</a></h1>
											<span>Del Paraguay</span>
										</div>
									
										<nav id="nav">
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
												<h2>Los corruptos</h2>
												<p>Bienvenidos al ranking de corruptos</p>
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
						<div class="4u">
								<section class="box box-feature">
                                    @if ($picture = $corrupto->getImage())
									<a href="#" class="image image-full">
                 <!-- <img src="{{{ $picture }}}" alt="" /> -->
                                    </a>
                                    @end
									<div class="inner">
										<header>
											<h2><a href="/{{$corrupto->uri}}">{{{ $corrupto->nombre }}}</a></h2>
											<span class="byline">Noticias procesadas: {{$corrupto->total_noticias}}</span>
										</header>
                                        <p>{{{ substr($corrupto->summary, 0, 200) }}}...</p>
									</div>
								</section>
						</div>
					
                        @if (++$i % 3 == 0)
                        </div>
                        <div class="row">
                        @end
                        @end
                    </div>
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
								 <a href="https://twitter.com/crodas">crodas</a>. No rights reserved. | El conocimiento debe ser libre, sin un amo, por eso esto es <a href="https://github.com/crodas/corruptos.net">código abierto</a> | Design: <a href="http://html5up.net/">HTML5 UP</a>
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

  ga('create', 'UA-8237210-3', 'corruptos.net');
    ga('send', 'pageview');

    </script>

	</body>
</html>
