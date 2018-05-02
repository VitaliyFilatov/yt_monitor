var patternPanel = $("#patternPanel")[0];
var patternList = $("#patternList")[0];

var idPatternActive="";

function clearPatternPanel()
{
    while(patternList.children.length)
    {
        patternList.children[0].remove();
    }
}

function onPatternClick()
{
    if(this.id == idPatternActive)
    {
        setCookie("pattern"+window.location.pathname.substr(1), "", 10000);
        idPatternActive="";
        this.classList.remove("active");
    }
    else
    {
        setCookie("pattern"+window.location.pathname.substr(1), this.id, 10000);
        this.classList.add("active");
        if(idPatternActive != "")
        {
            $("#" + idPatternActive)[0].classList.remove("active");
        }
        idPatternActive = this.id; 
    }
}

function getAllPatterns() {
    $.ajax({
        url: "getAllPatterns",
        type: "GET",
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            data = JSON.parse(data);
            //console.log("success: " + data.patternName);
            clearPatternPanel();
            for (var i = 0; i < data.length; i++) {
                var li = document.createElement("li");
                li.classList.add("list-group-item");
                li.id = "id" + data[i].id;
                li.innerHTML = '<div class="row"><div class="col-sm-7">' + data[i].name + 
                    '</div><div id="threshold'+data[i].id+'" class="col-sm-5">'+data[i].threshold+'</div></div>';
                patternList.appendChild(li);
                $("#id" + data[i].id)[0].addEventListener("click", onPatternClick);
            }
            var pattern = getCookie("pattern"+window.location.pathname.substr(1));
            if (pattern != "") {
                if ($("#" + pattern)[0] !== undefined) {
                    $("#" + pattern)[0].click();
                }
            }
        }
    });
}