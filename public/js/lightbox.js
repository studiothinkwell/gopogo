var divId;
var baseUrl;
var arrAccount = new Array('.clsSignInEmail','.clsSignInPwd','.clsSignUpEmail','.clsSignUpPwd','.clsSignUpRePwd','.clsForgotEmail','clsForgotPass');

var loginModalData = '';

$(document).ready(function()
{
    $().addResize();
    $().addScroll();

    $(".clsSaveProfile").click(function() {
        var txtEdtUsrName = $("<div class='heading-txt clsPUserName'>'"+$(".clsPUserName").val()+"'</div>");
        var txtEdtUsrDesc = $("<div class='clsPUserDesc'>'"+y+"'</div>");
        $(".clsPUserName").html(txtEdtUsrName);
        $(".clsPUserDesc").html(txtEdtUsrDesc);
        $(".clsProAction").html("<input type='button' class='clsSaveProfile' name='' value='Save'/>");
    });

    $(".clsSignIn").click(function(){
        $().addOverlay();
        $('#modalPopup').css({
            display:'block'
        });
        $().addSignInEvent();
    });

    $(".clsSignUp").click(function(){
        $().addOverlay();
        $('#modalPopup').css({
            display:'block'
        });
        $().addSignUpEvent();
    });

});

$.fn.addSignInEvent = function(){       
    
    if(modalData.user.loginmodalbox){
         $().setLoginEvent(modalData.user.loginmodalbox);
    }
    else{
        $.ajax({
            url: app.gopogo.signinmodalbox_url,
            type: 'GET',
            dataType: 'html',
            async: false,
            error: function(resp){},
            success: function(resp){
                if(resp) {
                    $().setLoginEvent(resp);
                }
            },
            complete: function(resp){}
        });
    }
}

$.fn.setLoginEvent = function(data){

    $().clearMessgae();
    $().clearModalBox();

    $('#modalPopup').html(data);

    $().makeCenter('#modalPopup','#loginBox');
        $(".login-close").click(function(){
        $().finish();
    });

    $(".clsForgot").click(function(){
        $().addForgotEvent();
    });

    $(".clsSignUp").click(function(){
        $().addSignUpEvent();
    });

    $(".clsLoginSubmitBox").click(function(){
        $().doLogin();
    });

    $().formEnterKey('loginBoxForm',$().doLogin);

    modalData.user.loginmodalbox = data;
}

$.fn.addForgotEvent = function(){

    if(modalData.user.forgotmodalbox){
        $().setForgotEvent(modalData.user.forgotmodalbox);
    }
    else{
        $.ajax({
            url: app.gopogo.forgotmodalbox_url,
            type: 'GET',
            dataType: 'html',
            async: false,
            error: function(resp){},
            success: function(resp){
                if(resp) {
                    $().setForgotEvent(resp);
                }
            },
            complete: function(resp){}
        });
    }
}

$.fn.setForgotEvent = function(data){
    
    $().clearMessgae();
    $().clearModalBox();

    $('#modalPopup').html(data);
    $().makeCenter('#modalPopup','#forgotBox');

    timeStamp = new Date().getTime();
    var imgSrc = app.gopogo.forgotcaptcha_url + '?time=' + timeStamp;
    $('#imageCaptcha').attr('src',imgSrc);
    
    $().setdefaultval();
    $("#email").focus();
    $(".clsForgotEmail").focus();

    $(".clsForgotClose").click(function(){
        $().finish();
    });

    $(".clsForgotSubmitBox").click(function(){
        $().doForgot();
    });

    $().formEnterKey('forgotBoxForm',$().doForgot);

    modalData.user.forgotmodalbox = data;
}

$.fn.addSignUpEvent = function(){
    
    if(modalData.user.signupmodalbox){
        $().setSignUpEvent(modalData.user.signupmodalbox);
    }
    else {

            $.ajax({
                url: app.gopogo.signupmodalbox_url,
                type: 'GET',
                dataType: 'html',
                async: false,
                error: function(resp){},
                success: function(resp){
                    if(resp) {                        
                        $().setSignUpEvent(resp);
                    }
                },
                complete: function(resp){}
            });
    }
}

$.fn.setSignUpEvent = function(data){
    $().clearMessgae();
    $().clearModalBox();

    $('#modalPopup').html(data);
    $().makeCenter('#modalPopup','#signupBox');

    $(".clsLogin").click(function(){
        $().addSignInEvent();
    });

    $().setdefaultval();
    $(".clsSignUpEmail").focus();
    $(".fb_button_text").text('');

    $(".create-ac-close").click(function(){
        $().finish();
    });
    $(".clsSignUpSubmit").click(function(){
        $().doSignup();
    });
    $().formEnterKey('signupBoxForm',$().doSignup);
    modalData.user.signupmodalbox = data;
}

$.fn.clearMessgae = function() {
    $().closeSuccessTooltip();
    $().closeErrorTooltip();
}

$.fn.addResize = function() {
    $(window).resize(function() {

        var arrPageSizes = $().getPageSize();
        // Style overlay and show it
        $('#overlay').css({
            width:		arrPageSizes[0],
            height:		arrPageSizes[1]
        });
        // Get page scroll
        var arrPageScroll = $().getPageScroll();

        $(divId).css({
            top:	parseInt(((arrPageScroll[1]) + (arrPageSizes[3]/2) - (($(divId).height()) / 2))),
            left:	parseInt(((arrPageScroll[0]) + (arrPageSizes[2]/2) - (($(divId).width()) / 2)))
        });
    });
}

