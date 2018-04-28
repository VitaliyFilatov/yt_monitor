var patternList = $("#patternList")[0];
var startMonitorBtn = $("#startMonitorBtn")[0];
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
var resultList = $("#resultList")[0];
var authContainer = $("#authContainer")[0];
var infoWork = $("#infoWork")[0];
var infoDone = $("#infoDone")[0];
var channelList = $("#channelList")[0];
var scrollResult = $("#scrollResult")[0];
var continueBtn = $("#continueBtn")[0];
var startAgainBtn = $("#startAgainBtn")[0];
var editParametersBtn = $("#editParametersBtn")[0];

var monitorStep=15000;

var idPatternActive="";

var lastVideoId = "";

var subAnalyze;

function getSessionId()
{
    return $("#sessionid")[0].innerHTML;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') {
            c = c.substring(1);
        }
        if (c.indexOf(name) == 0) {
            return c.substring(name.length, c.length);
        }
    }
    return "";
}


function initializeChannels()
{
    var channels = getCookie("channelsMonitor");
    if(channels == "")
    {
        return;
    }
    channels = JSON.parse(channels);
    
    for(var i=0;i<channels.length;i++)
    {
        var li = document.createElement("li");
        li.classList.add("list-group-item");
        li.id = "idchannel" + channels[i].id;
        li.innerHTML = '<div name="channelName" class="row"><div class="col-sm-5">'+channels[i].name+'</div>'+
                       '<div name="channelId" class="col-sm-5">'+channels[i].id+'</div>'+
                       '<div name="channelBtn" class="col-sm-2"><button id="removeChannelBtn'+channels[i].id+'" type="button" class="btn" style="background-color:transparent">'+
                       '<img src="media/png/glyphicons-208-remove.png" width="20" /></button></div></div>';

        var lastLi = channelList.children[channelList.children.length - 1];
        channelList.insertBefore(li, lastLi);
        $("#removeChannelBtn" + channels[i].id)[0].addEventListener("click",onRemoveChannelBtnClick);
    }
}

function onStartPage()
{
    initializeChannels();
    var height = document.documentElement.clientHeight;
    scrollResult.style="height:" + Math.floor(height*0.43) + "px;";
    $.ajax({
        url: "authorize",
        type: "GET",
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            if(data != true)
            {
                authContainer.innerHTML = data;
                $("#authLink")[0].click();
            }
            else
            {
                $.ajax({
                    url: "getAllPatterns",
                    type: "GET",
                    error: function(jqXHR, textStatus, errorThrown )
                    {
                        console.log("error: " + errorThrown);
                    },
                    success: function(data, textStatus, jqXHR )
                    {
                        data = JSON.parse(data);
                        patterns = data;
                        //console.log("success: " + data.patternName);
                        for(var i = 0; i < data.length; i++)
                        {
                            var li = document.createElement("li");
                            li.classList.add("list-group-item");
                            li.id = "id" + data[i].id;
                            li.innerHTML = data[i].name;
                            patternList.appendChild(li);
                            $("#id" + data[i].id)[0].addEventListener("click", onPatternClick);
                        }
                        
                        var pattern = getCookie("patternMonitor");
                        if(pattern != "")
                        {
                            $("#" + pattern)[0].click();
                        }
                    }
                });
            }
        }
    });
}


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
                            if(similarity == -1)
                            {
                                similarity = "нет субтитров";
                            }
                            var li = document.createElement("li");
                            li.classList.add("list-group-item");
                            li.id = "id" + videoId;
                            li.innerHTML = '<div class="row"><div class="col-sm-7">' + videoId + 
                                    '</div><div class="col-sm-5">' + similarity + '</div>'; 
                            resultList.appendChild(li);
                            scrollResult.scrollTop = scrollResult.scrollHeight
                            if(i == data.length - 1)
                            {
                                lastVideoId = videoId;
                            } 
                        }
                    }
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

function getSubResultAnalyze()
{
    var sessionid = getSessionId();
    
    $.ajax({
        url: "getSubResultResAnalyze",
        type: "POST",
        data: {lastVideoId : lastVideoId,
               sessionid : sessionid},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            console.log("subdata: "+data);
            data = JSON.parse(data);
            data = data.result
            if(data != null)
            {
                data = JSON.parse(data);
                var videoId = data.videoid;
                var similarity = data.sim
                if($("#id" + videoId)[0] == undefined)
                {
                    if(similarity == -1)
                    {
                        similarity = "нет субтитров";
                    }
                    var li = document.createElement("li");
                    li.classList.add("list-group-item");
                    li.id = "id" + videoId;
                    li.innerHTML = '<div class="row"><div class="col-sm-7">' + videoId + 
                            '</div><div class="col-sm-5">' + similarity + '</div>'; 
                    resultList.appendChild(li);
                    scrollResult.scrollTop = scrollResult.scrollHeight
                }
            }
            
            

            
            //lastVideoId = data.substr(0,11);
//            if(oldValue != 101)
//            {
//                if(saveProgressWidthClass != "")
//                {
//                    saveProgress.classList.remove(saveProgressWidthClass); 
//                }
//                data = Math.round(data/5.0);
//                data *= 5;
//                saveProgress.classList.add("w-" + data);
//                saveProgressWidthClass = "w-" + data;
//            }
            if(subAnalyze)
            {
                setTimeout(getSubResultAnalyze, 100);
            }
        }
    });
}

function onStopAnalyzeBtnClick()
{
//    pauseAnalyzeBtn.classList.add("display-none");
//    startAgainBtn.classList.remove("display-none");
    continueBtn.classList.remove("display-none");
    stopAnalyzeBtn.classList.add("display-none");
    infoWork.classList.add("display-none");
    infoDone.classList.remove("display-none");
    var sessionid = getSessionId()
    $.ajax({
        url: "stopAnalyze?XDEBUG_SESSION_START=ECLIPSE_DBGP",
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

function onPatternClick()
{
    if(this.id == idPatternActive)
    {
        idPatternActive="";
        this.classList.remove("active");
    }
    else
    {
        setCookie("patternMonitor", this.id, 1);
        this.classList.add("active");
        if(idPatternActive != "")
        {
            $("#" + idPatternActive)[0].classList.remove("active");
        }
        idPatternActive = this.id; 
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