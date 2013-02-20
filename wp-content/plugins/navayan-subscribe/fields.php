<?php
	$admin_fields = array(
		array(
			'slug'	=> 'ny_subscribe_field_form_heading',
			'type'	=> 'text',
			'label'	=> __('Form Heading'),
			'val'		=> __('Subscribe')
		),
		array(
			'slug'	=> 'ny_subscribe_field_form_description',
			'type'	=> 'textarea',
			'label'	=> __('Some description before the form'),
			'val'		=> __('Know the updates by subscribing')
		),
		array(
			'slug'	=> 'ny_subscribe_field_label',
			'type'	=> 'text',
			'label'	=> __('Submit Button text'),
			'val'		=> __('Subscribe')
		),
		array(
			'slug'	=> 'ny_subscribe_email_field_text',
			'type'	=> 'text',
			'label'	=> __('Email field label'),
			'val'		=> __('E-Mail')
		),
		array(
			'slug'	=> 'ny_subscribe_field_show_name',
			'type'	=> 'checkbox',
			'label'	=> __('Display Name field'),
			'val'		=> 0
		),
		array(
			'slug'	=> 'ny_subscribe_name_field_text',
			'type'	=> 'text',
			'label'	=> __('Name field label'),
			'val'		=> __('Name')
		),
		array(
			'slug'	=> 'ny_subscribe_field_custom',
			'type'	=> 'text',
			'label'	=> __('Type Custom field you want to display'),
			'val'		=> ''
		),
		array(
			'slug'	=> 'ny_subscribe_field_placeholder',
			'type'	=> 'checkbox',
			'label'	=> __('Use HTML5 placeholder instead label text'),
			'val'		=> 0
		),
		array(
			'slug'	=> 'ny_subscribe_field_hide_form',
			'type'	=> 'checkbox',
			'label'	=> __('Hide subscribe form after success'),
			'val'		=> 0
		),
		array(
			'slug'	=> 'ny_subscribe_field_send_email',
			'type'	=> 'checkbox',
			'label'	=> __('Send email to admin after every subscribe'),
			'val'		=> 0
		),
		array(
			'slug'	=> 'ny_subscribe_field_show_count',
			'type'	=> 'checkbox',
			'label'	=> __('Show subscribers count to users?'),
			'val'		=> 0
		),
		
		// MESSAGES
		array( 'slug' => '', 'type' => 'title', 'label'=> __('Error / Success Messages'), 'val' => '' ),		
		array(
			'slug'	=> 'ny_subscribe_name_field_error_msg',
			'type'	=> 'text',
			'label'	=> __('<b>Error</b> - If Name field is empty'),
			'val'		=> __('Type Name')
		),
		array(
			'slug'	=> 'ny_subscribe_field_email_invalid',
			'type'	=> 'text',
			'label'	=> __('<b>Error</b> - If Invalid email'),
			'val'		=> __('Invalid Email')
		),
		array(
			'slug'	=> 'ny_subscribe_field_email_exist',
			'type'	=> 'text',
			'label'	=> __('<b>Error</b> - If Email already exist'),
			'val'		=> __('This Email already registered')
		),
		array(
			'slug'	=> 'ny_subscribe_field_custom_error_message',
			'type'	=> 'text',
			'label'	=> __('<b>Error</b> - If custom field is empty'),
			'val'		=> __('Required...')
		),
		array(
			'slug'	=> 'ny_subscribe_field_success',
			'type'	=> 'text',
			'label'	=> __('<b>Success</b> - Form successfully submitted'),
			'val'		=> __('To confirm your subscription, please check your email.')
		),
		array(
			'slug'	=> 'ny_subscribe_field_sub_confirmed',
			'type'	=> 'text',
			'label'	=> __('<b>Success</b> - Subscription confirmed'),
			'val'		=> __('Congrats! Your subscription has been confirmed!')
		),
		array(
			'slug'	=> 'ny_subscribe_field_unable_to_subscribe',
			'type'	=> 'text',
			'label'	=> __('<b>Error</b> - If form not successfully submitted'),
			'val'		=> __('Unable to subscribe')
		),
		array(
			'slug'	=> 'ny_subscribe_logged_in_msg',
			'type'	=> 'text',
			'label'	=> __('Display a message if user is logged in'),
			'val'		=> __('You are already joined us!')
		),
		array(
			'slug'	=> 'ny_subscribe_logged_in_msg_unsub',
			'type'	=> 'text',
			'label'	=> __('Message on OptIn page, if user is logged in'),
			'val'		=> __('You are logged in! Please log out to unsubscribe!')
		),

		// UnSubscribe Form
		array( 'slug' => '', 'type' => 'title', 'label'=> __('UnSubscribe Form'), 'val' => '' ),		
		array(
			'slug'	=> 'ny_unsubscribe_button_label',
			'type'	=> 'text',
			'label'	=> __('UnSubscribe Button text'),
			'val'		=> __('UnSubscribe')
		),
		array(
			'slug'	=> 'ny_unsubscribe_msg_before_submit',
			'type'	=> 'text',
			'label'	=> __('<b>Text</b> - before UnSubscribe form submit'),
			'val'		=> __('Please type your email address to unsubscribe')
		),
		array(
			'slug'	=> 'ny_unsubscribe_msg_email_not_exist',
			'type'	=> 'text',
			'label'	=> __('<b>Message</b> - if email not exist'),
			'val'		=> __('Cannot unsubscribe! This email is not exist in our database')
		),
		array(
			'slug'	=> 'ny_unsubscribe_msg_after_submit',
			'type'	=> 'text',
			'label'	=> __('<b>Message</b> - if UnSubscribe form submitted'),
			'val'		=> __('Please check your email for unsubscribe confirmation.')
		),
		
		// OPTIN LINK TEXT
		array( 'slug' => '', 'type' => 'title', 'label'=> __('OptIn link labels/messages'), 'val' => '' ),		
		array(
			'slug'	=> 'ny_subscribe_optin_label',
			'type'	=> 'text',
			'label'	=> __('For <strong>subscription confirmation</strong> email'),
			'val'		=> __('Click here to confirm your subscription')
		),
		array(
			'slug'	=> 'ny_unsubscribe_optin_label',
			'type'	=> 'text',
			'label'	=> __('For <strong>un-subscription confirmation</strong> email'),
			'val'		=> __('Click here to un-subscribe')
		),
		array(
			'slug'	=> 'ny_unsubscribe_optin_confirmed_msg',
			'type'	=> 'text',
			'label'	=> __('<strong>Message</strong> - When user is successfully un-subscribed and removed from system'),
			'val'		=> __('You have successfully un-subscribed!')
		),		
		array(
			'slug'	=> 'ny_unsubscribe_label',
			'type'	=> 'text',
			'label'	=> __('A label for <a href="'. site_url() .'/navayan-subscribe-optin/">un-subscribe link</a> when sending a <strong>post notification</strong>'),
			'val'		=> __('Un-subscribe')
		),
		
	);
	
	// EMAIL TEMPLATES
	$admin_fields_email_template = array(
		array(
			'slug'	=> 'nyEmailFrom',
			'type'	=> 'text',
			'label'	=> __('Email From'),
			'val'		=> '{ADMIN_EMAIL}'
		),
		array( 'slug' => '', 'type' => 'title', 'label'=> __('Template: Post/Page Notification'), 'val' => '' ),
		array(
			'slug'	=> 'nyEmailSubject',
			'type'	=> 'text',
			'label'	=> __('Subject'),
			'val'		=> '{SITE_NAME} - {POST_NAME}'
		),
		array(
			'slug'	=> 'nyEmailBody',
			'type'	=> 'textarea',
			'label'	=> __('Body'),
			'val'		=> "{SITE_NAME} published a new post - {POST_NAME} \n\n{POST_EXCERPT} \n\n{PERMALINK} \n\n{UNSUBSCRIBE} if you do not want to receive post notifications from {SITE_NAME}"
		),
		
		// SUBSCRIBE CONFIRMATION
		array( 'slug' => '', 'type' => 'title', 'label'=> __('Template: Subscribe Confirmation'), 'val' => '' ),
		array(
			'slug'	=> 'nyEmailSubscribeSubject',
			'type'	=> 'text',
			'label'	=> __('Subject'),
			'val'		=> '{SITE_NAME} - subscribe confirmation'
		),
		array(
			'slug'	=> 'nyEmailSubscribeBody',
			'type'	=> 'textarea',
			'label'	=> __('Body'),
			'val'		=> "You or someone else has requested to subscribe posts onto {SITE_NAME}. \n\nPlease confirm your subscription by clicking on following link. Ignore if you do not wish to subscribe."
		),

		// UNSUBSCRIBE CONFIRMATION
		array( 'slug' => '', 'type' => 'title', 'label'=> __('Template: UnSubscribe Confirmation'), 'val' => '' ),
		array(
			'slug'	=> 'nyEmailUnSubscribeSubject',
			'type'	=> 'text',
			'label'	=> __('Subject'),
			'val'		=> '{SITE_NAME} - unsubscribe confirmation'
		),
		array(
			'slug'	=> 'nyEmailUnSubscribeBody',
			'type'	=> 'textarea',
			'label'	=> __('Body'),
			'val'		=> "You or someone else has requested to unsubscribe from {SITE_NAME}. \n\nPlease confirm your unsubscription by clicking on following link. Ignore if you do not wish to unsubscribe."
		)
		
	);
?>