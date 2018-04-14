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

function onAddPatternBtnClick()
{
    mainPanel.classList.remove("w-50");
    mainPanel.classList.remove("mx-auto");
    editPanel.classList.remove("display-none");
}

function onCancelBtnClick()
{
    mainPanel.classList.add("w-50");
    mainPanel.classList.add("mx-auto");
    editPanel.classList.add("display-none");
}

function onSaveBtnClick()
{
    subResult = true;
    $.ajax({
        url: "createPattern?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {patternName : patternNameInput.value,
               videoIds : videoIdsInput.value},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            subResult = false;
            data = JSON.parse(data);
            //console.log("success: " + data.patternName);
            var li = document.createElement("li");
            li.classList.add("list-group-item");
            li.innerHTML = '<div style="display:none">' + data.patternId + '</div>' +
                '<div class="row"><div class="col-sm-10">' + data.patternName + 
                    '</div><div class="col-sm-2"><button id="editPatternBtn' + data.patternId +
                '" type="button" class="btn" style="background-color:transparent"><img src="media/png/glyphicons-208-remove.png" width="20"/></button></div></div>';
            patternList.appendChild(li);
        }
    });
    
    getSubResultSavePattern();
}

function getSubResultSavePattern()
{
    $.ajax({
        url: "getSubResult?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "GET",
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            if(saveProgressWidthClass != "")
            {
                saveProgress.classList.remove(saveProgressWidthClass); 
            }
            data = Math.round(data/5.0);
            data *= 5;
            saveProgress.classList.add("w-" + data);
            saveProgressWidthClass = "w-" + data;
            if(subResult)
            {
                getSubResultSavePattern();
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
    onAddPatternBtnClick();
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
                li.innerHTML = '<div class="row"><div class="col-sm-10">' + data[i].name + 
                        '</div><div class="col-sm-2"><button id="editPatternBtn' + data[i].id +
                    '" type="button" class="btn" style="background-color:transparent"><img src="media/png/glyphicons-236-pen.png" width="20"/></button></div></div>';
                patternList.appendChild(li);
                $("#editPatternBtn" + data[i].id)[0].addEventListener("click",onEditPatternBtnClick);
            }
        }
    });
}


addPatternBtn.addEventListener("click", onAddPatternBtnClick);
cancelBtn.addEventListener("click", onCancelBtnClick);
saveBtn.addEventListener("click", onSaveBtnClick);
window.addEventListener("load", onStartPage);


