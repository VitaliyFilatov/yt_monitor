var resultList = $("#resultList")[0];
var scrollResult = $("#scrollResult")[0];

function getThreshold() {
    return $("#threshold" + idPatternActive.substr(2))[0].innerHTML;
}

function addResult(videoId, similarity) {
    var threshold = getThreshold();
    var arrow = "";
    var color = "";
    var title = "";
    if (similarity >= threshold) {
        arrow = "arrow-up";
        color = "#ff0000";
        title = "Порог деструктивности превышен";
    } else {
        arrow = "arrow-down";
        color = "#00ff00";
        title = "Порог деструктивности не превышен";
    }
    if (similarity == -1) {
        similarity = "нет субтитров";
    }
    var li = document.createElement("li");
    li.classList.add("list-group-item");
    li.id = "id" + videoId;
    li.innerHTML = '<div class="row"><div class="col-sm-7">' + videoId +
        '</div><div class="col-sm-5"><div class="row"><div class="col-sm-9">' + similarity + '</div><div data-toggle="tooltip" data-placement="top" title="'+title+'" class="col-sm-3" style="color:' + color + '"><i class="fas fa-' + arrow + '"></i></div></div></div>';
    resultList.appendChild(li);
    scrollResult.scrollTop = scrollResult.scrollHeight
}


function getSubResultAnalyze() {
    var sessionid = getSessionId();

    $.ajax({
        url: "getSubResultResAnalyze",
        type: "POST",
        data: { sessionid: sessionid},
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            console.log("subdata: " + data);
            data = JSON.parse(data);
            if (data != null) {
                for (var i = 0; i < data.length; i++) {
                    var videoId = data[i].videoid;
                    var similarity = data[i].sim;
                    addResult(videoId, similarity);
                }
                $('[data-toggle="tooltip"]').tooltip();
            }
            if (subAnalyze) {
                setTimeout(getSubResultAnalyze, 100);
            }
        }
    });
}