(function($) {
"use strict";

	//navigation
	$(".nd_elements_open_navigation_sidebar_content").on("click",function(event){ 
		$(".nd_elements_navigation_sidebar_content").css({ "right": "0px", }); 
	});
	$(".nd_elements_close_navigation_sidebar_content").on("click",function(event){ 
		$(".nd_elements_navigation_sidebar_content").css({ "right": "-300px" }); 
	});


})(jQuery);