<!DOCTYPE html>
<!-- saved from url=(0050)https://getbootstrap.com/docs/4.1/examples/cover/# -->
<html lang="en"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="media/logo-2.png">

    <title>YouTube's monitor</title>

    <!-- Bootstrap core CSS -->
    <link href="media/Cover Template for Bootstrap_files/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="media/Cover Template for Bootstrap_files/cover.css" rel="stylesheet">
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
    <div id="sessionid" class="display-none"><?php echo $sessionid; ?></div>
      <div class="d-flex w-100 h-100 p-3 mx-auto flex-column">
          <header class="masthead" style="margin-bottom:5%">
              <div class="inner">
                  <h3 class="masthead-brand"><img src="media/logo-2.png" width="40" />  YTMonitor</h3>
                  <nav class="nav nav-masthead justify-content-center">
                      <a class="nav-link" href="test">Подготовка(генерация паттерна)</a>
                      <a class="nav-link" href="analyze">Анализ каналов</a>
                      <a class="nav-link active" href="#">Мониторинг каналов</a>
                  </nav>
              </div>
          </header>
          <div id="authContainer" style="display:none"></div>
          <div class="row">
              <div id="channelPanel" class="col">
                  <h2>Список каналов для мониторинга</h2>
                  
                  <div class="card" style="color:#294a70;">
                      <div class="card-body">
                          <div class="pre-scrollable">
                              <div class="container">
                                  <ul id="channelList" class="list-group" style="color:#294a70;">
                                      <li id="unusedChannel" class="list-group-item">
                                          <div class="row">
                                              <div class="col-sm-5">
                                                  Название канала
                                              </div>
                                              <div name="channelId" class="col-sm-5">
                                                  id канала
                                              </div>
                                              <div name="channelBtn" class="col-sm-2">
                                                  
                                              </div>
                                          </div>
                                      </li>
                                      <li id="insertChannel" class="list-group-item display-none">
                                          <div class="row">
                                              <div class="col-sm-5">
                                                  <input id="nameChannelInput" type="text" class="form-control" placeholder="Название канала">
                                              </div>
                                              <div class="col-sm-5">
                                                  <input id="idChannelInput" type="text" class="form-control" placeholder="id канала">
                                              </div>
                                              <div class="col-sm-2">
                                                  <button id="insertChannelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">OK</button>
                                              </div>
                                          </div>
                                      </li>
                                  </ul>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                          <button id="addChanelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Добавить канал</button>
                          <button id="startMonitorBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Мониторинг каналов</button>
                          <button id="editParametersBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none">Вернуться к редактированию</button>
                      </div>
                  </div>
              </div>
              <div id="patternPanel" class="col">
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
                  </div>
              </div>
              
              <div id="resultPanel" class="col-sm-7 display-none">
                  <h3 id="infoWork" class="display-none">Результаты мониторинга: производится мониторинг</h3>
                  <h3 id="infoDone" class="display-none">Результаты мониторинга: мониторинг приостановлен</h3>
                  <div class="card" style="color:#294a70;">
                      <div class="card-header">
                          <h3 id="infoWork" class="display-none">производится мониторинг</h3>
                          <h3 id="infoDone" class="display-none">мониторинг остановлен</h3>
                      </div>
                      <div class="card-body">
                          <div id="scrollResult" class="pre-scrollable">
                              <div class="container">
                                  <ul id="resultList" class="list-group" style="color:#294a70;">
                                  </ul>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                          <button id="stopAnalyzeBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Остановить мониторинг</button>
                          <button id="continueBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none" style="margin-top:0px;">Продолжить мониторинг</button>
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
    <script src="media/js/monitor.js"></script>
  

</body></html>