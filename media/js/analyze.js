var startAnalyzeBtn = $("#startAnalyzeBtn")[0];
var patternPanel = $("#patternPanel")[0];
var resultPanel = $("#resultPanel")[0];
var channelPanel = $("#channelPanel")[0];
var addChanelBtn = $("#addChanelBtn")[0];
var insertChannel = $("#insertChannel")[0];
var insertChannelBtn = $("#insertChannelBtn")[0];
var nameChannelInput = $("#nameChannelInput")[0];
var idChannelInput = $("#idChannelInput")[0];
var stopAnalyzeBtn = $("#stopAnalyzeBtn")[0];
var pauseAnalyzeBtn = $("#pauseAnalyzeBtn")[0];
var authLink = $("#authLink")[0];
var infoWork = $("#infoWork")[0];
var infoDone = $("#infoDone")[0];
var continueBtn = $("#continueBtn")[0];
var startAgainBtn = $("#startAgainBtn")[0];
var editParametersBtn = $("#editParametersBtn")[0];
var reload = $("#reload")[0];
var inProcess = false;
var cancelAddChannelBtn = $("#cancelAddChanelBtn")[0];
var editBtnDisabled = false;


var subAnalyze;


function errorAnalyze(jqXHR, textStatus, errorThrown) {
    $('#spinner')[0].classList.add("display-none");
    console.log("error: " + errorThrown);
    subAnalyze = false;
    infoWork.classList.add("display-none");
    infoDone.classList.remove("display-none");
    disableBtn(stopAnalyzeBtn);
    pauseAnalyzeBtn.classList.add("display-none");
    startAgainBtn.classList.remove("display-none");
    continueBtn.classList.add("display-none");
}

function successAnalyze(data, textStatus, jqXHR) {
    console.log("data: " + data);
    data = JSON.parse(data);
    if (data.return_type == 1) {
        authContainer.innerHTML = data.result;
        $("#authLink")[0].click();
    } else if (data.return_type == 0) {
        console.log(data);
        var return_type = data.return_type;
        data = data.result;
        if (data != null) {
            for (var i = 0; i < data.length; i++) {
                var videoId = data[i].videoid;
                var similarity = data[i].sim;
                addResult(videoId, similarity);
            }
            $('[data-toggle="tooltip"]').tooltip();
        }
        pauseAnalyzeBtn.classList.add("display-none");
        startAgainBtn.classList.remove("display-none");
    } else if (data.return_type == 3) {
        if (data.result != getSessionId()) {
            return;
        }
        pauseAnalyzeBtn.classList.add("display-none");
        startAgainBtn.classList.remove("display-none");
        enableBtn(pauseAnalyzeBtn);
        enableBtn(stopAnalyzeBtn);
        toEditParameter();
    } else if (data.return_type == 4) {
        if (data.result != getSessionId()) {
            return;
        }
        continueBtn.classList.remove("display-none");
        pauseAnalyzeBtn.classList.add("display-none");
        enableBtn(pauseAnalyzeBtn);
    }
    infoWork.classList.add("display-none");
    infoDone.classList.remove("display-none");
    disableBtn(stopAnalyzeBtn);
    $('#spinner')[0].classList.add("display-none");
    subAnalyze = false;
}

