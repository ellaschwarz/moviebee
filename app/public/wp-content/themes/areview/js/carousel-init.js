/**
 * Initialize the carousel.
 */ 
 
jQuery(function($) {
	$(".carousel").owlCarousel({
	    items : 4,
	    itemsCustom : false,
	    itemsDesktop : [1199,4],
	    itemsDesktopSmall : [991,3],
	    itemsTablet: [767,2],
	    itemsTabletSmall: false,
	    itemsMobile : [399,1],
	    itemsScaleUp : true,
	    slideSpeed : +carouselOptions.slideshowspeed,
	    autoPlay: true,
	    stopOnHover : true,
	    navigation : true,
	    navigationText : ['<i class="fa fa-caret-left"></i>','<i class="fa fa-caret-right"></i>'],
	    responsiveRefreshRate : 100,
	})
});

