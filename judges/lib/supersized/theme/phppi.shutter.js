(function($){
	
	theme = {
		
		_init: function() {
			if ($(vars.slide_total).length) {
				$(vars.slide_total).html(api.options.slides.length);
			}

		    $(vars.next_slide).click(function() {
		    	api.nextSlide();
		    });
		    
		    $(vars.prev_slide).click(function() {
		    	api.prevSlide();
		    });
			
			if (api.options.thumbnail_navigation) {
				$(vars.next_thumb).click(function() {
			    	api.nextSlide();
			    });
			    
			    $(vars.prev_thumb).click(function() {
			    	api.prevSlide();
			    });
			}
			
		    $(vars.play_button).click(function() {
				api.playToggle();						    
		    });

		    $('#fullscreen-button').click(function() {
		    	toggleFullScreen();
		    });
		},	 

		playToggle : function(state) {
			
			if (state =='pause') {
				$(vars.play_button).addClass('play');
				$(vars.play_button).removeClass('pause');
			}else if (state == 'play') {
				$(vars.play_button).addClass('pause');
				$(vars.play_button).removeClass('play');
			}
			
		},	
		
		beforeAnimation : function(direction) {
		   	if ($(vars.slide_caption).length) {
		   		(api.getField('title')) ? $(vars.slide_caption).html(api.getField('title')) : $(vars.slide_caption).html('');
		   	}

			if (vars.slide_current.length) {
			    $(vars.slide_current).html(vars.current_slide + 1);
			}
		}

	};

	/* Theme Specific Variables
	----------------------------*/
	$.supersized.themeVars = {
		
	// Internal Variables
	progress_delay		:	false,					// Delay after resize before resuming slideshow
	thumb_page 			: 	false,					// Thumbnail page
	thumb_interval 		: 	false,					// Thumbnail interval
	image_path			:	'lib/supersized/img/',	// Default image path
												
	// General Elements							
	play_button			:	'#play-button',			// Play/Pause button
	next_slide			:	'#nextslide',			// Next slide button
	prev_slide			:	'#prevslide',			// Prev slide button

	slide_caption		:	'#slidecaption',		// Slide caption
	slide_current		:	'.slidenumber',			// Current slide number
	slide_total			:	'.totalslides',			// Total Slides
	slide_list			:	'#slide-list',			// Slide jump list
													
	};								

	$.supersized.themeOptions = {				   
		progress_bar: 0,										
		mouse_scrub: 0
	};

	$('body').mousemove(function(e){
		var wHeight = $(window).height();
		var wWidth = $(window).width();
		var yMouse = e.pageY;
		var xMouse = e.pageX;

		//Bottom Tray
		if(yMouse >= (wHeight - $('#bottom-tray').height())) {
			$('#bottom-tray').css( { bottom: '0px' } );
		} else {
			$('#bottom-tray').removeAttr("style");
		}

		if(xMouse <= 100) {
			$('#prevslide').css( { left: '0px' } );
		} else {
			$('#prevslide').removeAttr("style");
		}

		if(xMouse >= (wWidth - 100)) {
			$('#nextslide').css( { right: '0px' } );
		} else {
			$('#nextslide').removeAttr("style");
		}
	});
	
})(jQuery);

function toggleFullScreen() {
	if (!document.fullscreenElement && !document.mozFullScreenElement && !document.webkitFullscreenElement) { 
		if (document.documentElement.requestFullscreen) {
			document.documentElement.requestFullscreen();
		} else if (document.documentElement.mozRequestFullScreen) {
			document.documentElement.mozRequestFullScreen();
		} else if (document.documentElement.webkitRequestFullscreen) {
			document.documentElement.webkitRequestFullscreen();
		} else {
			alert('Your browser does not support HTML5 fullscreen mode. Most browsers have a built in fullscreen mode which is usually activated by pressing F11, try this instead.')
		}
	} else {
		if (document.cancelFullScreen) {
			document.cancelFullScreen();
		} else if (document.mozCancelFullScreen) {
			document.mozCancelFullScreen();
		} else if (document.webkitCancelFullScreen) {
			document.webkitCancelFullScreen();
		}
	}
}