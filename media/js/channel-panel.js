var channelList = $("#channelList")[0];
var authContainer = $("#authContainer")[0];
var scrollChannel = $("#scrollChannel")[0];

function initializeChannels()
{
    var channels = getCookie("channels");
    if(channels == "")
    {
        return;
    }
    channels = JSON.parse(channels);
    
    for(var i=0;i<channels.length;i++)
    {
        var li = document.createElement("li");
        li.classList.add("list-group-item");
        li.id = "idchannel" + channels[i].id;
        li.innerHTML = '<div name="channelName" class="row"><div class="col-sm-5">'+channels[i].name+'</div>'+
                       '<div name="channelId" class="col-sm-5">'+channels[i].id+'</div>'+
                       '<div name="channelBtn" class="col-sm-2"><button id="removeChannelBtn' + channels[i].id +
                '" type="button" data-toggle="tooltip" data-placement="top" title="Удалить паттерн" class="btn btn-editor" style="background-color:transparent;"><i class="fas fa-times"></i></button></div></div>';

        var lastLi = channelList.children[channelList.children.length - 1];
        channelList.insertBefore(li, lastLi);
        $("#removeChannelBtn" + channels[i].id)[0].addEventListener("click",onRemoveChannelBtnClick);
    }
    $('[data-toggle="tooltip"]').tooltip();
    scrollChannel.scrollTop = scrollChannel.scrollHeight;
}

function onStartPage()
{
    initializeChannels();
    $('[data-toggle=confirmation]').confirmation({
        rootSelector: '[data-toggle=confirmation]',
    });
    var height = document.documentElement.clientHeight;
    scrollResult.style="height:" + Math.floor(height*0.43) + "px;";
    scrollChannel.style="height:" + (Math.floor(height*0.43)-30) + "px;";
    scrollPattern.style="max-height:" + (Math.floor(height*0.43)-30) + "px;";
    $.ajax({
        url: "authorize",
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
                getAllPatterns();
            }
        }
    });
}