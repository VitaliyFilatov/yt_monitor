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
        
        .btn-darkblue{
            color: #294a70;
            border-color: #294a70;
        }
        
        .display-none
        {
            display:none;
        }
        
        .progress-bar-custom {
            background-color: #294a70 !important;
        }
        
        .popover-header {
            padding: .5rem .75rem;
            margin-bottom: 0;
            font-size: 1rem;
            color: #000;
            background-color: #f7f7f7;
            border-bottom: 1px solid #ebebeb;
            border-top-left-radius: calc(.3rem - 1px);
            border-top-right-radius: calc(.3rem - 1px);
        }
        
        
        .right-part{
            padding-left: 0px;
            border-left-width:1px;
            border-left-style:solid;
            border-left-color:rgba(0,0,0,.125);
        }
        
        .left-part{
            padding-right: 0px;
            border-right-width:1px;
            border-right-style:solid;
            border-right-color:rgba(0,0,0,.125);
        }
        
        
        
    </style>
  </head>

  <body class="text-center" style="background-color:#294a70;">
      <div id="sessionid" class="display-none"><?php echo $sessionid; ?></div>
      <div class="d-flex w-100 h-100 p-3 mx-auto flex-column">
          <header class="masthead mb-auto">
              <div class="inner">
                  <h3 class="masthead-brand"><img src="media/logo-2.png" width="40" />  YTMonitor</h3>
                  <nav class="nav nav-masthead justify-content-center">
                      <a id="reload" class="nav-link active" href="test">Подготовка(генерация паттерна)</a>
                      <a class="nav-link" href="analyze">Анализ каналов</a>
                      <a class="nav-link" href="monitor">Мониторинг каналов</a>
                  </nav>
              </div>
          </header>
          <div id="mainPanel" class="row w-50 mx-auto">
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
              <div id="editPanel" class="col display-none">
                  <h2>Редактирование/добавление паттерна</h2>
                  <div class="card" style="color:#294a70;">
                      
                      <div class="row">
                          <div class="col left-part">
                              <div class="card-header">Деструктивный котнтент
                              </div>
                          </div>
                          <div class="col right-part">
                              <div class="card-header">Недеструктивный контент
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col left-part">
                              <div class="card-body">
                                  <textarea id="destrVideoIdsInput" class="form-control" rows="5" placeholder="id видео через ','"></textarea>
                              </div>
                          </div>
                          <div class="col right-part">
                              <div class="card-body">
                                  <textarea id="nondestrVideoIdsInput" class="form-control" rows="5" placeholder="id видео через ','"></textarea>
                              </div>
                          </div>
                      </div>
                      <div class="card-footer">
                          <div class="row">
                              <div class="col">
                                  <div class="progress display-none" style="height:30%; margin-top:5%; margin-bottom:5%">
                                      <div id="saveProgress" class="progress-bar progress-bar-custom" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                  </div>
                              </div>
                              <div class="col">
                                  <button id="cancelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue" data-toggle="confirmation"
        data-btn-ok-label="Вернуться" data-btn-ok-class="btn-success"
        data-btn-cancel-label="Продолжить" data-btn-cancel-class="btn-danger"
        data-title="Подтверждение отмены" data-content="Это может привести к потере данных">Отменить</button>
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
      <script src="media/js/popper.js"></script>
      <script src="media/js/bootstrap.min.js"></script>
      <script src="media/js/bootstrup-confirmation.js"></script>
      <script src="media/js/prepare.js"></script>
  

</body></html>