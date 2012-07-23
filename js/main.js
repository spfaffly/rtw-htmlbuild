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
    scroll:{ items: 1, duration: 1000, fx:"scroll",
      onBefore : function( oldItems, newItems, newSizes ) {
        oldItems.removeClass( "active" );
      },
      onAfter : function( oldItems, newItems, newSizes ) {
         $(newItems[1]).addClass( "active" );
        //$(newItems[1]).animate( {bottom: 0} );
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

  // Show the donate nag if cookie is null
  if($.cookie('donatenag') == null){
    setTimeout(function(){
      $('#donatenag').css({top:-30}).removeClass('hidden').animate({top:0}, 1000);
    }, 3000);
  }

  /* Donate Nag */
  $('#donatenag .action_closenag').on('click', function(event){
    event.preventDefault();
    $('#donatenag').animate({top:-30});
    $.cookie('donatenag', true);
  });

  /* Donate dropdown */
  $('#hero .donate .button').hover(function(){
    $(this).animate({top:-40});
  }, function(){
    $(this).animate({top:-100});
  });

  /* Contact Us Form */
  $('#contactform').submit(function(event){
    event.preventDefault();

    // Serialize form data
    var formdata = $(this).serializeArray();

    // Set error data
    var error = false;
    var errormessage = new Array();

    // Iterate through each form element
    $.each(formdata, function(key, value){
      // Check for name
      if(value['name'] == 'name'){
        if(value['value'] == ''){
          error = true;
          errormessage.push('Please enter your name');
        }
      }

      // Check for email
      if(value['name'] == 'email'){
        if(value['value'] == ''){
          error = true;
          errormessage.push('Please enter your email');
        }
      }

      // Check for message
      if(value['name'] == 'message'){
        if(value['value'] == ''){
          error = true;
          errormessage.push('Please enter your message');
        }
      }
    });

    log(errormessage);
  });


});