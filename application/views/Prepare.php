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
                    <div id="saveProgressLine" class="col">
                        <div data-toggle="tooltip" data-placement="top" title="Прогресс создания паттерна" class="progress display-none" style="height:30%; margin-top:5%; margin-bottom:5%">
                            <div id="saveProgress" class="progress-bar progress-bar-custom" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>
                    <div class="col">
                        <button id="cancelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue" data-toggle="confirmation" data-btn-ok-label="Вернуться" data-btn-ok-class="btn-success" data-btn-cancel-label="Продолжить" data-btn-cancel-class="btn-danger" data-title="Подтверждение отмены" data-content="Это может привести к потере данных">Отменить</button>
                        <button id="saveBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue">Сохранить</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>