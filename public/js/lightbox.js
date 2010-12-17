
$(document).ready(function(){
    
 var divId;
 
 $("#sign-in").click(function(){  
 
     ___addModalWindow('loginBox');
 
 });
 
  $("#sign-up").click(function(){  
 
     ___addModalWindow('signupBox');    
 });
 
   $(".create-ac-close").click(function(){  
 
        _finish('loginBox'); 
   });
 

						   
 function ___addModalWindow(objId) {
     
    divId = objId;
     
    $('#'+objId).draggable();
     
    $('embed, object, select').css({ 'visibility' : 'hidden' }); 
    $('body').append('<div id="overlay"></div>');
    
    $('#'+objId).css({display:''});
    
    var arrPageSizes = ___getPageSize();
            // Style overlay and show it
            $('#overlay').css({
                backgroundColor:    '#000',
                opacity:             0.5,
                width:               arrPageSizes[0],
                height:              arrPageSizes[1]
            }).fadeIn();
            // Get page scroll
            var arrPageScroll = ___getPageScroll();
            
            // Calculate top and left offset for the jquery-lightbox div object and show it
            $('#'+objId).css({
                top:    parseInt((arrPageSizes[3]/2) - (($('#'+objId).height()) / 2)),
                left:    parseInt((arrPageSizes[2]/2) - (($('#'+objId).width()) / 2))
            }).show();
            
            // Assigning click events in elements to close overlay
            $('#overlay').click(function() {
                _finish(objId);                                    
            });                                   
            
            
            _enable_keyboard_navigation();   
 }	 	
	
// If window was resized, calculate the new overlay dimensions
$(window).resize(function() {
	// Get page sizes

	var arrPageSizes = ___getPageSize();
	// Style overlay and show it
	$('#overlay').css({
		width:		arrPageSizes[0],
		height:		arrPageSizes[1]
	});
	// Get page scroll
	var arrPageScroll = ___getPageScroll();

	$('#'+divId).css({
		top:	parseInt(((arrPageScroll[1]) + (arrPageSizes[3]/2) - (($('#'+divId).height()) / 2))),
		left:	parseInt(((arrPageScroll[0]) + (arrPageSizes[2]/2) - (($('#'+divId).width()) / 2))) 
	});
});
			
			
	
$(window).scroll(function() {
		// Get page sizes

		var arrPageSizes = ___getPageSize();
		// Style overlay and show it
		$('#overlay').css({
			width:		arrPageSizes[0],
			height:		arrPageSizes[1]
		});
		// Get page scroll
		var arrPageScroll = ___getPageScroll();

		$('#'+divId).css({
			top:	Math.round(((arrPageScroll[1]) + (arrPageSizes[3]/2) - (($('#'+divId).height()) / 2))),
			left:	Math.round(((arrPageScroll[0]) + (arrPageSizes[2]/2) - (($('#'+divId).width()) / 2))) 
		});
	});     	
		

function _enable_keyboard_navigation() {
	$(document).keydown(function(objEvent) {
		_keyboard_action(objEvent);
	});
}       		

function _keyboard_action(objEvent) {
	var escapeKey = 27;
	// To ie
	if ( objEvent == null ) {
		keycode = event.keyCode;
		
	// To Mozilla
	} else {
		keycode = objEvent.keyCode;				
	}
	// Get the key in lower case form
	key = String.fromCharCode(keycode).toLowerCase();
	
	// Verify the keys to close the ligthBox
	if ( ( key == 'x' ) || ( keycode == escapeKey ) ) {  		
		_finish(divId);
	}			
}  		

function _finish() {			
	$('#'+divId).css({display:'none'});
	$('#overlay').fadeOut(function() { $('#overlay').remove(); });
	// Show some elements to avoid conflict with overlay in IE. These elements appear above the overlay.
	$('embed, object, select').css({ 'visibility' : 'visible' });
}

	
function ___getPageScroll() {
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

		
function ___getPageSize() {
	var jqueryPageSize = new Array($(document).width(),$(document).height(), $(window).width(), $(window).height());
	return jqueryPageSize;
}; 

		
});