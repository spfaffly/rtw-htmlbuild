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
        // $.each(oldItems, function(){
        //   $(this).animate({bottom:'80px'});
        // });
      },
      onAfter : function( oldItems, newItems, newSizes ) {
        $(newItems[1]).addClass( "active" );
        //$(newItems[1]).css( {bottom: 0} );
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

  /* Team Member Links */
  $('#potm li > a').on('click', function(event){
    event.preventDefault();
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

  /* Team Member Links */
  $('#aboutus .team .member').on('click', function(event){
    event.preventDefault();
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
    $(this).animate({top:-80});
  });

  /* Contact Us Form */
  $('#contactform').submit(function(event){
    event.preventDefault();

    // Remove old error classes
    $(this).find('input, textarea').removeClass('error');

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
          $('[name="' + value['name'] + '"]').addClass('error');
        }
      }

      // Check for email
      if(value['name'] == 'email'){
        if(value['value'] == ''){
          error = true;
          errormessage.push('Please enter your email');
          $('[name="' + value['name'] + '"]').addClass('error');
        }
      }

      // Check for message
      if(value['name'] == 'message'){
        if(value['value'] == ''){
          error = true;
          errormessage.push('Please enter your message');
          $('[name="' + value['name'] + '"]').addClass('error');
        }
      }
    });

    // Submit form?
    if(error){
      // Display error messages?
      var html = '';
      $.each(errormessage, function(key, value){
        html += '<li>' + value + '</li>';
      });
      $('#contactuserror').html(html);
    } else {
      // Submit form to AJAX page
      $.getJSON('contactus.php', formdata, function(response){
        $('#contactform fieldset').animate({opacity:0}, function(){
          $('#contactform fieldset').html('<div class="thanks"><h3>Thanks for contacting us.</h3><p>We will be in touch with you shortly.</p></div>');
          $('#contactform fieldset').animate({opacity:1});
        });
        
      });
    }
  });
  
  /* Remove error box */
  $('#contactform input, #contactform textarea').on('focus', function(){
    $(this).removeClass('error');
  });

  /* Donate box */
  $('.action_donate').on('click', function(event){
    event.preventDefault();
    $('#new_donation').click();
  });

  /* Donate box - POTM */
  $('.action_donate_potm').on('click', function(event){
    event.preventDefault();
    $('#new_donation_potm').click();
  });

  /* Submit box */
  $('.action_submit').on('click', function(event){
    event.preventDefault();
    $.colorbox({href:"http://placehold.it/350x150&text=submit+action+here!", iframe:true, width:'600px', height:'400px', opacity: 0.6, fixed: true});
    log('submit trigger!');
  });

  /* Rules box */
  $('.action_rules').on('click', function(event){
    // event.preventDefault();
    // $.colorbox({href:"http://placehold.it/350x150&text=rules+action+here!", iframe:true, width:'600px', height:'400px', opacity: 0.6, fixed: true});
    // log('rules trigger!');

  });

});