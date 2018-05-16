<!DOCTYPE html>
<!-- saved from url=(0050)https://getbootstrap.com/docs/4.1/examples/cover/# -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="media/logo-2.png">

    <title>YouTube's monitor</title>

    <link href="media/Cover Template for Bootstrap_files/bootstrap.min.css" rel="stylesheet">

    <link href="media/Cover Template for Bootstrap_files/cover.css" rel="stylesheet">
    
    <link href="media/css/sizeclasses.css" rel="stylesheet">
    
    <link href="media/css/custom-styles.css" rel="stylesheet">
    
    <link href="media/fontawesome-free/web-fonts-with-css/css/fontawesome-all.css" rel="stylesheet">
    
    <style>
        .btn-outline-darkblue{
            color: #294a70;
            border-color: #294a70;
        }
    </style>    
  </head>

  <body class="text-center" style="background-color:#294a70;">
      <div id="sessionid" class="display-none"><?php echo $sessionid; ?></div>
      <div class="d-flex w-100 h-100 p-3 mx-auto flex-column">
          <header class="masthead" style="margin-bottom:5%">
              <div class="inner">
                  <h3 class="masthead-brand"><img src="media/logo-2.png" width="40" />  YTMonitor</h3>
                  <nav class="nav nav-masthead justify-content-center">
                      <?php foreach($links as $link): ?>
                        <a id="<?php echo $link['id'] ?>" class="nav-link <?php echo $link['active'] ?> custom-link" href="<?php echo $link['href'] ?>"><?php echo $link['text'] ?></a>
                      <?php endforeach; ?>
                  </nav>
              </div>
          </header>
          <?php echo $content; ?>
          <footer class="mastfoot mt-auto">
              <div class="inner"></div>
          </footer>
      </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="media/Cover Template for Bootstrap_files/jquery-3.3.1.slim.min.js.Без названия" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="../../../../assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
      <script src="media/Cover Template for Bootstrap_files/popper.min.js.Без названия"></script>
      <script src="media/Cover Template for Bootstrap_files/bootstrap.min.js.Без названия"></script>
      <script src="media/js/libs/jquery-3.2.1.js"></script>
      <script src="media/js/popper.js"></script>
      <script src="media/js/bootstrap.min.js"></script>
      <script src="media/js/bootstrup-confirmation.js"></script>
      <script src="media/js/main.js"></script>
      <?php foreach($scripts as $script): ?>
        <script src="<?php echo $script ?>"></script>
      <?php endforeach; ?>
  

</body></html>