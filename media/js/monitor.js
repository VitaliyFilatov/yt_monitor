var startMonitorBtn = $("#startMonitorBtn")[0];
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
var resultList = $("#resultList")[0];
var infoWork = $("#infoWork")[0];
var infoDone = $("#infoDone")[0];
var continueBtn = $("#continueBtn")[0];
var startAgainBtn = $("#startAgainBtn")[0];
var editParametersBtn = $("#editParametersBtn")[0];
var reload = $("#reload")[0];

var monitorStep=15000;


var lastVideoId = "";

var subAnalyze;


function monitorChannels(channelIds, patternId, sessionId, lastVideoId)
{
    $.ajax({
        url: "checkLastVideos?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {channelIds : channelIds,
               patternId : patternId,
               sessionid : sessionId,
               lastVideoId : lastVideoId},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
            subAnalyze = false;
            infoWork.classList.add("display-none");
            infoDone.classList.remove("display-none");
        },
        success: function(data, textStatus, jqXHR )
        {
            data = JSON.parse(data);
            if(data.return_type == 1)
            {
                authContainer.innerHTML = data.result;
                $("#authLink")[0].click();
            }
            else
            {
                console.log(data);
                data = data.result;
                if(data != null)
                {
                    for(var i=0;i<data.length;i++)
                    {
                        var videoId = data[i].videoid;
                        var similarity = data[i].sim
                        if($("#id" + videoId)[0] == undefined)
                        {
                            addResult(videoId, similarity);
                            if(i == data.length - 1)
                            {
                                lastVideoId = videoId;
                            } 
                        }
                    }
                    $('[data-toggle="tooltip"]').tooltip();
                }
                //after.setTime(after.getTime() + monitorStep);
                setTimeout(monitorChannels, monitorStep, channelIds, patternId, sessionId, lastVideoId);
            }
        }
    });
}

function onStartMonitorBtnClick()
{
    //clear result panel from old record
    while(resultList.children.length)
    {
        resultList.children[0].remove();
    }
    //hide insert channel
    insertChannel.classList.add("display-none");
    
    //hide remove channel button
    var channelBtn = $("[name*='channelBtn']");
    for(var i=0;i<channelBtn.length;i++)
    {
        channelBtn[i].classList.add("display-none");
    }
    
    //wide id chanel in channel list
    var channelId = $("[name*='channelId']");
    for(var i=0;i<channelBtn.length;i++)
    {
        channelId[i].classList.remove("col-sm-5");
        channelId[i].classList.add("col-sm-7");
    }
    
    //hide pattern panel, wide channel panel and show result panel
    patternPanel.classList.add("display-none");
    channelPanel.classList.remove("col");
    channelPanel.classList.add("col-sm-5");
    resultPanel.classList.remove("display-none");
    
    //hide start monitoring button
    startMonitorBtn.classList.add("display-none");
    
    //show pause button, hide start again button, hide button of add channel
    //pauseAnalyzeBtn.classList.remove("display-none");
    //startAgainBtn.classList.add("display-none");
    addChanelBtn.classList.add("display-none");
    //show button of retur to edit parameters of monitoring
    editParametersBtn.classList.remove("display-none");
    //hide and show needed info
    infoWork.classList.remove("display-none");
    infoDone.classList.add("display-none");
    continueBtn.classList.add("display-none");
    stopAnalyzeBtn.classList.remove("display-none");
    subAnalyze=true;
    var rows = channelList.children;
    var channelIds=[];
    for(var i=0; i < rows.length - 1; i++)
    {
        if(rows[i].id != "unusedChannel")
        {
            channelIds.push(rows[i].id.substr(9));
        }
    }
    
    var sessionid = getSessionId();
    
    lastVideoId = "";
    
    monitorChannels(channelIds, idPatternActive.substr(2), sessionid, lastVideoId);
}


function onStopAnalyzeBtnClick()
{
    continueBtn.classList.remove("display-none");
    stopAnalyzeBtn.classList.add("display-none");
    infoWork.classList.add("display-none");
    infoDone.classList.remove("display-none");
    stopProcess();
}

function onPauseAnalyzeBtnClick()
{
    continueBtn.classList.remove("display-none");
    pauseAnalyzeBtn.classList.add("display-none");
    var sessionid = getSessionId()
    $.ajax({
        url: "pauseAnalyze",
        type: "POST",
        data: {sessionid : sessionid},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
        }
    });
}

