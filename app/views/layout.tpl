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
									
									<!-- Nav -->
										<nav id="nav">
											<ul>
												<li class="current_page_item"><a href="index.html">Portada</a></li>
												<li><a href="left-sidebar.html">Noticias</a></li>
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
											<div class="7u">
												<h2>Los corruptos</h2>
												<p>Bienvenidos al ranking de corruptos</p>
											</div>
											<div class="5u">
												<ul>
													<li><a href="/" class="button big fa fa-arrow-circle-right">Top Corruptos</a></li>
													<li><a href="/" class="button alt big fa fa-question-circle">Como funciona</a></li>
												</ul>
											</div>
										</div>
									</div>
								
								</div>

						</div>
					</div>
				</div>
			</div>
		
		<!-- Features Wrapper -->
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
											<span class="byline">Total: {{$corrupto->total_noticias}}</span>
										</header>
										<p>{{{ implode(",", array_keys($corrupto->keywords)) }}}</p>
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

		<!-- Footer Wrapper -->
			<div id="footer-wrapper">
				<footer id="footer" class="container">
					<div class="row">
						<div class="12u">
							<div id="copyright">
								&copy; <a href="https://twitter.com/crodas">crodas</a>. No rights reserved. | El conocimiento debe ser libre, sin un amo, por eso esto es <a href="https://github.com/crodas/corruptos.net">código abierto</a> | Design: <a href="http://html5up.net/">HTML5 UP</a>
							</div>
						</div>
					</div>
				</footer>
			</div>

	</body>
</html>
