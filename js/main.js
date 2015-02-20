jQuery(function(){function e(e){if(e.state=="on"){

jQuery.post(sl_ajax_url,t,function(e){if(e=="success")location.reload()})}}FB.init();

FB.Event.subscribe("edge.create",function(e){var t={post:sl_post_id,action:"wplikelocker",network:"facebook"};

jQuery.post(sl_ajax_url,t,function(e){if(e=="success")location.reload()});FB.XFBML.parse()});

})