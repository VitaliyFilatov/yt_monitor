var patternPanel = $("#patternPanel")[0];

function getAllPatterns()
{
    $.ajax({
        url: "getAllPatterns",
        type: "GET",
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            data = JSON.parse(data);
            patterns = data;
            //console.log("success: " + data.patternName);
            for (var i = 0; i < data.length; i++) {
                var li = document.createElement("li");
                li.classList.add("list-group-item");
                li.id = "id" + data[i].id;
                li.innerHTML = data[i].name;
                patternList.appendChild(li);
                $("#id" + data[i].id)[0].addEventListener("click", onPatternClick);
            }
            var pattern = getCookie("pattern");
            if (pattern != "") {
                $("#" + pattern)[0].click();
            }
        }
    });
}