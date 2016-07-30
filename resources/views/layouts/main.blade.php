<?php
$request = $_SERVER['REQUEST_URI'];
?>

<!DOCTYPE html>
<html lang="en">
<head>

	<!-- start: Meta -->
	<meta charset="utf-8">
	<title>Частотный словарь английских слов онлайн - бесплатно - @yield('add_title')</title>
	<meta name="description" content="GotYa Free Bootstrap Theme"/>
	<meta name="keywords" content="Template, Theme, web, html5, css3, Bootstrap" />
	<meta name="author" content="Łukasz Holeczek from creativeLabs"/>
    <!-- end: Meta -->

    <!-- start: Mobile Specific -->
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <!-- end: Mobile Specific -->

    <!-- start: Facebook Open Graph -->
	<meta property="og:title" content=""/>
	<meta property="og:description" content=""/>
	<meta property="og:type" content=""/>
	<meta property="og:url" content=""/>
	<meta property="og:image" content=""/>
    <!-- end: Facebook Open Graph -->

    <!-- start: CSS -->
    <link href="/css/bootstrap.css" rel="stylesheet">
    <link href="/css/bootstrap-responsive.css" rel="stylesheet">
	<link href="/css/style.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Droid+Sans:400,700">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Droid+Serif">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Boogaloo">
	<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family=Economica:700,400italic">
    <!-- end: CSS -->

    <!-- Le HTML5 shim, for IE6-8 support of HTML5 elements -->
    <!--[if lt IE 9]>
    <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->

	<script type="text/javascript">
    	var csrf = '{{csrf_token()}}';
		var isLearning = false;
	</script>

</head>
<body>

	<!--start: Header -->
	<header>

		<!--start: Container -->
		<div class="container">

			<!--start: Row -->
			<div class="row">

				<!--start: Logo -->
				<div class="logo span3" style="padding-top: 10px;">

					<a class="brand" href="/" style="font-size: 20px;">Частотный словарь английских слов</a>

				</div>
                <!--end: Logo -->

                <!--start: Navigation -->
				<div class="span9">

					<div class="navbar navbar-inverse">
			    		<div class="navbar-inner">
			        		<div class="container">
			          			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
			            			<span class="icon-bar"></span>
			            			<span class="icon-bar"></span>
			            			<span class="icon-bar"></span>
			          			</a>
			          			<div class="nav-collapse collapse">
			            			<ul class="nav">
			              				<li<?php if($request == '/'): ?> class="active"<?php endif; ?>><a href="/">Главная</a></li>
										@can('auth')
										<li<?php if($request == '/repeat'): ?> class="active"<?php endif; ?>><a href="/repeat">Нужно повторять</a></li>
										<li<?php if($request == '/learning'): ?> class="active"<?php endif; ?>><a href="/learning">Изученные слова</a></li>
										@else
											<li><a href="/login">Войти</a></li>
											<li><a href="/register">Регистрация</a></li>
										@endcan
										<li<?php if($request == '/about'): ?> class="active"<?php endif; ?>><a href="/about">Об этом сайте</a></li>
			            			</ul>
			          			</div>
			        		</div>
			      		</div>
			    	</div>

				</div>
                <!--end: Navigation -->

			</div>
            <!--end: Row -->

		</div>
        <!--end: Container-->

	</header>
    <!--end: Header-->

    <!-- start: Page Title -->
	<div id="page-title">

		<div id="page-title-inner">

			<!-- start: Container -->
			<div class="container">

				<h2><i class="@yield('page_title_ico') ico-white"></i>@yield('page_title')</h2>

			</div>
            <!-- end: Container  -->

		</div>

	</div>
    <!-- end: Page Title -->

    <!--start: Wrapper-->
	<div id="wrapper">
	@yield('content')
	</div>
    <!-- end: Wrapper  -->

    <!-- start: Footer Menu -->
	<div id="footer-menu" class="hidden-tablet hidden-phone">

		<!-- start: Container -->
		<div class="container">

			<!-- start: Row -->
			<div class="row">

				<!-- start: Footer Menu Logo -->
				<div class="span2">
					<div id="footer-menu-logo">
						<a href="#"><img src="img/logo-footer-menu.png" alt="logo" /></a>
					</div>
				</div>
                <!-- end: Footer Menu Logo -->

                <!-- start: Footer Menu Links-->
				<div class="span9">

					<div id="footer-menu-links">

						<ul id="footer-nav">
							<li><a href="/">Главная</a></li>
							@can('auth')
							<li><a href="/repeat">Нужно повторять</a></li>
							<li><a href="/learning">Изученные слова</a></li>
							@endcan
							<li><a href="/about">Об этом сайте</a></li>
							<li><a href="/contact">Связаться с нами</a></li>
							@can('auth')
							<li><a href="/logout">Выход</a></li>
							@endcan
						</ul>
					</div>
				</div>
                <!-- end: Footer Menu Links-->

                <!-- start: Footer Menu Back To Top -->
				<div class="span1">

					<div id="footer-menu-back-to-top">
						<a href="#"></a>
					</div>

				</div>
                <!-- end: Footer Menu Back To Top -->

			</div>
            <!-- end: Row -->

		</div>
        <!-- end: Container  -->

	</div>
    <!-- end: Footer Menu -->

    <!-- start: Footer -->
	<div id="footer">

	</div>
    <!-- end: Footer -->

    <!-- start: Copyright -->
	<div id="copyright">

		<!-- start: Container -->
		<div class="container">

			<p>
				&copy; 2016 From Siberia with Love
			</p>

		</div>
        <!-- end: Container  -->

	</div>

	<div id="myModal" class="modal hide fade">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
		<h3 class="j-my-modal-header">Успешно</h3>
	  </div>
	  <div class="modal-body">
		<p class="j-my-modal-body">Слово добавлено в список "Повторять чаще"</p>
	  </div>
	  <div class="modal-footer">
		<a href="#" class="btn j-modal-close">Закрыть</a>
	  </div>
	</div>
    <!-- end: Copyright -->

    <!-- start: Java Script -->
    <!-- Placed at the end of the document so the pages load faster -->
<script src="/js/jquery-1.8.2.js"></script>
<script src="/js/bootstrap.js"></script>
<script src="/js/flexslider.js"></script>
<script src="/js/carousel.js"></script>
<script src="/js/jquery.cslider.js"></script>
<script src="/js/slider.js"></script>
<script def src="/js/custom.js"></script>
<script src="/js/main.js?version=10"></script>
    <!-- end: Java Script -->

@yield('add_script')

</body>
</html>