/*--custom js start here--*/
jQuery(document).ready(function(){
  /*--function start to add to cart product custom form--*/
  jQuery(".addcartBtn").click(function(event){
    event.preventDefault();
    jQuery(".cartmessages").empty();
    var productQuentity = jQuery("#quantity option:selected").text();
    var productID = jQuery("#quantity option:selected").attr("data-id");
    jQuery.ajax({
        type: 'POST',
        url: ajaxurl,
        data: {
              'action':'add_product_to_cart',
              'productQuentity' : productQuentity,
              'productID' : productID
          },
          success:function(response) {
              //console.log(response);
              jQuery(".cartmessages").html(response);
          },
          error: function(errorThrown){
              //console.log(errorThrown);
              //alert("fail");
          }
    });
  });
  /*--//function end to add to cart product custom form--*/
  /*--function start to open custom review form--*/
    jQuery(".write-review").click(function(event){
        event.preventDefault();
        jQuery(".addcommenttoproduct").show();
    });
  /*--//function end to open custom review form--*/


      /*var submitmsg = jQuery(document).find(".wppb-error").text();
      var passwmsg  = jQuery(document).find(".profilepress-reset-status").text();*/
      var loginmsg  = jQuery(document).find(".wppb-error").text();
        if(loginmsg !=''){
          jQuery('#loginModal').modal({
              show: 'true'
          });
        }
        /*if(passwmsg != ''){
            jQuery('#forgot-modal').modal({
              show: 'true'
            });
        }
        if(loginmsg != ''){
            jQuery('#login-modal').modal({
              show: 'true'
            });
        }*/



});
/*--//custom js end here--*/




jQuery(document).ready(function() {  
    jQuery("#transition-timer-carousel").on("slide.bs.carousel", function(event) {
        jQuery(".transition-timer-carousel-progress-bar", this)
            .removeClass("animate").css("width", "0%");
    }).on("slid.bs.carousel", function(event) {
        jQuery(".transition-timer-carousel-progress-bar", this)
            .addClass("animate").css("width", "100%");
    });
    
    jQuery(".transition-timer-carousel-progress-bar", "#transition-timer-carousel")
        .css("width", "100%");
});
		/*jQuery(document).ready(function(){

		jQuery('#recommendations-slider').carousel({ interval: false });

		jQuery('.carousel-showmanymoveone .item').each(function(){
		var itemToClone = jQuery(this);

		for (var i=1;i<6;i++) {
		itemToClone = itemToClone.next();

		if (!itemToClone.length) {
		itemToClone = jQuery(this).siblings(':first');
		}

		itemToClone.children(':first-child').clone()
		.addClass("cloneditem-"+(i))
		.appendTo(jQuery(this));
		}
		});
		});*/
  
  (function(){
	  jQuery('#carousel123').carousel({ interval: false });
	  jQuery('#carousel456').carousel({ interval: false });
	  jQuery('#carouselABC').carousel({ interval: false });
	  jQuery('#carouselXYZ').carousel({ interval: false });
  }());

/*(function(){
  jQuery('.carousel-showmanymove .item').each(function(){
    var itemToClone = jQuery(this);

    for (var i=1;i<4;i++) {
      itemToClone = itemToClone.next();

      // wrap around if at end of item collection
      if (!itemToClone.length) {
        itemToClone = jQuery(this).siblings(':first');
      }

      // grab item, clone, add marker class, add to collection
      itemToClone.children(':first-child').clone()
        .addClass("cloneditem-"+(i))
        .appendTo(jQuery(this));
    }
  });
}());	*/

		jQuery(window).scroll(function() {
    if (jQuery(this).scrollTop() >= 50) {        // If page is scrolled more than 50px
        jQuery('#return-to-top').fadeIn(200);    // Fade in the arrow
    } else {
        jQuery('#return-to-top').fadeOut(200);   // Else fade out the arrow
    }
});
jQuery('#return-to-top').click(function() {      // When arrow is clicked
    jQuery('body,html').animate({
        scrollTop : 0                       // Scroll to top of body
    }, 500);
});  
	 	( function( window ) {

'use strict';

// class helper functions from bonzo https://github.com/ded/bonzo

function classReg( className ) {
  return new RegExp("(^|\\s+)" + className + "(\\s+|jQuery)");
}

// classList support for class management
// altho to be fair, the api sucks because it won't accept multiple classes at once
var hasClass, addClass, removeClass;

if ( 'classList' in document.documentElement ) {
  hasClass = function( elem, c ) {
    return elem.classList.contains( c );
  };
  addClass = function( elem, c ) {
    elem.classList.add( c );
  };
  removeClass = function( elem, c ) {
    elem.classList.remove( c );
  };
}
else {
  hasClass = function( elem, c ) {
    return classReg( c ).test( elem.className );
  };
  addClass = function( elem, c ) {
    if ( !hasClass( elem, c ) ) {
      elem.className = elem.className + ' ' + c;
    }
  };
  removeClass = function( elem, c ) {
    elem.className = elem.className.replace( classReg( c ), ' ' );
  };
}

function toggleClass( elem, c ) {
  var fn = hasClass( elem, c ) ? removeClass : addClass;
  fn( elem, c );
}

window.classie = {
  // full names
  hasClass: hasClass,
  addClass: addClass,
  removeClass: removeClass,
  toggleClass: toggleClass,
  // short names
  has: hasClass,
  add: addClass,
  remove: removeClass,
  toggle: toggleClass
};

})( window );

var menuLeft = document.getElementById( 'cbp-spmenu-s1' ),
				body = document.body;

			/*showLeft.onclick = function() {
				classie.toggle( this, 'active' );
				classie.toggle( menuLeft, 'cbp-spmenu-open' );
				disableOther( 'showLeft' );
			};
			function disableOther( button ) {
				if( button !== 'showLeft' ) {
					classie.toggle( showLeft, 'disabled' );
				}
				
			}*/ 

	var target_date = new Date().getTime() + (1000*3600*48); // set the countdown date
	var days, hours, minutes, seconds; // variables for time units

	var countdown = document.getElementById("tiles"); // get tag element

	getCountdown();

	setInterval(function () { getCountdown(); }, 1000);

function getCountdown(){

	var current_date = new Date().getTime();
	var seconds_left = (target_date - current_date) / 1000;

	days = pad( parseInt(seconds_left / 86400) );
	seconds_left = seconds_left % 86400;
		 
	hours = pad( parseInt(seconds_left / 3600) );
	seconds_left = seconds_left % 3600;
		  
	minutes = pad( parseInt(seconds_left / 60) );
	seconds = pad( parseInt( seconds_left % 60 ) );

	countdown.innerHTML = "<span>" + days + "</span><span>" + hours + "</span><span>" + minutes + "</span><span>" + seconds + "</span>"; 
}

function pad(n) {
	return (n < 10 ? '0' : '') + n;
}