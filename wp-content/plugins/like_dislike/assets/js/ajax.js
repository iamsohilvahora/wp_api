function wp_like_button_ajax(postId, userId){
	var post_id = postId;
	var user_id = userId;
	//alert(post_id + "  " + user_id );

	//jQuery(this).addClass('wp-like-btn-click');
	//wp-like-btn-click

	jQuery.ajax({
		method: 'post',
		url : plugin_ajax_object.ajaxurl,
		data : {
			action : 'wp_like_button_ajax_action',
			pid : post_id,
			uid : user_id 
		},
		success : function(response){
			jQuery("#wpAjaxResponse span").html(response);

		    jQuery('.wp-like-btn').addClass('wp-like-btn-click');
		    jQuery('.wp-dislike-btn').removeClass('wp-dislike-btn-click');
			
		}

	});

}

function wp_dislike_button_ajax(postId, userId){
	var post_id = postId;
	var user_id = userId;
	//alert(post_id + "  " + user_id );

	jQuery.ajax({
		method: 'post',
		url : plugin_ajax_object.ajaxurl,
		data : {
			action : 'wp_dislike_button_ajax_action',
			pid : post_id,
			uid : user_id 
		},
		success : function(response){
			jQuery("#wpAjaxResponse span").html(response);
			
			jQuery('.wp-dislike-btn').addClass('wp-dislike-btn-click');
			jQuery('.wp-like-btn').removeClass('wp-like-btn-click');
		}

	});

}