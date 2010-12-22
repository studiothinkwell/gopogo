
$(document).ready(function(){

    var divId;    

    $("#sign-in").click(function(){
        divId = "#loginBox";
        $().displayModalBox("#loginBox", ".create-ac-head", ".sign-in-centerbg", 1 );
    });

    $("#sign-up").click(function(){
        divId = "#signupBox";
        $().displayModalBox("#signupBox", ".create-ac-head", ".create-ac-centerbg", 1 );
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




$.fn.addModalWindow = function(objId, createOverlay) {

    $().debugLog('___addModalWindow');
    $().debugLog(objId);

    divId = objId;    

 if(createOverlay) {
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

$.fn.displayModalBox = function(mainBox, titleBox, container, createOverlay ) {
       
        $(mainBox).draggable();

        $(titleBox).mouseover(function(){
            $(mainBox).draggable('enable');
           });

        $(container).mouseover(function(){
                $(mainBox).draggable('disable');
            });

        $().addModalWindow(mainBox, createOverlay);
}



$.fn.enableKeyboardNavigation = function() {
    $(document).keydown(function(objEvent) {
        $().keyboardAction(objEvent);
    });
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