function onStartAnalyzeBtnClick() {
    $('#spinner')[0].classList.remove("display-none");
    cancelAddChannelBtn.click();
    scrollChannel.style = "height:" + $('#scrollResult')[0].style.height;
    while (resultList.children.length > 1) {
        //        if (resultList.children[1].id != "spinner") {
        //            resultList.children[1].remove();
        //        }
        resultList.children[1].remove();
    }
    insertChannel.classList.add("display-none");

    var channelBtn = $("[name*='channelBtn']");
    for (var i = 0; i < channelBtn.length; i++) {
        channelBtn[i].classList.add("display-none");
    }

    var channelId = $("[name*='channelId']");
    for (var i = 0; i < channelBtn.length; i++) {
        channelId[i].classList.remove("col-sm-5");
        channelId[i].classList.add("col-sm-7");
    }

    patternPanel.classList.add("display-none");
    channelPanel.classList.remove("col");
    channelPanel.classList.add("col-sm-5");
    resultPanel.classList.remove("display-none");

    startAnalyzeBtn.classList.add("display-none");
    pauseAnalyzeBtn.classList.remove("display-none");
    startAgainBtn.classList.add("display-none");
    addChanelBtn.classList.add("display-none");
    editParametersBtn.classList.remove("display-none");
    infoWork.classList.remove("display-none");
    infoDone.classList.add("display-none");
    subAnalyze = true;
    var rows = channelList.children;
    var channelIds = [];
    for (var i = 0; i < rows.length - 1; i++) {
        if (rows[i].id != "unusedChannel" && !rows[i].classList.contains("display-none")) {
            channelIds.push(rows[i].id.substr(9));
        }
    }

    var sessionid = getSessionId();
    $.ajax({
        url: "analyzeChannels",
        type: "POST",
        data: {
            channelIds: channelIds,
            patternId: idPatternActive.substr(2),
            sessionid: sessionid
        },
        error: errorAnalyze,
        success: successAnalyze
    });

    enableBtn(stopAnalyzeBtn);
    enableBtn(pauseAnalyzeBtn);

    setTimeout(getSubResultAnalyze, 1000);

    //    $.ajax({
    //        url: "getContent?XDEBUG_SESSION_START=ECLIPSE_DBGP",
    //        type: "POST",
    //        data: {channelIds : channelIds,
    //               patternId : idPatternActive.substr(2),
    //               sessionid : sessionid},
    //        error: errorAnalyze,
    //        success: successAnalyze
    //    });
}


function onStopAnalyzeBtnClick() {
    //    pauseAnalyzeBtn.classList.add("display-none");
    //    startAgainBtn.classList.remove("display-none");
    //    continueBtn.classList.add("display-none");
    this.blur();
    disableBtn(pauseAnalyzeBtn);
    disableBtn(stopAnalyzeBtn);
    subAnalyze = false;
    stopProcess();
}

function onPauseAnalyzeBtnClick() {
    disableBtn(pauseAnalyzeBtn);
    disableBtn(stopAnalyzeBtn);
    this.blur();
    var sessionid = getSessionId()
    $.ajax({
        url: "pauseAnalyze",
        type: "POST",
        data: {
            sessionid: sessionid
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + errorThrown);
        },
        success: function (data, textStatus, jqXHR) {}
    });
}

function onContinueBtnClick() {
    continueBtn.classList.add("display-none");
    pauseAnalyzeBtn.classList.remove("display-none");
    startAgainBtn.classList.add("display-none");
    this.blur();
    var sessionid = getSessionId();
    subAnalyze = true;
    infoWork.classList.remove("display-none");
    infoDone.classList.add("display-none");
    $.ajax({
        url: "continueAnalyze",
        type: "POST",
        data: {
            sessionid: sessionid
        },
        error: errorAnalyze,
        success: successAnalyze
    });
    enableBtn(stopAnalyzeBtn);
    setTimeout(getSubResultAnalyze, 1000);
}

function onStartAgainBtnClick() {
    pauseAnalyzeBtn.classList.remove("display-none");
    continueBtn.classList.add("display-none");
    startAgainBtn.classList.add("display-none");
    this.blur();
    onStartAnalyzeBtnClick();
}

function onAddChanelBtnClick() {
    nameChannelInput.value = "";
    idChannelInput.value = "";
    insertChannel.classList.remove("display-none");
    addChanelBtn.classList.add("display-none");
    cancelAddChanelBtn.classList.remove("display-none");
}

