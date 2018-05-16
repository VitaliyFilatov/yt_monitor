<div class="row">

    <?php echo $patternPanel; ?>

    <div id="editPanel" class="col">
        <h2>Вычисление порогового значения</h2>
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
                        <div class="btn-group" style="float:right;">
                            <button id="cancelBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue" data-toggle="confirmation" data-btn-ok-label="Вернуться" data-btn-ok-class="btn-success" data-btn-cancel-label="Продолжить" data-btn-cancel-class="btn-danger" data-title="Подтверждение отмены" data-content="Это может привести к потере данных">Отменить</button>
                            <button id="saveBtn" type="button" class="btn btn-outline-primary btn-outline-darkblue">Сохранить</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>