<?php

	//header('Strict-Transport-Security: max-age=31536000 ; includeSubDomains');
	header("X-Frame-Options: deny");
	header("X-XSS-Protection: 1; mode=block");
	header('X-Content-Type-Options: nosniff');
	header('Content-Security-Policy: "self" http://fonts.googleapis.com/css?family=Lato:100,300,400 https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js');
	header('X-Permitted-Cross-Domain-Policies: none');
	header('Referrer-Policy: origin');
	header('Expect-CT: max-age=86400, enforce, report-uri=""');
	
	header("Expires: Tue, 01 Jul 2001 06:00:00 GMT");
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
	header("Cache-Control: no-store, no-cache, must-revalidate");
	header("Cache-Control: post-check=0, pre-check=0", false);
	header("Pragma: no-cache");
	
	header_remove("X-Powered-By");
	
	ini_set("session.cookie_httponly", 1);
	ini_set("session.use_only_cookies", 1);
	
?>

<!DOCTYPE html>

<html lang = "es">

	<head>

		<meta charset = "utf-8">
		<meta name = "description" content = "">
		<meta name = "keywords" content = "">
		<meta name = "author" content = "ARVARIAM">
		<meta name = "viewport" content = "width=device-width, initial-scale=1">
		
		<link rel = "stylesheet" href = "http://fonts.googleapis.com/css?family=Lato:100,300,400">
		<link rel = "stylesheet" href = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
		<link rel = "stylesheet" href = "assets/stylesheets/main.css">
		
		<link rel = "apple-touch-icon" sizes = "57x57" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "60x60" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "72x72" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "76x76" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "114x114" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "120x120" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "144x144" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "152x152" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "apple-touch-icon" sizes = "180x180" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "icon" type = "image/png" sizes = "192x192"  href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "icon" type = "image/png" sizes = "32x32" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "icon" type = "image/png" sizes = "96x96" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		<link rel = "icon" type = "image/png" sizes = "16x16" href = "http://www.acasyc.com.mx/assets/images/icon.png">
		
		<script src = "https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"> </script>
    	<script src = "https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"> </script>
	
		<title> ARVARIAM - La Magia de tus Sentidos </title>

	</head>

	<body>

		<header class = "principal-header">

		</header>

		<section class = "principal-section">

			<section class = "principal-section-container-section container container-fluid">

				<img class = "principal-section-container-section-img" src = "assets/images/" alt = "ARVARIAM Principal Image">

				<nav class = "principal-header-nav navbar navbar-inverse navbar-fixed-top">

					<div class = "principal-header-nav-container-div container-fluid">

						<div class = "principal-header-nav-container-div-navbar-header-div navbar-header">

							<button type = "button" class = "principal-header-nav-container-div-navbar-header-div-toggle-div navbar-toggle" data-toggle = "collapse" data-target = "#myNavbar">

								<span class = "icon-bar"> </span>
								<span class = "icon-bar"> </span>
								<span class = "icon-bar"> </span>

							</button>

						</div>

						<div class = "principal-header-nav-container-div-navbar-collapse-div collapse navbar-collapse" id = "myNavbar">

							<ul class = "principal-header-nav-container-div-navbar-collapse-div-ul nav navbar-nav">

								<li class = "active"> <a href = "index.php"> Detrás de la Magia </a> </li>
								<li class = "divider"> </li>
								<li> <a href = ".php"> Bondades para tus Sentidos </a> </li>
								<li class = "divider"> </li>
								<li> <a href = "products.php"> Productos </a> </li>
								<li class = "divider"> </li>
								<li> <a href = "recent_entries.php"> Entradas Recientes </a> </li>
								
							</ul>

						</div>

					</div>

				</nav>

				<nav class = "principal-header-nav navbar navbar-inverse navbar-fixed-top">

					<div class = "principal-header-nav-container-div container-fluid">

						<div class = "principal-header-nav-container-div-navbar-header-div navbar-header">

							<button type = "button" class = "principal-header-nav-container-div-navbar-header-div-toggle-div navbar-toggle" data-toggle = "collapse" data-target = "#myNavbar">

								<span class = "icon-bar"> </span>
								<span class = "icon-bar"> </span>
								<span class = "icon-bar"> </span>

							</button>

						</div>

						<div class = "principal-header-nav-container-div-navbar-collapse-div collapse navbar-collapse" id = "myNavbar">

							<ul class = "principal-header-nav-container-div-navbar-collapse-div-ul nav navbar-nav">

								<li> <a href = "profile.php"> Detrás de la Magia </a> </li>
								<li> <a href = "profile_projects.php"> Bondades para tus Sentidos </a> </li>
								<li> <a href = "profile_proposals.php"> Productos </a> </li>
								<li> <a href = "profile_guides.php"> Entradas Recientes </a> </li>

							</ul>

						</div>

					</div>

				</nav>

				<section class = "principal-section-container-section-principal-phrase-section">

					<h1 class = "principal-section-container-section-principal-phrase-section-h1"> Somos un grupo de abogados, consultores y asesores. </h1>

					<a class = "principal-section-container-section-principal-phrase-section-a" href = "contact.php"> contactanos </a>

				</section>

				<section class = "principal-section-container-section-principal-info-section">

					<h2 class = "principal-section-container-section-principal-info-section-h2"> Detrás de la Magia </h2>

					<p class = "principal-section-container-section-principal-info-section-p"> ARVARIAM es un grupo que comenzó elaborando productos para la higiene personal de sus creadores, derivado de que uno de ellos presentara varias alergias que le causaban otros productos comerciales no naturales, posterior a constantes valoraciones médicas terminó por utilizar productos dermatológicos que son muy buenos pero son excesivamente caros, por lo que con el tiempo otro de los integrantes de este grupo, con su conocimiento heredado y adquirido de herbolaria, comenzó a realizar varias pruebas para la elaboración de cremas, shampoo, jabones y cosméticos utilizando lo más posible productos naturales.
					<br> <br>
					Durante más de tres años esos productos fueron testados y fabricados por estas dos personas preocupadas por conseguir productos que se adaptaran a la piel delicada de una y a la piel mixta de la otra, transformando recetas constantemente hasta lograr conseguir las recetas que buscaban.
					<br> <br>
					Así es como nace este grupo lleno de magia, por la necesidad, convicción y amor a sí mismas que las llevó a encaminarse a esta travesía que disfrutan y las hace reconciliarse con sus sentidos. </p>

				</section>

			</section>

		</section>

		<footer class = "principal-footer">

			<section class = "principal-footer-additional-info-section container-fluid">
				
				<section class = "principal-footer-additional-info-section-store-section col-sm-4 col-xs-12">

					<h2 class = "principal-footer-additional-info-section-store-section-h2"> Nosotros </h2>
				
					<div class = "principal-footer-additional-info-section-store-section-div">

						<img class = "principal-footer-additional-info-section-store-section-div-img" src = "assets/images/logo_0.png">
						<p class = "principal-footer-additional-info-section-store-section-div-p"> Lorem Ipsum es simplemente el texto de relleno. </p>
					
					</div>
				
				</section>

				<section class = "principal-footer-additional-info-section-contact-section col-sm-4 col-xs-12">

					<h2 class = "principal-footer-additional-info-section-contact-section-h2"> Contacto </h2>

					<a href = "contact.php"> Contáctanos </a>

				</section>
			
				<section class = "principal-footer-additional-info-section-commitment-section col-sm-4 col-xs-12">
				
					<h2 class = "principal-footer-additional-info-section-commitment-section-h2"> Nuestro Compromiso </h2>
					<p class = "principal-footer-additional-info-section-commitment-section-p"> Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen. </p>

				</section>
			
			</section>
		
			<p class = "principal-footer-additional-info-p"> &copy; <?php echo strftime('%Y'); ?> Todos los Derechos Reservados ACASYC </p>

		</footer>

	</body>

</html>