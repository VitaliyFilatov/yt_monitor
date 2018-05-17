var resultList = $("#resultList")[0];
var scrollResult = $("#scrollResult")[0];

function getThreshold() {
    return $("#threshold" + idPatternActive.substr(2))[0].innerHTML;
}

function addResult(videoId, similarity, Kl, Kd, Kp, Kn, Risk) {
    var threshold = getThreshold();
    var indicator = "";
    var color = "";
    var title = "";
    var collapseData = "";
    if (similarity >= threshold) {
        indicator = '<button id="clpsBtn' + videoId + '" type="button" class="btn btn-collapse" style="background-color:transparent; color:#ff0000"><i class="fas fa-arrow-down"></i></button>';
        color = "#ff0000";
        title = "Порог деструктивности превышен";
        collapseData = '<div id="collapse' + videoId +
        '" class="collapse w-100">Коэффициент лайков: '+Kl+
        '</br>Коэффициент дизлайков: '+Kd+
        '</br>Коэффициент позитивных комментариев '+Kp+
        '</br>Коэффициент отрицательных комментариев '+Kn+
        '</br>Риск вовлечённости '+Risk+
        '</div>';
    } else {
        indicator = '<i class="fas fa-check"></i>';
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
        '</div><div class="col-sm-5"><div class="row"><div class="col-sm-9">' + similarity + '</div><div id="toggle'+videoId+'" data-toggle="tooltip" data-placement="left" title="' + title + '" class="col-sm-3" style="color:' + color + '">' + indicator + '</div></div></div>' + collapseData;
    resultList.appendChild(li);
    scrollResult.scrollTop = scrollResult.scrollHeight;
    var clpsBtn = $('#clpsBtn' + videoId)[0];
    if (clpsBtn != undefined) {
        $('#clpsBtn' + videoId)[0].addEventListener("click", onCollapseClick);
    }
    $('[data-toggle="tooltip"]').tooltip();
}


function getSubResultAnalyze() {
    var sessionid = getSessionId();

    $.ajax({
        url: "getSubResultResAnalyze",
        type: "POST",
        data: {
            sessionid: sessionid
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log("error: " + errorThrown);
        },
        success: function (data, textStatus, jqXHR) {
            console.log("subdata: " + data);
            data = JSON.parse(data);
            if (data != null) {
                $('#spinner')[0].classList.add("display-none");
                for (var i = 0; i < data.length; i++) {
                    var videoId = data[i].videoid;
                    var similarity = data[i].sim;
                    var threshold = getThreshold();
                    if(similarity >= threshold)
                    {
                    	var Kl = "невозможно определить";
                    	var Kdl = "невозможно определить";
                    	var Kp = "невозможно определить";
                    	var Kn = "невозможно определить";
                    	var Risk = "невозможно определить";
                    	if(data[i].view_count > 0)
                    	{
                    		Kl = data[i].like_count/data[i].view_count;
                    		Kdl = data[i].dislike_count/data[i].view_count;
                    		Kp = data[i].positive_count/data[i].view_count;
                    		Kn = data[i].negative_count/data[i].view_count;
                    	}
                    	if(data[i].followers_count > 0)
                    	{
                    		Risk = (2*data[i].like_count +
                    				data[i].positive_count +
                    				data[i].view_count)/data[i].followers_count;
                    	}
                        addResult(videoId, similarity, Kl, Kd, Kp, Kn, Risk);
                    }
                    else
                    {
                        addResult(videoId, similarity);
                    }
                }
                
            }
            if (subAnalyze) {
                setTimeout(getSubResultAnalyze, 100);
            }
        }
    });
}

function onCollapseClick() {
    this.blur();
    var classList = $('#collapse'+this.id.substr(7))[0].classList;
    if(classList.contains('show'))
    {
        $('#collapse'+this.id.substr(7))[0].classList.remove('show');
        $('#collapse'+this.id.substr(7))[0].classList.add('hide');
    }
    else
    {
        $('#collapse'+this.id.substr(7))[0].classList.add('show');
        $('#collapse'+this.id.substr(7))[0].classList.remove('hide');
    }
}