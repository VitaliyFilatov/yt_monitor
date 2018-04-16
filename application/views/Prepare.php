<!DOCTYPE html>
<!-- saved from url=(0050)https://getbootstrap.com/docs/4.1/examples/cover/# -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="logo-2.png">

    <title>YouTube's monitor</title>

    <link href="media/Cover Template for Bootstrap_files/bootstrap.min.css" rel="stylesheet">

    <link href="media/Cover Template for Bootstrap_files/cover.css" rel="stylesheet">
    
    <link href="media/css/sizeclasses.css" rel="stylesheet">
    

    <style>
        .btn-outline-darkblue{
            color: #294a70;
            border-color: #294a70;
        }
        .btn-outline-darkblue:hover{
            color: #fff;
            background-color: #294a70;
            border-color: #294a70;
        }
        
        .btn-outline-darkblue:focus{
            color: #fff;
            background-color: #294a70;
            border-color: #294a70;
        }
        
        
        .btn-outline-darkblue:active:focus{
            color: #fff;
            background-color: #294a70;
            border-color: #294a70;
        }
        
        
        .btn-outline-darkblue:active{
            color: #fff;
            background-color: #294a70;
            border-color: #294a70;
        }
        
        .display-none
        {
            display:none;
        }
        
        .progress-bar-custom {
            background-color: #294a70 !important;
        }
        
        
        
        
    </style>
  </head>

  <body class="text-center" style="background-color:#294a70;">
      <div class="d-flex w-100 h-100 p-3 mx-auto flex-column">
          <header class="masthead mb-auto">
              <div class="inner">
                  <h3 class="masthead-brand">YTMonitor</h3>
                  <nav class="nav nav-masthead justify-content-center">
                      <a class="nav-link active" href="test">Подготовка(генерация паттерна)</a>
                      <a class="nav-link" href="analyze">Анализ каналов</a>
                      <a class="nav-link" href="#">Мониторинг каналов</a>
                  </nav>
              </div>
          </header>
          <div id="mainPanel" class="row w-50 mx-auto">
              <div class="col">
                  <h2>Список паттернов</h2>
                  
                  <div class="card" style="color:#294a70;">
                      <div class="card-body">
                          <div class="pre-scrollable">
                              <div class="container">
                                  <ul id="patternList" class="list-group" style="color:#294a70;">
                                      
                                  </ul>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                          <button id="addPatternBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Добавить паттерн</button>
                      </div>
                  </div>
              </div>
              <div id="editPanel" class="col display-none">
                  <h2>Редактирование/добавление паттерна</h2>
                  <div class="card" style="color:#294a70;">
                      <div class="card-header">
                          <input id="patternNameInput" type="text" class="form-control" placeholder="Название паттерна" style="text-align: center">
                      </div>
                      <div class="card-body">
                          <textarea id="videoIdsInput" class="form-control" rows="5" placeholder="id видео через ','"></textarea>
                      </div>
                      <div class="card-footer">
                          <div class="row">
                              <div class="col">
                                  <div class="progress display-none" style="height:30%; margin-top:5%; margin-bottom:5%">
                                      <div id="saveProgress" class="progress-bar progress-bar-custom" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                              </div>
                              <div class="col">
                                  <button id="cancelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue">Отменить</button>
                                  <button id="saveBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue">Сохранить</button>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
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
      <script src="media/js/prepare.js"></script>
  

</body></html>