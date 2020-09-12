//Notifications javascript
window.showNotifications = function(data)
{
    if(data.status && data.notify && data.notify.length > 0)
    {
        let status=data.status;
        let notify=data.notify;

        let errorText="";
        $(notify).each(function(index, text){

            errorText+="<span class='glyphicon glyphicon-hand-right'></span> "+text+"<br/>";
        });

        if($("#notifications"))
        {
            $("#notifications").remove();
        }

        let errorContainer="";

        errorContainer+='<div class="notifications" id="notifications">';
        errorContainer+='<div class="notifications-content alert alert-'+status+'">';
        errorContainer+='<button type="button" class="close notifications-close">&times;</button>';
        errorContainer+=errorText;
        errorContainer+='</div>';
        errorContainer+='</div>';

        $("body").append(errorContainer);
        $("#notifications").fadeIn(300);

        $("#notifications").find(".notifications-close").click(function(){
            $("#notifications").fadeOut(300, function(){
                $("#notifications").remove();
            });
        });

        let timeout = window.setTimeout(function(){

            $("#notifications").remove();

            window.clearTimeout(timeout);
        }, 10000);
    }
}

//Confirmation function
window.showConfirmation = function(confirmMsg, callBack, callBackArgs)
{
    if(confirmMsg == "")
    {
        confirmMsg="Are you sure you want to delete this record? Then confirm.";
    }

    let confirmBox="";

    confirmBox+='<div class="confirmation-box" id="confirmation-box">';
    confirmBox+='<div class="confirmation-box-inner">';
    confirmBox+='<div class="toast show confirmation-box-content" data-toggle="toast">';
    confirmBox+='<div class="toast-header">';
    confirmBox+='<strong class="mr-auto">Please confirm</strong>';
    confirmBox+='</div>';
    confirmBox+='<div class="toast-body">';

    confirmBox+='<p>'+confirmMsg+'</p>';
    confirmBox+='<button class="btn btn-danger btn-sm pull-right" onclick="return confirmationCallBack(\''+callBack+'\', \''+callBackArgs+'\');">Confirm</button>';
    confirmBox+='<button class="btn btn-info btn-sm pull-right" onclick="return cancelConfirmation();">Cancel</button>';
    confirmBox+='</div>';
    confirmBox+='</div>';
    confirmBox+='</div>';
    confirmBox+='</div>';

    //if confirm box exists then remove
    if($("body").find("#confirmation-box"))
    {
        $("body").find("#confirmation-box").remove();
    }

    //append new confirmation box to the body
    $("body").append(confirmBox);
}

window.cancelConfirmation = function()
{
    $("body").find("#confirmation-box").remove();
}

window.confirmationCallBack = function(callBack, callBackArgs)
{
    if(typeof window[callBack] === 'function'){

        window[callBack].apply(undefined, callBackArgs.split(","))
    }
    $("body").find("#confirmation-box").remove();
}

window.showPreloader = function(elem, posFixed)
{
    $(elem).append('<div class="preloader"><div class="preloader_icon"></div></div>');
    if(posFixed)
    {
        $(elem).find('.preloader').css("position", "fixed").css("top", "50%");
    }
    $('.preloader').fadeIn(300);
}

window.hidePreloader = function(elem)
{
    $(elem).find('.preloader').remove();
}
