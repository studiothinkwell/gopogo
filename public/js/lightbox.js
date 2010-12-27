
$(document).ready(function(){

    var divId;

    $("#sign-in").click(function(){
        divId = 'loginBox';
     
        $("#loginBox").draggable();        

        $(".sign-in-centerbg").mouseover(function(){
                $("#loginBox").draggable('disable');
            });

        $(".create-ac-head").mouseover(function(){              
                $("#loginBox").draggable('enable');
           });           

        $().addModalWindow('loginBox');

    });

    $("#sign-up").click(function(){
        divId = 'signupBox';

        $("#signupBox").draggable();

        $(".create-ac-centerbg").mouseover(function(){
                $("#signupBox").draggable('disable');
            });

        $(".create-ac-head").mouseover(function(){
                $("#signupBox").draggable('enable');
           });
           
        $().addModalWindow('signupBox');

    });

    $(".create-ac-close").click(function(){
        $().finish();
    });

     $(".login-close").click(function(){
        $().finish();
    });


    $(window).resize(function() {

        var arrPageSizes = $().getPageSize();
        // Style overlay and show it
        $('#overlay').css({
            width:		arrPageSizes[0],
            height:		arrPageSizes[1]
        });
        // Get page scroll
        var arrPageScroll = $().getPageScroll();

        $('#'+divId).css({
            top:	parseInt(((arrPageScroll[1]) + (arrPageSizes[3]/2) - (($('#'+divId).height()) / 2))),
            left:	parseInt(((arrPageScroll[0]) + (arrPageSizes[2]/2) - (($('#'+divId).width()) / 2)))
        });
    });

    $(window).scroll(function() {

        var arrPageSizes = $().getPageSize();

        $('#overlay').css({
        width:		arrPageSizes[0],
        height:		arrPageSizes[1]
        });

        var arrPageScroll = $().getPageScroll();

        $('#'+divId).css({
        top:	Math.round(((arrPageScroll[1]) + (arrPageSizes[3]/2) - (($('#'+divId).height()) / 2))),
        left:	Math.round(((arrPageScroll[0]) + (arrPageSizes[2]/2) - (($('#'+divId).width()) / 2)))
        });
    });
    
});


$.fn.addModalWindow = function(objId) {

    divId = objId;

    

    $('embed, object, select').css({ 'visibility' : 'hidden' });
    $('body').append('<div id="overlay"></div>');

    $('#'+objId).css({display:''});

    var arrPageSizes = $().getPageSize();
    // Style overlay and show it
    $('#overlay').css({
        backgroundColor:    '#000',
        opacity:             0.5,
        width:               arrPageSizes[0],
        height:              arrPageSizes[1]
    }).fadeIn();

    // Get page scroll
    var arrPageScroll = $().getPageScroll();

    // Calculate top and left offset for the jquery-lightbox div object and show it
    $('#'+objId).css({
        top:    parseInt((arrPageSizes[3]/2) - (($('#'+objId).height()) / 2)),
        left:    parseInt((arrPageSizes[2]/2) - (($('#'+objId).width()) / 2))
    }).show();

    // Assigning click events in elements to close overlay
    $('#overlay').click(function() {
        $().finish();
    });

    $().enable_keyboard_navigation();
}


$.fn.enable_keyboard_navigation = function() {
    $(document).keydown(function(objEvent) {
        $().keyboard_action(objEvent);
    });
}


$.fn.keyboard_action = function(objEvent) {
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

    $('#'+divId).css({display:'none'});
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