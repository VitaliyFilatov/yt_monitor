var addPatternBtn = $( "#addPatternBtn" )[0];
var mainPanel = $("#mainPanel")[0];
var editPanel = $("#editPanel")[0];
var cancelBtn = $("#cancelBtn")[0];
var saveBtn = $("#saveBtn")[0];
var patternNameInput = $("#patternNameInput")[0];
var videoIdsInput = $("#videoIdsInput")[0];
var patternList = $("#patternList")[0];
var saveProgress = $("#saveProgress")[0];
var saveProgressWidthClass="";
var patterns;
var oldValue = 0;
var editedPattern = -1;

var subResult=false;

function searchPatternById(id)
{
    for(var i = 0; i < patterns.length; i++)
    {
        if(patterns[i].id == id)
        {
            return patterns[i];
        }
    }
    return false;
}

function clearProgress()
{
    for(var i=0;i< 20; i++)
    {
       saveProgress.classList.remove("w-" + (i*5 +  5)); 
    }
}

function showEditPanel()
{
    mainPanel.classList.remove("w-50");
    mainPanel.classList.remove("mx-auto");
    editPanel.classList.remove("display-none");
}

function onAddPatternBtnClick()
{
    showEditPanel();
    patternNameInput.value = "";
    videoIdsInput.value = "";
    editedPattern = -1;
}

function onCancelBtnClick()
{
    mainPanel.classList.add("w-50");
    mainPanel.classList.add("mx-auto");
    editPanel.classList.add("display-none");
    saveProgress.parentElement.classList.add("display-none");
}

function onSaveBtnClick()
{
    var sessionid = document.cookie.substr(8);
    if(sessionid.indexOf(";") >= 0)
    {
        sessionid = sessionid.substr(0, sessionid.indexOf(";")); 
    }
    clearProgress();
    saveProgress.parentElement.classList.remove("display-none");
    subResult = true;
    $.ajax({
        url: "createPattern?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {patternName : patternNameInput.value,
               videoIds : videoIdsInput.value,
               sessionid : sessionid},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
            subResult = false;
            oldValue = 0;
            saveProgress.parentElement.classList.add("display-none");
            editedPattern = -1;
        },
        success: function(data, textStatus, jqXHR )
        {
            subResult = false;
            oldValue = 0;
            saveProgress.parentElement.classList.add("display-none");
            data = JSON.parse(data);
            if(data.type === 0)
            {
                alert("Субтитры для видео с id=" + data.result + "отсутствуют");
                return;
            }
            data = data.result;
            //console.log("success: " + data.patternName);
            var li = document.createElement("li");
            li.classList.add("list-group-item");
            li.id = "id" + data.id;
            li.innerHTML = '<div class="row"><div class="col-sm-9">' + data.name + 
                    '</div><div class="col-sm-3"><button id="editPatternBtn' + data.id +
                '" type="button" class="btn" style="background-color:transparent"><img src="media/png/glyphicons-236-pen.png" width="20"/></button><button id="removePatternBtn' + data.id +
                '" type="button" class="btn" style="background-color:transparent"><img src="media/png/glyphicons-208-remove.png" width="20"/></button></div></div>';
            if(editedPattern != -1)
            {
                var el = $("#id" + editedPattern.id)[0];
                el.remove();
            }
            patternList.appendChild(li);
            $("#editPatternBtn" + data.id)[0].addEventListener("click",onEditPatternBtnClick);
            $("#removePatternBtn" + data.id)[0].addEventListener("click",onDeletePatternBtnClick);
            patterns.push(data);
        }
    });
    
    getSubResultSavePattern();
}

function getSubResultSavePattern()
{
    var sessionid = document.cookie.substr(8);
    if(sessionid.indexOf(";") >= 0)
    {
        sessionid = sessionid.substr(0, sessionid.indexOf(";")); 
    }
    $.ajax({
        url: "getSubResult?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {oldValue : oldValue,
               sessionid : sessionid},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            console.log("data: "+data);
            //oldValue = data;
            if(data != "")
            {
                if(saveProgressWidthClass != "")
                {
                    saveProgress.classList.remove(saveProgressWidthClass); 
                }
                data = Math.round(data/5.0);
                data *= 5;
                saveProgress.classList.add("w-" + data);
                saveProgressWidthClass = "w-" + data;
            }
            if(subResult)
            {
                setTimeout(getSubResultSavePattern, 500);
            }
        }
    });
}

function onEditPatternBtnClick()
{
    var id = this.id;
    id = id.substr(14);
    var pattern = searchPatternById(id);
    if(pattern === false)
    {
        alert("Паттерн не найден");
        return;
    }
    editedPattern = pattern;
    patternNameInput.value = pattern.name;
    var videos = pattern.video;
    videoIdsInput.value = "";
    for(var i = 0;i < videos.length; i++)
    {
        videoIdsInput.value += videos[i].videoid;
        if(i != videos.length - 1)
        {
            videoIdsInput.value += ",";
        }
    }
    showEditPanel();
}

function onDeletePatternBtnClick()
{
    var id = this.id;
    id = id.substr(16);
    $.ajax({
        url: "deletePattern?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {patternId : id},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            console.log("data: "+data);
            if(data == true)
            {
                var li = $("#id" + id)[0];
                li.remove();
            }
            else
            {
                alert("Невозможно удалить запись");
            }
        }
    });
}

function onStartPage()
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
                li.innerHTML = '<div class="row"><div class="col-sm-9">' + data[i].name + 
                        '</div><div class="col-sm-3"><button id="editPatternBtn' + data[i].id +
                    '" type="button" class="btn" style="background-color:transparent"><img src="media/png/glyphicons-236-pen.png" width="20"/></button><button id="removePatternBtn' + data[i].id +
                '" type="button" class="btn" style="background-color:transparent"><img src="media/png/glyphicons-208-remove.png" width="20"/></button></div></div>';
                patternList.appendChild(li);
                $("#editPatternBtn" + data[i].id)[0].addEventListener("click",onEditPatternBtnClick);
                $("#removePatternBtn" + data[i].id)[0].addEventListener("click",onDeletePatternBtnClick);
            }
        }
    });
}


addPatternBtn.addEventListener("click", onAddPatternBtnClick);
cancelBtn.addEventListener("click", onCancelBtnClick);
saveBtn.addEventListener("click", onSaveBtnClick);
window.addEventListener("load", onStartPage);


