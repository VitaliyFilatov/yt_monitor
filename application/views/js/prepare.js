var addPatternBtn = $( "#addPatternBtn" )[0];
var mainPanel = $("#mainPanel")[0];
var editPanel = $("#editPanel")[0];
var cancelBtn = $("#cancelBtn")[0];
var saveBtn = $("#saveBtn")[0];

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
    $.ajax({
        url: "createPattern",
        type: "POST",
        data: {id : "value"},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
            console.log("success: " + data);
        }
    });
}


addPatternBtn.addEventListener("click", onAddPatternBtnClick);
cancelBtn.addEventListener("click", onCancelBtnClick);
saveBtn.addEventListener("click", onSaveBtnClick);