function onContinueBtnClick()
{
    continueBtn.classList.add("display-none");
    stopAnalyzeBtn.classList.remove("display-none");
    var sessionid = getSessionId();
    subAnalyze = true;
    infoWork.classList.remove("display-none");
    infoDone.classList.add("display-none");
    subAnalyze=true;
    var rows = channelList.children;
    var channelIds=[];
    for(var i=0; i < rows.length - 1; i++)
    {
        if(rows[i].id != "unusedChannel")
        {
            channelIds.push(rows[i].id.substr(9));
        }
    }
    
    var sessionid = getSessionId();
    
    monitorChannels(channelIds, idPatternActive.substr(2), sessionid, lastVideoId);
}

function onStartAgainBtnClick()
{
    pauseAnalyzeBtn.classList.remove("display-none");
    startAgainBtn.classList.add("display-none");
    
    onStartAnalyzeBtnClick();
}

function onAddChanelBtnClick()
{
    nameChannelInput.value="";
    idChannelInput.value="";
    insertChannel.classList.remove("display-none");
}

function onInsertChannelBtnClick()
{
    insertChannel.classList.add("display-none");
    if(nameChannelInput.value == "" || idChannelInput.value == "")
    {
        return;
    }
    if($("#removeChannelBtn" + idChannelInput.value)[0] !== undefined)
    {
        alert("Видео с таким id канала уже добавлено");
        return;
    }
    var li = document.createElement("li");
    li.classList.add("list-group-item");
    li.id = "idchannel" + idChannelInput.value;
    li.innerHTML = '<div name="channelName" class="row"><div class="col-sm-5">'+nameChannelInput.value+'</div>'+
                   '<div name="channelId" class="col-sm-5">'+idChannelInput.value+'</div>'+
                   '<div name="channelBtn" class="col-sm-2"><button id="removeChannelBtn'+idChannelInput.value+'" type="button" class="btn" style="background-color:transparent">'+
                   '<img src="media/png/glyphicons-208-remove.png" width="20" /></button></div></div>';
    
    var lastLi = channelList.children[channelList.children.length - 1];
    channelList.insertBefore(li, lastLi);
    $("#removeChannelBtn" + idChannelInput.value)[0].addEventListener("click",onRemoveChannelBtnClick);
    
    
    var channels = getCookie("channelsMonitor");
    if(channels != "")
    {
       channels = JSON.parse(channels); 
    }
    else
    {
        channels = [];
    }
    channels.push({id:idChannelInput.value, name : nameChannelInput.value});
    channels = JSON.stringify(channels);
    setCookie("channelsMonitor", channels, 1);
}

function onRemoveChannelBtnClick()
{
    var id = this.id;
    id = id.substr(16);
    $("#idchannel" + id)[0].remove();
    
    var channels = getCookie("channelsMonitor");
    if(channels != "")
    {
        channels = JSON.parse(channels);
        setCookie("channelsMonitor", channels, 1);
        for(var i=0;i<channels.length;i++)
        {
            if(channels[i].id == id)
            {
                channels.splice(id, 1);
                break;
            }
        }
        channels = JSON.stringify(channels);
        setCookie("channelsMonitor", channels, 1);
    }
}

function onEditParametersBtnClick()
{
    startMonitorBtn.classList.remove("display-none");
    addChanelBtn.classList.remove("display-none");
    editParametersBtn.classList.add("display-none");
    
    var channelBtn = $("[name*='channelBtn']");
    for(var i=0;i<channelBtn.length;i++)
    {
        channelBtn[i].classList.remove("display-none");
    }
    
    var channelId = $("[name*='channelId']");
    for(var i=0;i<channelBtn.length;i++)
    {
        channelId[i].classList.add("col-sm-5");
        channelId[i].classList.remove("col-sm-7");
    }
    patternPanel.classList.remove("display-none");
    channelPanel.classList.add("col");
    channelPanel.classList.remove("col-sm-5");
    resultPanel.classList.add("display-none");
    
    stopAnalyzeBtn.click();
}

startMonitorBtn.addEventListener("click", onStartMonitorBtnClick);
window.addEventListener("load", onStartPage);
addChanelBtn.addEventListener("click", onAddChanelBtnClick);
insertChannelBtn.addEventListener("click", onInsertChannelBtnClick);
stopAnalyzeBtn.addEventListener("click", onStopAnalyzeBtnClick);
editParametersBtn.addEventListener("click", onEditParametersBtnClick);
//pauseAnalyzeBtn.addEventListener("click", onPauseAnalyzeBtnClick);
continueBtn.addEventListener("click", onContinueBtnClick);
//startAgainBtn.addEventListener("click", onStartAgainBtnClick);