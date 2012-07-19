/* Author: River to Well */

$(document).ready(function(){
  // initiate page scroller plugin
  $('body').pageScroller({
    navigation: '#mainnav',
    scrollOffset: -140
  });

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