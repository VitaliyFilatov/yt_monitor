<div id="authContainer" style="display:none"></div>
<div class="row">
    <div id="channelPanel" class="col">
        <h2>Список каналов для мониторинга</h2>

        <div class="card" style="color:#294a70;">
            <?php echo $channelPanelBody; ?>
            <div class="card-footer">
                <button id="addChanelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Добавить канал</button>
                <button id="startMonitorBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Мониторинг каналов</button>
                <button id="editParametersBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none">Вернуться к редактированию</button>
            </div>
        </div>
    </div>
    
    <?php echo $patternPanel; ?>

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