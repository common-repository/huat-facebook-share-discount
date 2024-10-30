(function(d, s, id){
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) {return;}
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));

window.fbAsyncInit = function() {
  FB.init({
    appId      : MyScriptParams.fb_app_id,
    xfbml      : true,
    version    : MyScriptParams.fb_api_version
  });

  window.facebookShare = function( callback ) {

    FB.ui({
      method: 'feed',
      link: MyScriptParams.fb_share_link
    }, function(response) {
      if (response && !response.error_code) {
        alert('Thank you for sharing!');
        var data = {
          'action': 'huat_wc_fb_share_discount_action',
          'whatever': ajax_object.we_value
        };
        jQuery.post(ajax_object.ajax_url, data, function(response) {
          alert('Coupon code generated ' + response);
          location.reload();
        });
      } else {
        alert('Sorry, something went wrong. Please try again.');
      }
    });
  }

};


jQuery('#fbsdbutton').on('click', function( e ) {
  e.preventDefault();

  facebookShare(function( response ) {
    console.log(response);
  });
});

jQuery(document).ready(function($){
  jQuery("#fbsdbutton").click(function(){
    facebookShare(function( response ) {
      console.log(response);
    });
  });
});