$.fn.addScroll = function() {

    $(window).scroll(function() {
        var arrPageSizes = $().getPageSize();

        $('#overlay').css({
            width:		arrPageSizes[0],
            height:		arrPageSizes[1]
        });

        var arrPageScroll = $().getPageScroll();

        $(divId).css({
            top:	Math.round(((arrPageScroll[1]) + (arrPageSizes[3]/2) - (($(divId).height()) / 2))),
            left:	Math.round(((arrPageScroll[0]) + (arrPageSizes[2]/2) - (($(divId).width()) / 2)))
        });
    });
}

$.fn.addModalWindow = function(objId) {

    $().debugLog('addModalWindow');
    $().debugLog(objId);

    divId = objId;

    if( $("#overlay").length <= 0 )
    {
        $('embed, object, select').css({
            'visibility' : 'hidden'
        });
        $('body').append('<div id="overlay"></div>');
    }
    $(objId).css({
        display:''
    });

    var arrPageSizes = $().getPageSize();

    // Style overlay and show it
    $('#overlay').css({
        backgroundColor:    '#000',
        opacity:             0.5,
        width:               arrPageSizes[0],
        height:              arrPageSizes[1]
    }).fadeIn();


    // Calculate top and left offset for the jquery-lightbox div object and show it
    $(objId).css({
        top:    parseInt((arrPageSizes[3]/2) - (($(objId).height()) / 2)),
        left:   parseInt((arrPageSizes[2]/2) - (($(objId).width()) / 2))
    }).show();

    // Assigning click events in elements to close overlay
    $('#overlay').click(function() {
        $().finish();
    });

    $().enableKeyboardNavigation();
}

$.fn.addOverlay = function(){

    if( $("#overlay").length <= 0 ) {
        $('embed, object, select').css({
            'visibility' : 'hidden'
        });
        $('body').append('<div id="overlay"></div>');
    }

    var arrPageSizes = $().getPageSize();

    // Style overlay and show it
    $('#overlay').css({
        backgroundColor:    '#000',
        opacity:             0.5,
        width:               arrPageSizes[0],
        height:              arrPageSizes[1]
    }).fadeIn();

    $('#overlay').click(function() {
        $().finish();
    });

    $().enableKeyboardNavigation();
}

$.fn.makeCenter = function(outerBox, innerBox){

    var arrPageSizes = $().getPageSize();

    $(outerBox).css({
        height:	$(innerBox).height(),
        width:	$(innerBox).width()
    });

    $(outerBox).css({
        top:    parseInt((arrPageSizes[3]/2) - (($(outerBox).height()) / 2)),
        left:   parseInt((arrPageSizes[2]/2) - (($(outerBox).width()) / 2))
    });

}

$.fn.displayModalBox = function(mainBox, titleBox, container ) {
    $().addModalWindow(mainBox);
}

$.fn.enableKeyboardNavigation = function() {
    $(document).keydown(function(objEvent) {
        $().keyboardAction(objEvent);
    });
}

$.fn.enableLoginBox = function() {

    if($("#toggleForgot").css('display')== 'block'){
        $("#toggleLogin").css({
            display:''
        });
    }
}

$.fn.keyboardAction = function(objEvent) {
    var escapeKey = 27;

    if ( objEvent == null ) {
        keycode = event.keyCode;
    } else {
        keycode = objEvent.keyCode;
    }
    // Get the key in lower case form
    key = String.fromCharCode(keycode).toLowerCase();

    // Verify the keys to close the ligthBox
    if ( ( keycode == escapeKey ) ) {
        $().finish();
    }
}

$.fn.finish = function() {
    $().clearModalBox();
    $('#overlay').fadeOut(function() {
        $('#overlay').remove();
    });
    $('embed, object, select').css({
        'visibility' : 'visible'
    });
}

$.fn.clearModalBox = function() {
    $('#modalPopup').empty();
}

$.fn.getPageScroll = function() {
    var xScroll, yScroll;
    if (self.pageYOffset) {
        yScroll = self.pageYOffset;
        xScroll = self.pageXOffset;
    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
        yScroll = document.documentElement.scrollTop;
        xScroll = document.documentElement.scrollLeft;
    } else if (document.body) {// all other Explorers
        yScroll = document.body.scrollTop;
        xScroll = document.body.scrollLeft;
    }
    arrayPageScroll = new Array(xScroll,yScroll);
    return arrayPageScroll;
};

$.fn.getPageSize = function() {
    var jqueryPageSize = new Array($(document).width(),$(document).height(), $(window).width(), $(window).height());
    return jqueryPageSize;
};

$.fn.setdefaultval = function() {
    for(i=0;i<7;i++) {
        $(arrAccount[i]).val("");
    }
    $(".clsSignInEmail").val("Email Address");
    $().removeTextColor(".clsSignInEmail");
    $(".clsSignUpEmail").val("Email Address");
    $().removeTextColor(".clsSignUpEmail");
    $(".clsForgotEmail").val("Email Address");
    $().removeTextColor(".clsForgotEmail");
    $(".clsForgotPass").val("Characters");
    $().removeTextColor(".clsForgotPass");
    $(".clsSignInPwdOld").removeAttr('style');
    $(".clsSignInPwdOld").val('Password');
    $(".clsSignInPwd").attr('style','display:none');
    $(".clsSignUpPwdOld").removeAttr('style');
    $(".clsSignUpPwdOld").val('Password');
    $(".clsSignUpPwd").attr('style','display:none');
    $(".clsSignUpRePwdOld").removeAttr('style');
    $(".clsSignUpRePwdOld").val('Re-Type Password');
    $(".clsSignUpRePwd").attr('style','display:none');
}

$.fn.setTextColor = function(id) {
    $(id).attr('style','color:#000000');
}

$.fn.removeTextColor = function(id) {
    $(id).attr('style','color:#A9A9A9');
}
