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
    patternPanel.classList.add("display-none");
    channelPanel.classList.remove("col");
    channelPanel.classList.add("col-sm-4");
    resultPanel.classList.remove("display-none");
    stopAnalyzeBtn.classList.remove("display-none");
    pauseAnalyzeBtn.classList.remove("display-none");
    
    startAnalyzeBtn.classList.add("display-none");
    addChanelBtn.classList.add("display-none");
    lastVideoId="";
    infoWork.classList.remove("display-none");
    infoDone.classList.add("display-none");
    subAnalyze=true;
    
    $.ajax({
        url: "analyzeChannel?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {channelId : 'UCqPKeDJ7fV0Nj0KrL1lGqaQ',
               patternId : '18'},
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
            subAnalyze = false;
            infoWork.classList.add("display-none");
            infoDone.classList.remove("display-none");
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
    $.ajax({
        url: "getSubResultResAnalyze?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {lastVideoId : lastVideoId},
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
    patternPanel.classList.remove("display-none");
    channelPanel.classList.add("col");
    channelPanel.classList.remove("col-sm-4");
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