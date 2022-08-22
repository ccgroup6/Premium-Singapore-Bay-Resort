(function($) {
"use strict";

	jQuery(document).ready(function() {

		//START masonry
		jQuery(function ($) {

			var $nd_elements_masonry_content = $(".nd_elements_masonry_content").imagesLoaded( function() {
			  $nd_elements_masonry_content.masonry({ itemSelector: ".nd_elements_masonry_item" });
			});

		});
		//END masonry
		
	});


})(jQuery);