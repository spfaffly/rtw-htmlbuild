/* Author: River to Well */

$(document).ready(function(){
	// Scrollto function
	// $('#mainnav a').on('click', function(event){
	// 	event.preventDefault();	// So it doesn't drop
	// 	var section = $(this).attr('href');
	// 	if($(section).length > 0){
	// 		$('html, body').animate({
 //        		scrollTop: $(section).offset().top - 100
 //        	}, 1000);
	// 	} else {
	// 		$('html, body').animate({scrollTop: 0}, 1000);
	// 	}

	// 	return false;
	// });

	// grab the initial top offset of the navigation 
    // var sticky_navigation_offset_top = $('#fixednav').offset().top;
     
    // // our function that decides weather the navigation bar should have "fixed" css position or not.
    // var sticky_navigation = function(){
    //     var scroll_top = $(window).scrollTop(); // our current vertical position from the top
         
    //     // if we've scrolled more than the navigation, change its position to fixed to stick to top,
    //     // otherwise change it back to relative
    //     if (scroll_top > sticky_navigation_offset_top) { 
    //         $('#fixednav').css({ 'position': 'fixed', 'top':0, 'left':0, 'z-index':999, 'width':'100%' });
    //     } else {
    //         $('#fixednav').css({ 'position': 'relative' }); 
    //     }   
    // };
     
    // // run our function on load
    // sticky_navigation();
     
    // // and run it again every time you scroll
    // $(window).scroll(function() {
    //      sticky_navigation();
    // });

	/* Initialize Main Hero */
	// @TODO: Custom callback functions to control scolling
	$("#herocarousel").carouFredSel({
		responsive:true,
	    auto: 3000,
	    width: "variable",
	    scroll:{fx:"crossfade", pauseOnHover:true},
		onCreate: function(){ setTimeout(function(){ $(window).resize(); }, 100); /* Hack to recalculate height */ }
	});

	/* Initialize Winners */
	// @TODO: Custom callback functions to control scolling
	$("#winnercarousel").carouFredSel({
	    items: { visible: '+1' },
		auto: { items: 1, pauseDuration: 4000 },
	    width: "100%",
	    prev: '#winnerprev',
        next: '#winnernext',
        scroll: {
        	pauseOnHover: true,
        	onBefore: function(oldItems, newItems){
        		var count = 0;
        		//oldItems.removeClass('opacity');
        		newItems.each(function(){
        			if(count == 0 || count == 2){
        				$(this).addClass('opacity');
        			} else {
        				$(this).removeClass('opacity');
        			}
        			count++;
        		});
        	}
        },
        onCreate: function(items){ 
        	var count = 0;
    		//oldItems.removeClass('opacity');
    		items.each(function(){
    			if(count == 0 || count == 2){
    				$(this).addClass('opacity');
    			} else {
    				$(this).removeClass('opacity');
    			}
    			count++;
    		});
        }
	});

});