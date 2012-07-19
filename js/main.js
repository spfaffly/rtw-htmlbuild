/* Author: River to Well */

$(document).ready(function(){
	// Scrollto function
	$('#mainnav a').on('click', function(event){
      event.preventDefault();	// So it doesn't drop
      var section = $(this).attr('href');
      if($(section).length > 0){
         $('html, body').animate({
            scrollTop: $(section).offset().top - 100
         }, 1000);
	 	} else {
	 		$('html, body').animate({scrollTop: 0}, 1000);
	 	}
	 	return false;
   });

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
  
  /* Competition Carousel */
  $("#competition .carousel ul").carouFredSel({
    circular: false,
    infinite: false,
    width: "100%",
    height: "auto",
    auto: { play: false, duration: 3000 },
    scroll:{duration: 1000, fx:"scroll", easing: "swing"},
    prev: {
       button: function() {
          return $(this).parents(".section").find(".prev");
       }
    },
    next: {
       button: function() {
          return $(this).parents(".section").find(".next");
       }
    },
    pagination  : {
       container: function() {
          return $(this).parents(".section").find(".pager");
       }
    }
  });

	/* Main Hero Carousel */
	$("#herocarousel").carouFredSel({
    width: "100%",
    height: "auto",
    auto: 3000,
    scroll:{duration: 1000, fx:"scroll", pauseOnHover:true, easing: "swing"},
    prev: { button: "#hero .prev", key: "left" },
    next: { button: "#hero .next", key: "right" },
    pagination: "#hero .pager"
	});

  /* Winners Carousel */
  $("#winners .carousel ul").carouFredSel({
    circular: true,
    infinite: true,
    width: "100%",
    height: "auto",
    auto: { items: 1, visible: 1, play: true, pauseDuration: 4000, duration: 1000, pauseOnHover: true },
    scroll:{ items: 1, duration: 1000, fx:"scroll", easing: "swing",
      onBefore : function( oldItems, newItems, newSizes ) {
        oldItems.removeClass( "active" );
      },
      onAfter : function( oldItems, newItems, newSizes ) {
        $(newItems[1]).addClass( "active" );
      }
    },
    items: { visible : "+1" },
    prev: {
       button: function() {
          return $(this).parents(".section").find(".prev");
       }
    },
    next: {
       button: function() {
          return $(this).parents(".section").find(".next");
       }
    },
    pagination  : {
      // items : 1,
      //  container: function() {
      //     return $(this).parents(".section").find(".pager");
      //  }
    },
    onCreate : function(items){
      $(items[1]).addClass( "active" );
    }
  });

  /* POTM Carousel */
  $("#potm .carousel ul").carouFredSel({
    width: "100%",
    height: "auto",
    auto: { play: false, duration: 3000 },
    scroll:{duration: 1000, fx:"scroll", pauseOnHover:true, easing: "swing"},
    prev: { button: "#potm .prev", key: "left" },
    next: { button: "#potm .next", key: "right" },
    pagination: "#potm .pager"
  });

  /* Competition Carousel */
  $("#aboutus .carousel ul").carouFredSel({
    circular: false,
    infinite: false,
    width: "100%",
    height: "auto",
    auto: { play: false, duration: 3000 },
    scroll:{duration: 1000, fx:"scroll", easing: "swing"},
    prev: {
       button: function() {
          return $(this).parents(".section").find(".prev");
       }
    },
    next: {
       button: function() {
          return $(this).parents(".section").find(".next");
       }
    },
    pagination  : {
       container: function() {
          return $(this).parents(".section").find(".pager");
       }
    }
  });
});