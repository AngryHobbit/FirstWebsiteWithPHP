$(document).ready(function() {
	
	var nav = $('.navMenu');
	var banner = $('header img');
	var pos = nav.position();
	
	$(window).scroll(function() {

		var windowpos = $(window).scrollTop();
		
		if (windowpos>=banner.outerHeight()) {
			nav.addClass('fixedTop');
		}
		
		else {
			nav.removeClass('fixedTop');
		}
        
	});
    
});