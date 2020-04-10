<!DOCTYPE html>

<html>

  <head>
    
    <meta charset="UTF-8">
    
    <title>Restaurante</title>
    
    <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
    
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    
    <!-- Theme style -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/css/AdminLTE.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/css/skins/_all-skins.min.css">
    
    <!-- iCheck -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/skins/square/_all.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
    
    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    
    <link rel="stylesheet" href="assets/stylesheets/main.css">
    
    <!-- jQuery 3.1.1 -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    
    <script type="text/javascript" src="assets/js/jquery-2.1.3.min.js"></script>
    <script src="assets/js/jquery.backstretch.min.js"></script>

    <!-- AdminLTE App -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/2.4.2/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/iCheck/1.0.2/icheck.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js"></script>

    <script type="text/javascript">
      $.ajaxSetup({ headers: { 'csrftoken' : '{{ csrf_token() }}' } });
    </script>
    
    <script type='text/javascript' src='//platform-api.sharethis.com/js/sharethis.js#property=5c4b831b80b4ba001b1eeb20&product=inline-share-buttons' async='async'></script>
    
    <script src="//code.jquery.com/jquery-3.1.1.slim.min.js"></script>
    <script src="assets/js/fullclip.js"></script>
    
    <script>
    
      $(function () {
        
        $('#sidebar-form').on('submit', function (e) {
          e.preventDefault();
        });
        
        $('.sidebar-menu li.active').data('lte.pushmenu.active', true);
        
        $('#search-input').on('keyup', function () {
          
          var term = $('#search-input').val().trim();
          
          if (term.length === 0) {
            
            $('.sidebar-menu li').each(function () {
              
              $(this).show(0);
              $(this).removeClass('active');
              
              if ($(this).data('lte.pushmenu.active')) {
                $(this).addClass('active');
              }
              
            });
            
            return;
            
          }
          
          $('.sidebar-menu li').each(function () {
            
            if ($(this).text().toLowerCase().indexOf(term.toLowerCase()) === -1) {
              
              $(this).hide(0);
              $(this).removeClass('pushmenu-search-found', false);
              
              if ($(this).is('.treeview')) {
                $(this).removeClass('active');
              }
              
            }
            
            else {
              
              $(this).show(0);
              $(this).addClass('pushmenu-search-found');
              
              if ($(this).is('.treeview')) {
                $(this).addClass('active');
              }
              
              var parent = $(this).parents('li').first();
              
              if (parent.is('.treeview')) {
                parent.show(0);
              }
              
            }
            
            if ($(this).is('.header')) {
              
              $(this).show();
              
            }
            
          });
          
          $('.sidebar-menu li.pushmenu-search-found.treeview').each(function () {
            $(this).find('.pushmenu-search-found').show(0);
          });
        
        });
        
      });
    
    </script>
    
    <script type="text/javascript">
      
      $('.fullBackground').fullClip({
        transitionTime: 1000,
        wait: 3000
      });
      
    </script>
    
  </head>

  <body class="sidebar-mini">
    
    <div class="sharethis-inline-share-buttons"></div>
    
    <div class="wrapper">
        
      <header class="main-header">
          
        <a href="#" class="logo" style="color: #fff;">
    
          <b>Restaurante</b>
      
        </a>
          
        <nav class="navbar navbar-static-top" role="navigation" style="border-bottom: 1px solid rgba(255,255,255,0.5);">
            
          <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" style="color: #fff;">
          
            <span class="sr-only">Toggle navigation</span>
        
          </a>
          
          <div class="navbar-custom-menu">
              
            <ul class="nav navbar-nav">
                    
              <a href="https://www.facebook.com/www.restaurantesecreto.mx/" target="_blank" rel="noopener noreferrer"> <img src="assets/images/social_media/facebook.png" style="width:40px; margin-top: 5px; display: inline-block;"> </a>
              <a href="https://twitter.com/secreto_rp" target="_blank" rel="noopener noreferrer"> <img src="assets/images/social_media/twitter.png" style="width:40px; margin-top: 5px; display: inline-block;"> </a>
              <a href="https://www.instagram.com/elsecretoapuertacerrada/" target="_blank" rel="noopener noreferrer"> <img src="assets/images/social_media/instagram.png" style="width:40px; margin-top: 5px; margin-right: 10px; display: inline-block;"> </a>
                
            </ul>
              
          </div>
            
        </nav>
          
      </header>
        
      <aside class="main-sidebar" id="sidebar-wrapper" style="background: rgba(0,0,0,1);">

        <section class="sidebar">
               
          <img src="assets/images/logo.jpg" alt="User Image" style="width: 100%; margin-bottom: 20px; padding-top: 20px; padding-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.5); border-top: 1px solid rgba(255,255,255,0.5);">
        
          <ul class="sidebar-menu">
                  
            <li class="{{ Request::is('profiles*') ? 'active' : '' }}">
              <a href="index.php" style="color: #fff;"><i class="glyphicon glyphicon-home"></i><span style="margin-left: 5px;">Inicio</span></a>
            </li>
              
            <li class="{{ Request::is('profiles*') ? 'active' : '' }}">
              <a href="about.php" style="color: #fff;"><i class="glyphicon glyphicon-info-sign"></i><span style="margin-left: 5px;">Nosotros</span></a>
            </li>
              
            <li class="{{ Request::is('shared*') ? 'active' : '' }}">
              <a href="partners.php" style="color: #fff;"><i class="glyphicon glyphicon-user"></i><span style="margin-left: 5px;">Aliados</span></a>
            </li>
              
            <li class="{{ Request::is('colleges*') ? 'active' : '' }}">
              <a href="menu.php" style="color: #fff;"><i class="glyphicon glyphicon-list-alt"></i><span style="margin-left: 5px;">Menu</span></a>
            </li>
              
            <li class="{{ Request::is('jobs*') ? 'active' : '' }}">
              <a href="events.php" style="color: #fff;"><i class="glyphicon glyphicon-calendar"></i><span style="margin-left: 5px;">Eventos</span></a>
            </li>
              
            <li class="{{ Request::is('projects*') ? 'active' : '' }}">
              <a href="services.php" style="color: #fff;"><i class="glyphicon glyphicon-cutlery"></i><span style="margin-left: 5px;">Servicios</span></a>
            </li>
              
            <li class="{{ Request::is('personalDatas*') ? 'active' : '' }}">
              <a href="store.php" style="color: #fff;"><i class="glyphicon glyphicon-usd"></i><span style="margin-left: 5px;">Arte en Venta</span></a>
            </li>
            
            <li class="{{ Request::is('personalDatas*') ? 'active' : '' }}">
              <a href="galery.php" style="color: #fff;"><i class="glyphicon glyphicon-picture"></i><span style="margin-left: 5px;">Galería</span></a>
            </li>
            
            <li class="{{ Request::is('personalDatas*') ? 'active' : '' }}">
              <a href="reservation.php" style="color: #fff;"><i class="glyphicon glyphicon-phone-alt"></i><span style="margin-left: 5px;">Reservación</span></a>
            </li>
            
            <li class="{{ Request::is('personalDatas*') ? 'active' : '' }}">
              <a href="location.php" style="color: #fff;"><i class="glyphicon glyphicon-globe"></i><span style="margin-left: 5px;">Ubicación</span></a>
            </li>
            
            <li class="{{ Request::is('personalDatas*') ? 'active' : '' }}">
              <a href="https://kiosco-dot-si-nube.appspot.com?mprs=R0dTMTIwODA2RENB" target="_blank" rel="noopener noreferrer" style="color: #fff;"><i class="glyphicon glyphicon-credit-card"></i><span style="margin-left: 5px;">Facturación</span></a>
            </li>
                
          </ul>
            
        </section>
            
      </aside>
        
      <div class="content-wrapper" style="background: #000; border-left: 1px solid rgba(255,255,255,0.5);">
        
          <section class="content-header">
          
          </section>
          
          <div class="content" style = "margin-top: 20px">
          
            <div class="clearfix"></div>
        
            <div class="clearfix"></div>
            
            <div class="box-body">
        
              <div class="row">
         
                <div class="col-md-12">
                  
                  <section class = "content-image" style="background: #fff;">
                    
                    <img src="assets/images/index/flyer.png">
                    
                  </section>
                  
                </div>
              
              </div>
            
            </div>
          
          </div>
        
      </div>
        
      <footer class="main-footer" style="max-height: 100px;text-align: center; border: 1px solid rgba(0,0,0,0.1);">
        
        Heráclito 309 Col. Chapultepec Morales, Delegación Miguel Hidalgo, 11570 Ciudad de México, Distrito Federal +52 (55) 50826760
        
      </footer>
        
    </div>
    
  </body>
  
</html>