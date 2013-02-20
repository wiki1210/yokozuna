
jQuery(document).ready(function(){
	jQuery('#emailSub-form').submit(function(){
		form=jQuery(this);
		form.fadeOut("Slow");

		
		//send message
		jQuery.post("/wp-admin/admin-ajax.php",{
			action: 'email_subscription',
			email:jQuery("#emailSub-email").val()
		},function(data){
			if(data.status==200){
				jQuery("#emailSub-output")
					.html(jQuery("#emailSub-success").val())
					.fadeIn("Slow");
			}
			else{
				jQuery("#emailSub-output")
				.html(jQuery("#emailSub-fail").val()+": "+data.message)
				.fadeIn("Slow");
			}
		},'json');
		
		return false;
	});

});