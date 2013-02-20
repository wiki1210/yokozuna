<?php
	define('CONST_NYS_WPURL', 'http://wordpress.org/extend/plugins/navayan-subscribe');
	define('CONST_NYS_NAME', __('Navayan Subscribe') );
	define('CONST_NYS_SLUG',  'navayan-subscribe');
	define('CONST_NYS_VERSION', '1.12');
	define('CONST_NYS_DIR', WP_PLUGIN_URL.'/'.CONST_NYS_SLUG.'/');
	define('CONST_NYS_URL', 'http://blog.navayan.com/');
	define('CONST_NYS_INFO', '<strong>'. nys_original_post( CONST_NYS_NAME ) .'</strong> '. __('allows your visitors to easily and quickly subscribe to your website with double optin process, custom email templates, post/page notifications.') );
	define('CONST_NYS_DONATE_INFO', __('We call \'Donation\' as \'<strong><em>Dhammadana</em></strong>\', which helps to continue support for the plugin.') );
	define('CONST_NYS_DONATE_URL', 'https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name='.CONST_NYS_NAME );
	
	function nys_original_post( $str='' ){
		return '<a href="'. CONST_NYS_URL .'navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/" target="_blank">'. $str .'</a>';
	}
?>