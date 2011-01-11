
var divId;
var arrAccount = new Array('.clsSignInEmail','.clsSignInPwd','.clsSignUpEmail','.clsSignUpPwd','.clsSignUpRePwd','.clsForgotEmail');
$(document).ready(function(){

  $(".clsSignIn").click(function(){ 
        divId = "#loginBox";
        $(".errorMsg").text('');        
        $().enableLoginBox();        
        $().displayModalBox("#loginBox", ".create-ac-head", ".CLS_sign-in-centerbg" );      
        $().setdefaultval();
        $("#email").focus();
        $(".fb_button_text").text('');
    });

  $(".clsForgot").click(function(){
        divId = "#forgotBox";
        $("#loginBox").css({display:'none'}); 
        $("#toggleForgot").css({display:''});
        $(".errorMsg").text('');
        $().enableLoginBox();
        $().displayModalBox("#forgotBox", ".create-ac-head", ".CLS_sign-in-centerbg" );
        $().setdefaultval();
        $("#email").focus();       
    });

    $(".clsSignUp").click(function(){
        divId = "#signupBox";
        $().displayModalBox("#signupBox", ".create-ac-head", ".create-ac-centerbg" );
        $().setdefaultval();
        $(".clsSignUpEmail").focus();
        $(".fb_button_text").text('');
    });

    $(".create-ac-close").click(function(){
        $().finish();
    });

     $(".login-close").click(function(){
        $().finish();
    });
    
    $().addResize();
    $().addScroll();    
    
});

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
        $('embed, object, select').css({ 'visibility' : 'hidden' });
        $('body').append('<div id="overlay"></div>');
     }
    $(objId).css({display:''});

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
        left:    parseInt((arrPageSizes[2]/2) - (($(objId).width()) / 2))
    }).show();

    // Assigning click events in elements to close overlay
    $('#overlay').click(function() {         
        $().finish();
    });

    $().enableKeyboardNavigation();    
}

$.fn.displayModalBox = function(mainBox, titleBox, container ) {
       
        $(mainBox).draggable();

        $(titleBox).mouseover(function(){
            $(mainBox).draggable('enable');
           });

        $(container).mouseover(function(){
                $(mainBox).draggable('disable');
            });

        $().addModalWindow(mainBox);
}



$.fn.enableKeyboardNavigation = function() {
    $(document).keydown(function(objEvent) {
        $().keyboardAction(objEvent);
    });
}

$.fn.enableLoginBox = function() {

     if($("#toggleForgot").css('display')== 'block'){           
           $("#toggleLogin").css({display:''});
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
    
    $(divId).css({display:'none'});
    $('#overlay').fadeOut(function() { $('#overlay').remove(); });
    // Show some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
    $('embed, object, select').css({ 'visibility' : 'visible' });
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
    for(i=0;i<5;i++) {
            $(arrAccount[i]).val("");
        }
    $(".clsSignInEmail").val("Email Address");
    $(".clsSignUpEmail").val("Email Address");
    $(".clsForgotEmail").val("Email Address");
    $(".clsSignInPwdOld").removeAttr('style');
    $(".clsSignInPwdOld").val('Password');
    $(".clsSignInPwd").attr('style','display:none');
    $(".clsSignUpPwdOld").removeAttr('style');
    $(".clsSignUpPwdOld").val('Password');
    $(".clsSignUpPwd").attr('style','display:none');
    $(".clsSignUpRePwdOld").removeAttr('style');
    $(".clsSignUpRePwdOld").val('Password');
    $(".clsSignUpRePwd").attr('style','display:none');
}

$(".create-ac-head").mousemove(function(){

	$("#signupBox").draggable({
			containment: 'document',
			start: function(event, ui) {

			},
			drag: function(event, ui) {

			},
			stop: function(event, ui) {

			}
		});

});

$(".create-ac-head").mousemove(function(){

	$("#loginBox").draggable({
			containment: 'document',
			start: function(event, ui) {

			},
			drag: function(event, ui) {

			},
			stop: function(event, ui) {

			}
		});

});
