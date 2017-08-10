jQuery(function () {
	jQuery(window).scroll(function () {
		if (jQuery(this).scrollTop() > 400)
			jQuery('a#move_up').fadeIn(300);
		else
			jQuery('a#move_up').fadeOut(300);
	});
	jQuery('a#move_up').click(function () {
		jQuery('body,html').animate({
			scrollTop: 0
		}, 1000);
		return false;
	});
});
