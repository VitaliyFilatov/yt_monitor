var patternList = $("#patternList")[0];
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
var resultList = $("#resultList")[0];
var authContainer = $("#authContainer")[0];
var infoWork = $("#infoWork")[0];
var infoDone = $("#infoDone")[0];
var channelList = $("#channelList")[0];
var scrollResult = $("#scrollResult")[0];


var idPatternActive="";

var lastVideoId;

var subAnalyze;

function onStartPage()
{
    $.ajax({
        url: "authorize?XDEBUG_SESSION_START=ECLIPSE_DBGP",
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
                    url: "getAllPatterns?XDEBUG_SESSION_START=ECLIPSE_DBGP",
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
                    }
                });
            }
//            data = JSON.parse(data);
//            patterns = data;
//            //console.log("success: " + data.patternName);
//            for(var i = 0; i < data.length; i++)
//            {
//                var li = document.createElement("li");
//                li.classList.add("list-group-item");
//                li.id = "id" + data[i].id;
//                li.innerHTML = data[i].name;
//                patternList.appendChild(li);
//                $("#id" + data[i].id)[0].addEventListener("click", onPatternClick);
//            }
        }
    });
    
//    $.ajax({
//        url: "getAllPatterns?XDEBUG_SESSION_START=ECLIPSE_DBGP",
//        type: "GET",
//        error: function(jqXHR, textStatus, errorThrown )
//        {
//            console.log("error: " + errorThrown);
//        },
//        success: function(data, textStatus, jqXHR )
//        {
//            data = JSON.parse(data);
//            patterns = data;
//            //console.log("success: " + data.patternName);
//            for(var i = 0; i < data.length; i++)
//            {
//                var li = document.createElement("li");
//                li.classList.add("list-group-item");
//                li.id = "id" + data[i].id;
//                li.innerHTML = data[i].name;
//                patternList.appendChild(li);
//                $("#id" + data[i].id)[0].addEventListener("click", onPatternClick);
//            }
//        }
//    });
}


function onStartAnalyzeBtnClick()
{
    while(resultList.children.length)
    {
        resultList.children[0].remove();
    }
    insertChannel.classList.add("display-none");
    
    var channelBtn = $("[name*='channelBtn']");
    for(var i=0;i<channelBtn.length;i++)
    {
        channelBtn[i].classList.add("display-none");
    }
    
    var channelId = $("[name*='channelId']");
    for(var i=0;i<channelBtn.length;i++)
    {
        channelId[i].classList.remove("col-sm-5");
        channelId[i].classList.add("col-sm-7");
    }
    
    patternPanel.classList.add("display-none");
    channelPanel.classList.remove("col");
    channelPanel.classList.add("col-sm-5");
    resultPanel.classList.remove("display-none");
    stopAnalyzeBtn.classList.remove("display-none");
    pauseAnalyzeBtn.classList.remove("display-none");
    
    startAnalyzeBtn.classList.add("display-none");
    addChanelBtn.classList.add("display-none");
    lastVideoId="";
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
    
    var sessionid = document.cookie.substr(8);
    if(sessionid.indexOf(";") >= 0)
    {
        sessionid = sessionid.substr(0, sessionid.indexOf(";")); 
    }
    
    $.ajax({
        url: "analyzeChannels?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {channelIds : channelIds,
               patternId : idPatternActive.substr(2),
               sessionid : sessionid},
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
            subAnalyze = false;
            infoWork.classList.add("display-none");
            infoDone.classList.remove("display-none");
            lastVideoId="";
//            if(data.return_type == 1)
//            {
//                authLink.parentElement.classList.remove("display-none");
//                authLink.href = data.result;
//            }
//            subAnalyze = false;
//            console.log("data: " + data);
        }
    });
    
    getSubResultAnalyze();
}

function getSubResultAnalyze()
{
    var sessionid = document.cookie.substr(8);
    if(sessionid.indexOf(";") >= 0)
    {
        sessionid = sessionid.substr(0, sessionid.indexOf(";")); 
    }
    
    $.ajax({
        url: "getSubResultResAnalyze?XDEBUG_SESSION_START=ECLIPSE_DBGP",
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
            
            if(lastVideoId == data.substr(0,11) || data == "????????????????")
            {
            }
            else
            {
                var videoId = data.substr(0, 11);
                var similarity = data.substr(11, 5)
                if(similarity == "nosub")
                {
                    similarity = "нет субтитров";
                }
                var li = document.createElement("li");
                li.classList.add("list-group-item");
                li.innerHTML = '<div class="row"><div class="col-sm-7">' + videoId + 
                    '</div><div class="col-sm-5">' + data.substr(11, 5) + '</div>'; 
                resultList.appendChild(li);
                scrollResult.scrollTop = scrollResult.scrollHeight
            }
            

            
            lastVideoId = data.substr(0,11);
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
    stopAnalyzeBtn.classList.add("display-none");
    pauseAnalyzeBtn.classList.add("display-none");
    
    startAnalyzeBtn.classList.remove("display-none");
    addChanelBtn.classList.remove("display-none");
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
}

function onRemoveChannelBtnClick()
{
    var id = this.id;
    id = id.substr(16);
    $("#idchannel" + id)[0].remove();
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
        this.classList.add("active");
        if(idPatternActive != "")
        {
            $("#" + idPatternActive)[0].classList.remove("active");
        }
        idPatternActive = this.id; 
    }
}

startAnalyzeBtn.addEventListener("click", onStartAnalyzeBtnClick);
window.addEventListener("load", onStartPage);
addChanelBtn.addEventListener("click", onAddChanelBtnClick);
insertChannelBtn.addEventListener("click", onInsertChannelBtnClick);
stopAnalyzeBtn.addEventListener("click", onStopAnalyzeBtnClick);