function getSessionId()
{
    return $("#sessionid")[0].innerHTML;
}

function setSessionId(sessionid)
{
    $("#sessionid")[0].innerHTML = sessionid;
}

function stopProcess()
{
    var sessionid = getSessionId()
    $.ajax({
        url: "stopAnalyze?XDEBUG_SESSION_START=ECLIPSE_DBGP",
        type: "POST",
        data: {sessionid : sessionid},
        error: function(jqXHR, textStatus, errorThrown )
        {
            console.log("error: " + errorThrown);
        },
        success: function(data, textStatus, jqXHR )
        {
        }
    });
}

function disableBtn(btn)
{
    btn.classList.remove("btn-outline-primary");
    btn.classList.remove("btn-outline-darkblue");
    btn.classList.add("btn-darkblue");
    btn.classList.add("disabled");
}

function enableBtn(btn)
{
    btn.classList.add("btn-outline-primary");
    btn.classList.add("btn-outline-darkblue");
    btn.classList.remove("btn-darkblue");
    btn.classList.remove("disabled");
}

window.onbeforeunload = function(e) {
  stopProcess();
};


var navLinks = $('.nav-link');
for(var i=0;i<navLinks.length;i++)
{
   navLinks[i].addEventListener("click", stopProcess); 
}
