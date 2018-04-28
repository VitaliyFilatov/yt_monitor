var addPatternBtn = $( "#addPatternBtn" )[0];
var editPanel = $("#editPanel")[0];
var cancelBtn = $("#cancelBtn")[0];
var saveBtn = $("#saveBtn")[0];
var videoIdsInput = $("#videoIdsInput")[0];
var patternList = $("#patternList")[0];
var saveProgress = $("#saveProgress")[0];
var saveProgressWidthClass="";
var oldValue = 0;
var editedPattern = -1;

var subResult=false;

var inProcess = false;


function clearProgress()
{
    for(var i=0;i< 20; i++)
    {
       saveProgress.classList.remove("w-" + (i*5 +  5)); 
    }
}


function onCancelBtnClick()
{
    if(inProcess)
    {
        return;
    }
    else
    {
        saveProgress.parentElement.classList.add("display-none"); 
    }
}

function cancelSave()
{
    if(inProcess)
    {
        $("#reload")[0].click();
    }
    else
    {
        saveProgress.parentElement.classList.add("display-none"); 
    }
}

function disableSaveBtn()
{
    saveBtn.classList.remove("btn-outline-primary");
    saveBtn.classList.remove("btn-outline-darkblue");
    saveBtn.classList.add("btn-darkblue");
    saveBtn.classList.add("disabled");
}

function enableSaveBtn()
{
    saveBtn.classList.add("btn-outline-primary");
    saveBtn.classList.add("btn-outline-darkblue");
    saveBtn.classList.remove("btn-darkblue");
    saveBtn.classList.remove("disabled");
}

function onSaveBtnClick()
{
    if(inProcess)
    {
        return;
    }
    disableSaveBtn();
    setConfirmation();
    inProcess = true;
    var sessionid = $("#sessionid")[0].innerHTML;
    clearProgress();
    saveProgress.parentElement.classList.remove("display-none");
    subResult = true;
    $.ajax({
        url: "createPattern?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {patternId : idPatternActive,
               destrVideoIds : destrVideoIdsInput.value,
               nondestrVideoIds : nondestrVideoIdsInput.value,
               sessionid : sessionid},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
            subResult = false;
            oldValue = 0;
            saveProgress.parentElement.classList.add("display-none");
            editedPattern = -1;
            inProcess = false;
            enableSaveBtn();
        },
        success: function(data, textStatus, jqXHR )
        {
            inProcess = false;
            subResult = false;
            oldValue = 0;
            saveProgress.parentElement.classList.add("display-none");
            data = JSON.parse(data);
            if(data.type === 0)
            {
                alert("Субтитры для видео с id=" + data.result + "отсутствуют");
                enableSaveBtn();
                return;
            }
            data = data.result;
            console.log("data: " + data);
            enableSaveBtn();
        }
    });
    
    getSubResultSavePattern();
}

function getSubResultSavePattern()
{
    var sessionid = $("#sessionid")[0].innerHTML;
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

function hideConfirm()
{
    if(!inProcess)
    {
        $('#cancelBtn').confirmation('hide');
    }
}

function onStartPage()
{
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
    });
    getAllPatterns();
}


addPatternBtn.addEventListener("click", onAddPatternBtnClick);
cancelBtn.addEventListener("click", onCancelBtnClick);
$('#cancelBtn').on('canceled.bs.confirmation', cancelSave);
$('#cancelBtn').on('shown.bs.confirmation', hideConfirm);
saveBtn.addEventListener("click", onSaveBtnClick);
window.addEventListener("load", onStartPage);


