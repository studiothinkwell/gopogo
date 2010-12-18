$(document).ready(function(){
	
	$(".your-personal-button").click(function(){            
		$(".footer-middle").slideToggle("show");             

	});

        
  $('.submenu-box').hover
  (
           function()
                        {
                                $(this).addClass('selected');
                    },
           function()
                        {
                                $(this).removeClass('selected');
                        }
   );
			
                        
});