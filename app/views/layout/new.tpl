<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <title>Botame.org</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    @section('seo')
    @show
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" /></head>

    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

    <link href="/assets/css/bootstrap.min.css" media="all" type="text/css" rel="stylesheet" />
    <link href="/assets/css/bootstrap-responsive.min.css" media="all" type="text/css" rel="stylesheet" />
    <link href="/assets/css/font-awesome.min.css" media="all" type="text/css" rel="stylesheet" />
    <link href="/assets/css/style.css" media="all" type="text/css" rel="stylesheet" />
    @section('css')
    @show
  <body>

    <!-- Navbar
      ================================================== -->
    <div class="navbar navbar-fixed-top navbar-inverse">
        <div class="navbar-inner">

            <div class="container">
                <a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>

                <a href="http://www.websterfolks.com/demo/reddish/" class="brand">Botame</a>                
                <div class="nav-collapse collapse" id="main-menu">
                    <ul class="nav">
                    @foreach($sections as $url => $name)
	                    <li>
		                    <a href="{{{$url}}}">{{{$name}}}</a>
	                    </li>
                    @end
                    </ul>

                        
                        <div class="btn-group pull-right">
                            <a href="/user/register" class="btn btn-info">
                                <i class="icon-fixed-width fa fa-user"></i> Registrarse</a>

                            <a href="/user/login" class="btn btn-info">
                                <i class="icon-fixed-width fa fa-unlock"></i> Iniciar sesión
                            </a>
                        </div> <!-- /.btn-group -->

                        
                    

                </div> <!-- /.nav-collapse -->

            </div> <!-- /.container -->

        </div>
    </div>


    <div class="container">
        <div class="row-fluid text-center">
            <div class="hidden-phone">
            </div>        
        </div>

        <div class="row-fluid">
            <div class="span9 content-wrap">
            @section('content')
            @show
            </div> <!--  /span9 -->


            
            <!-- Sidebar
            ================================================== -->
            <div class="span3 sidebar">
        
          <div class="widget">
              <form class="form-search form-horizontal pull-right" id="custom-search-form" method="GET" action="http://www.websterfolks.com/demo/reddish/search/" accept-charset="UTF-8" />
                <div class="input-append span12">
                  <input type="text" name="search" class="search-query" placeholder="Busqueda" />
                  <button type="submit" class=""><i class="icon-search"></i></button>
                </div>

              </form>              
              <div class="clearfix"></div>
          </div>

          <div class="widget">

              <a href="http://www.websterfolks.com/demo/reddish/link/new" class="btn btn-block btn-success">
                <i class="icon-fixed-width fa fa-link"></i> Enviar nueva noticia</a>

              <a href="http://www.websterfolks.com/demo/reddish/post/new" class="btn btn-block btn-info">
                <i class="icon-fixed-width fa fa-file-text"></i> Postular candidato  </a>

          </div>

          <div class="widget">
            <div class="list-widget">
              <h4>Usuarios Top</h4>
                <ul class="unstyled">
                    <li><a href="http://www.websterfolks.com/demo/reddish/user/2-demo"><img src="http://www.gravatar.com/avatar/7c4ff521986b4ff8d29440beec01972d?&s=70" /></a></li>
                    <li><a href="http://www.websterfolks.com/demo/reddish/user/5-amasbee"><img src="http://www.gravatar.com/avatar/d121b7154d6d068f4f72e9a8e74f1691?&s=70" /></a></li>
                    <li><a href="http://www.websterfolks.com/demo/reddish/user/7-gotya"><img src="http://www.gravatar.com/avatar/66e77f6bb17da2899e989bf439f36bdd?&s=70" /></a></li>
                    <li><a href="http://www.websterfolks.com/demo/reddish/user/8-snitz"><img src="http://www.gravatar.com/avatar/a0deceeb0a75cb57b5e85ebfae5fff53?&s=70" /></a></li>
                    <li><a href="http://www.websterfolks.com/demo/reddish/user/1-testuser"><img src="http://www.gravatar.com/avatar/d6477a5a87342e2321944343fbf03c2b?&s=70" /></a></li>
                </ul>
              <div class="clearfix"></div>
            </div>

          </div>

          <div class="widget">

            <div class="ads">
              <h4>Sponsors</h4>
            </div>

          </div>

          <div class="widget">

            <div class="list-widget">
              <h4>Important Links</h4>
              <ul class="unstyled links">
	<li>
		<a href="http://www.websterfolks.com">Just a few test</a>
	</li>
	<li>
		<a href="http://www.websterfolks.com">Another Test</a>
	</li>

</ul>
              <div class="clearfix"></div>
            </div>

      </div> <!-- /span3 /sidebar -->



        </div> <!-- /row-fluid -->

        <div class="row-fluid text-center">
            <div class="hidden-phone">
</div>        </div>
      
        <!-- Footer
          ================================================== -->
        <hr />

        <footer id="footer">
            <p class="pull-right"><a href="#top">Back To Top</a></p>

            <ul class="inline">
	<li>
		<a href="http://reddish.dev/">Terms & Services</a>
	</li>
	<li>
		<a href="http://reddish.dev/page/1-privacy">Privacy Policy</a>
	</li>

</ul>

        </footer>

    </div><!-- /container -->


    <!-- Le javascript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="/assets/js/jquery.min.js"></script>
    <script src="/assets/js/bootstrap.min.js"></script>
    <script src="/assets/js/custom.js"></script>
    @section('js')
    @show

    

  </div></body>
</html>

