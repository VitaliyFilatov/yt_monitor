<div id="authContainer" style="display:none"></div>
<div class="row">
    <div id="channelPanel" class="col">
        <h2>Список каналов для анализа</h2>

        <div class="card" style="color:#294a70;">
            <?php echo $channelPanelHeader; ?>
            <?php echo $channelPanelBody; ?>
            <div class="card-footer">
                <button id="addChanelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Добавить канал</button>
                <button id="cancelAddChanelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none">Отменить</button>
                <button id="startAnalyzeBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Анализ каналов</button>
                <button id="editParametersBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none" style="
    margin-top: 0px;">Вернуться к редактированию</button>
            </div>
        </div>
    </div>
    
    <?php echo $patternPanel; ?>

    <div id="resultPanel" class="col-sm-7 display-none">
        <h2 id="infoWork" class="display-none">Результаты анализа: производится анализ</h2>
        <h2 id="infoDone" class="display-none">Результаты анализа: анализ завершён</h2>
        <div class="card" style="color:#294a70;">
            <?php echo $resultHeader; ?>
            <?php echo $resultBody; ?>
            <div class="card-footer">
                <div class="row">
                    <div class="col">
                        <button id="stopAnalyzeBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block">Остановить анализ</button>
                    </div>
                    <div class="col">
                        <button id="pauseAnalyzeBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block" style="margin-top: 0px;">Пауза</button>
                        <button id="continueBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none" style="margin-top: 0px;">Продолжить</button>
                        <button id="startAgainBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue btn-block display-none" style="margin-top: 0px;">Начать заново</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>