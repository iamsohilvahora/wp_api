jQuery(document).on('ready', function(){
	var ajaxscript = { ajax_url : 'http://localhost/wp_api/wp-admin/admin-ajax.php' }

	// http://localhost/wp_api/wp-admin/admin-ajax.php".
	// var ajaxVar = ajax_object.ajaxurl;
	var ajaxVar = ajaxscript.ajaxurl;
	jQuery("#inquiry_form").submit(function(e){
	    e.preventDefault();
	    var form = jQuery(this).serialize();
	    console.log(form);

	    jQuery.ajax({
	        method: 'post',
	        url: ajaxVar,
	        data: {
	        	action : 'addinquiry',
	        },
	        async: true,
	        success: function(response){
	            alert(response);
	            jQuery("#name").val("");
	            jQuery("#contact_no").val("");
	            jQuery("#message").val("");
	        }
	    });
	});	

});