function onInsertChannelBtnClick() {
    this.blur();
    //insertChannel.classList.add("display-none");
    if (nameChannelInput.value == "" || idChannelInput.value == "") {
        insertChannel.classList.add("display-none");
        addChanelBtn.classList.remove("display-none");
        cancelAddChanelBtn.classList.add("display-none");
        return;
    }
    if ($("#removeChannelBtn" + idChannelInput.value)[0] !== undefined) {
        alert("Видео с таким id канала уже добавлено");
        return;
    }
    idChannelInput.value = idChannelInput.value.replace(/\s{1,}/g, '');
    var li = document.createElement("li");
    li.classList.add("list-group-item");
    li.id = "idchannel" + idChannelInput.value;
    li.innerHTML = '<div name="channelName" class="row"><div class="col-sm-5">' + nameChannelInput.value + '</div>' +
        '<div name="channelId" class="col-sm-5">' + idChannelInput.value + '</div>' +
        '<div name="channelBtn" class="col-sm-2"><button id="removeChannelBtn' + idChannelInput.value +
        '" type="button" data-toggle="tooltip" data-placement="top" title="Удалить паттерн" class="btn btn-editor" style="background-color:transparent;"><i class="fas fa-times"></i></button></div></div>';

    var lastLi = channelList.children[channelList.children.length - 1];
    channelList.insertBefore(li, lastLi);
    $("#removeChannelBtn" + idChannelInput.value)[0].addEventListener("click", onRemoveChannelBtnClick);

    var channels = getCookie("channels");
    if (channels != "") {
        channels = JSON.parse(channels);
    } else {
        channels = [];
    }
    channels.push({
        id: idChannelInput.value,
        name: nameChannelInput.value
    });
    channels = JSON.stringify(channels);
    setCookie("channels", channels, 1);
    $('[data-toggle="tooltip"]').tooltip();
    nameChannelInput.value = "";
    idChannelInput.value = "";
    scrollChannel.scrollTop = scrollChannel.scrollHeight;
}

function onCancelAddChannelBtn() {
    insertChannel.classList.add("display-none");
    addChanelBtn.classList.remove("display-none");
    cancelAddChanelBtn.classList.add("display-none");
}

function onRemoveChannelBtnClick() {
    var id = this.id;
    id = id.substr(16);
    $("#idchannel" + id)[0].classList.add("display-none");
    setTimeout(function (id) {
        $(id)[0].remove();
    }, 100, "#idchannel" + id);
    var channels = getCookie("channels");
    if (channels != "") {
        channels = JSON.parse(channels);
        setCookie("channels", channels, 1);
        for (var i = 0; i < channels.length; i++) {
            if (channels[i].id == id) {
                channels.splice(i, 1);
                break;
            }
        }
        channels = JSON.stringify(channels);
        setCookie("channels", channels, 1);
    }
}

function toEditParameter() {
    if (!editBtnDisabled) {
        return;
    }
    editBtnDisabled = false;
    var height = $('#scrollResult')[0].style.height;
    scrollChannel.style = "height:" + (height.substr(0, height.length - 2) - 30) + "px;";
    startAnalyzeBtn.classList.remove("display-none");
    addChanelBtn.classList.remove("display-none");
    editParametersBtn.classList.add("display-none");

    var channelBtn = $("[name*='channelBtn']");
    for (var i = 0; i < channelBtn.length; i++) {
        channelBtn[i].classList.remove("display-none");
    }

    var channelId = $("[name*='channelId']");
    for (var i = 0; i < channelBtn.length; i++) {
        channelId[i].classList.add("col-sm-5");
        channelId[i].classList.remove("col-sm-7");
    }
    patternPanel.classList.remove("display-none");
    channelPanel.classList.add("col");
    channelPanel.classList.remove("col-sm-5");
    resultPanel.classList.add("display-none");
    enableBtn(editParametersBtn);
}

function onEditParametersBtnClick() {

    this.blur();
    if (!subAnalyze) {
        editBtnDisabled = true;
        toEditParameter();
        return;
    }
    disableBtn(editParametersBtn);
    editBtnDisabled = true;
    stopAnalyzeBtn.click();
}






startAnalyzeBtn.addEventListener("click", onStartAnalyzeBtnClick);
window.addEventListener("load", onStartPage);
addChanelBtn.addEventListener("click", onAddChanelBtnClick);
insertChannelBtn.addEventListener("click", onInsertChannelBtnClick);
stopAnalyzeBtn.addEventListener("click", onStopAnalyzeBtnClick);
editParametersBtn.addEventListener("click", onEditParametersBtnClick);
pauseAnalyzeBtn.addEventListener("click", onPauseAnalyzeBtnClick);
continueBtn.addEventListener("click", onContinueBtnClick);
startAgainBtn.addEventListener("click", onStartAgainBtnClick);
cancelAddChannelBtn.addEventListener("click", onCancelAddChannelBtn);