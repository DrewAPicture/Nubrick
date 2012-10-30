jQuery(document).ready(function(){

	// hide #back-top first
	jQuery("#scroll-top").hide();
	
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('#scroll-top').fadeIn();
			} else {
				jQuery('#scroll-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery('#scroll-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 800);
			return false;
		});
	});

});