jQuery(document).ready(function($) {
  //enable attachment of images to comments
  jQuery('form#commentform').attr( "enctype", "multipart/form-data" ).attr( "encoding", "multipart/form-data" );
  //prevent review submission if captcha is not solved
  jQuery("#commentform").submit(function(event) {
    var recaptcha = jQuery("#g-recaptcha-response").val();
    if (recaptcha === "") {
      event.preventDefault();
      alert("Please confirm that you are not a robot");
    }
  });
  //show lightbox when click on images attached to reviews
  jQuery(".ivole-comment-a").click(function(t) {
    t.preventDefault();
    var o = jQuery(".pswp")[0];
    var pics = jQuery(this).parent().parent().find("img");
    var this_pic = jQuery(this).find("img");
    var inx = 0;
    if(pics.length > 0 && this_pic.length > 0) {
      var a = [];
      for(i=0; i<pics.length; i++) {
        a.push({
          src: pics[i].src,
          w: pics[i].naturalWidth,
          h: pics[i].naturalHeight,
          title: pics[i].alt
        });
        if(this_pic[0].src == pics[i].src) {
          inx = i;
        }
      }
      var r = {
        index: inx
      };
      new PhotoSwipe(o,PhotoSwipeUI_Default,a,r).init();
    }
  });
  //register a listener for votes on for reviews
  jQuery("a.ivole-a-button-text").on("click", function(t) {
    t.preventDefault();
    var reviewIDhtml = jQuery(this).attr('id');
    if(reviewIDhtml != null) {
      var reviewID = reviewIDhtml.match(/\d+/)[0];
      var data = {
        "action": "ivole_vote_review",
        "reviewID": reviewID,
        "upvote": 1,
        "security": ajax_object.ajax_nonce
      };
      //check if it is upvote or downvote
      if(reviewIDhtml.indexOf("ivole-reviewyes-") >= 0) {
        data.upvote = 1;
      } else if(reviewIDhtml.indexOf("ivole-reviewno-") >= 0) {
        data.upvote = 0;
      } else {
        return;
      }
      jQuery("#ivole-reviewyes-" + reviewID).parent().parent().parent().parent().hide();
      jQuery("#ivole-reviewno-" + reviewID).parent().parent().parent().parent().hide();
      jQuery("#ivole-reviewvoting-" + reviewID).text(ajax_object.text_processing);
      jQuery.post(ajax_object.ajax_url, data, function(response) {
        if( response.code === 0 ) {
          jQuery("#ivole-reviewvoting-" + reviewID).text(ajax_object.text_thankyou);
        } else if( response.code === 1 ) {
          jQuery("#ivole-reviewvoting-" + reviewID).text(ajax_object.text_thankyou);
        } else if( response.code === 2 ) {
          jQuery("#ivole-reviewvoting-" + reviewID).text(ajax_object.text_thankyou);
        } else if( response.code === 3 ) {
          jQuery("#ivole-reviewvoting-" + reviewID).text(ajax_object.text_error1);
        } else {
          jQuery("#ivole-reviewvoting-" + reviewID).text(ajax_object.text_error2);
        }
      }, "json");
    }
  });
});
