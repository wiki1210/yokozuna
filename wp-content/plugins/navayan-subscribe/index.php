<?php
/*
Plugin Name: Navayan Subscribe
Description: Allows your visitors to easily and quickly subscribe to your website with double optin process, custom email templates, post/page notifications. Can be used as sidebar widget.
Version: 1.12
Usage: Paste this single line code within PHP tag in your template: if ( function_exists('navayan_subscribe') ){ echo navayan_subscribe(); } or put shortcode [navayan_subscribe] in post/page
Donate Link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=amolnw2778@gmail.com&item_name=NavayanSubscribe
Author: Amol Nirmala Waman
Plugin URI: http://blog.navayan.com/navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/
Author URI: http://www.navayan.com/
*/

include_once 'constants.php';
include_once 'functions.php';
function_exists('nys_Call') ? nys_Call() : exit( coreNotFound('function') );

/***************************************************
* UNINSTALL NAVAYAN SUBSCRIBE
* *************************************************/
register_deactivation_hook( __FILE__, 'deactivate_navayan_subscribe' );
function deactivate_navayan_subscribe(){
	global $wpdb;
	$wpdb->query("DELETE FROM $wpdb->options WHERE
							 (option_name LIKE '%ny_subscribe_%') OR
							 (option_name LIKE '%ny_unsubscribe_%') OR
							 (option_name LIKE '%nyEmail%')
							 ");
	$nysPage = get_nysPageID();
	$wpdb->query("DELETE FROM $wpdb->comments WHERE comment_post_ID = $nysPage");
	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = $nysPage");
	wp_delete_post( $nysPage, true );
}

/***************************************************
* INSTALL NAVAYAN SUBSCRIBE
* *************************************************/
register_activation_hook( __FILE__, 'activate_navayan_subscribe' );
function activate_navayan_subscribe(){
	if (version_compare(CONST_NYS_VERSION, '1.10', '<')) {
		// DELETE OLDER 'Navayan Unsubscribe' PAGE AND ITS RELATED DATA. TODO: REMOVE THIS AFTER v1.12
		$nysOldPage = get_page_by_path( 'navayan-unsubscribe' );
		if( $nysOldPage->ID ){
			$wpdb->query("DELETE FROM $wpdb->comments WHERE comment_post_ID = $nysOldPageID");
			$wpdb->query("DELETE FROM $wpdb->postmeta WHERE post_id = $nysOldPageID");
			wp_delete_post( $nysOldPageID, true );
		}
	}
}


/***************************************************
* NAVAYAN SUBSCRIBE FORM SETTINGS
* *************************************************/
function ny_subscribe_admin() {
	global $admin_fields, $admin_fields_email_template;
	global $vEmail, $vName, $vCustom;

	wp_enqueue_style( CONST_NYS_SLUG, CONST_NYS_DIR . 'default.css', '', CONST_NYS_VERSION, 'screen' );
	wp_enqueue_script( CONST_NYS_SLUG, CONST_NYS_DIR .'default.js', array('jquery'), CONST_NYS_VERSION );
	
	$tabEmail = '';
	$tabAbout = '';
	$tabSetting = '';
	echo '<style type="text/css">';	
	if( isset($_POST['ny_subscribe_submit_template']) ){
		$tabEmail = 'class="on"';
	}elseif ( isset( $_GET['delete'] ) ){
		$tabAbout = 'class="on"';
	}else{
		$tabSetting = 'class="on"';
	}
	echo '</style>';	
?>

<div id="wrapper">
	<div class="titlebg" id="plugin_title">
		<span class="head i_mange_coupon"><h1><?php echo CONST_NYS_NAME;?></h1></span>
	</div>
	<div id="page">
		<p>
			<?php _e( 'v'.CONST_NYS_VERSION . ' &nbsp;|&nbsp; ' . nys_original_post( "Plugin's Homepage") ); ?> &nbsp; &nbsp; 
			<a href="<?php echo CONST_NYS_URL; ?>wordpress/" target="_blank"><?php _e('Similar Topics');?></a> &nbsp; &nbsp; 
			<a href="<?php echo CONST_NYS_URL; ?>navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/" target="_blank"><?php _e('Export Users to CSV');?></a> &nbsp; &nbsp; 
			<a href="<?php echo CONST_NYS_DONATE_URL; ?>" target="_blank"><?php _e('Make a donation');?></a> &nbsp; &nbsp; 
			<a href="<?php echo CONST_NYS_WPURL; ?>" target="_blank"><?php _e('Rate this plugin');?></a>
		</p>
		
		<?php
			// WARN ADMIN IF WP < 3.3
			global $wp_version;			
			if (version_compare($wp_version, '3.3', '<')) {
				_e('<h2 class="nysNote">You are using older WordPress ('. $wp_version .'). <strong>'. CONST_NYS_NAME .'</strong> requires minimum 3.3 (newest better!). <a href="http://wordpress.org/latest.zip" target="_blank">Update WordPress</a></h2>');
			}
		?>
		
		<div id="nySubscribeTabs">
			<a <?php echo $tabSetting;?> href="#nySubscribeSettings"><?php _e('Form Settings');?></a>
			<a <?php echo $tabEmail; ?> href="#nySubscribeEmailTemplate"><?php _e('Email Templates');?></a>
			<a <?php echo $tabAbout; ?> href="#nySubscribeAbout">About</a>
			<a href="<?php echo CONST_NYS_DONATE_URL;?>" target="_blank" class="donatelink"><?php _e('Dhammadana');?></a>
		</div>
		
		<div id="nySubscribeBlocks">
			<div id="nySubscribeSettings">
				<form id="nySubscribeSettingsForm" method="post">
					<?php nysAdminForm( sizeof($admin_fields), $admin_fields, 'ny_subscribe_submit_form_settings', __('Settings saved!') ); ?>
					<p>
						<label>&nbsp;</label>
						<input type="submit" name="ny_subscribe_submit_form_settings" id="ny_subscribe_submit_form_settings" class="button button-primary button-large" value="<?php _e('Save Settings');?>" />
					</p>
				</form>
			</div><!-- #nySubscribeSettings -->
			
			<div id="nySubscribeEmailTemplate">
				<form id="nyEmailTemplateForm" method="post">
					<?php nysAdminForm( sizeof($admin_fields_email_template), $admin_fields_email_template, 'ny_subscribe_submit_template', __('Email templates updated!') ); ?>
					<p>
						<label>&nbsp;</label>
						<input type="submit" name="ny_subscribe_submit_template" id="ny_subscribe_submit_template" class="button button-primary button-large" value="<?php _e('Update Email Templates');?>" />
					</p>
				</form>
				
				<div id="nySubscribeSubstitutes">
					<p>
						<?php _e('Following are the keywords, you can you use in email template.');?><br/>
						<?php _e('Copy the keyword with curly braces and paste it into email template.');?>
					</p>
					<ul>
						<li><strong>{SITE_NAME}</strong> - <?php _e( get_option('blogname'));?></li>
						<li><strong>{SITE_URL}</strong> - <?php echo site_url();?></li>
						<li><strong>{POST_NAME}</strong> - <?php _e('This is your published post name');?></li>
						<li><strong>{POST_CONTENT}</strong> - <?php _e('Published post content will be sent');?></li>
						<li><strong>{POST_EXCERPT}</strong> - <?php _e('Published post excerpt will be sent. Note: Exerpt field must not empty!');?></li>
						<li><strong>{POST_CATEGORIES}</strong> - <?php _e('Categorie/s of published post');?></li>
						<li><strong>{POST_TAGS}</strong> - <?php _e('Tag/s of published post');?></li>
						<li><strong>{POST_FEATURED_IMAGE}</strong> - <?php _e('Featured image (thumbnail) of post. (Use <b>ONLY</b> with <b>Post Notification</b>)');?></li>
						<li><strong>{PERMALINK}</strong> - <?php _e('Published post\'s URL');?></li>
						<li><strong>{AUTHOR}</strong> - <?php _e('Name of author of published post');?></li>
						<li><strong>{ADMIN_EMAIL}</strong> - <?php echo get_option('admin_email');?></li>
						<li><strong>{AUTHOR_EMAIL}</strong> - <?php _e('Email of published post author');?></li>
						<li><strong>{UNSUBSCRIBE}</strong> - <a href="<?php echo get_permalink( get_nysPageID() ); ?>" target="_blank"><?php _e( getOption('ny_unsubscribe_label', 'Un-subscribe'));?></a> <?php _e( 'page. (Use <b>ONLY</b> with <b>Post Notification</b> email)' );?></li>
						<?php $optinurl = '/navayan-subscribe-optin/?nysemail=someone@gmail.com&nyskey=randomkey&nystype=';?>
						<li><br/><?php _e('Subscribe confirmation URL will be like this:');?><br/><a><?php echo site_url() . $optinurl;?>subscribe</a></li>
						<li><?php _e('Un-Subscribe confirmation URL will be like this:');?><br/><a><?php echo site_url() . $optinurl;?>unsubscribe</a></li>
					</ul>
				</div>
				
			</div><!-- #nySubscribeEmailTemplate -->
			
			<div id="nySubscribeAbout">
				<p class="nyMotivation">
					<?php
						_e('
							<span>Motivated by : <strong><a href="http://ambedkar.navayan.com" target="_blank">Dr. B. R. Ambedkar</a></strong></span>
							<span>Inspired by : <strong>People and Nature</strong></span>
							<span>Pushed by : <strong>Technologies</strong></span>
						');
					?>
				</p>
				<blockquote><?php _e(CONST_NYS_INFO);?></blockquote>
				<ul>
					<li><strong><?php _e('Want to use in sidebar?');?></strong> - <?php _e('Go to Appearance ->');?> <a href="widgets.php"><?php _e('Widgets');?></a> <?php _e('and drag and drop '.CONST_NYS_NAME.' widget in sidebar.');?>  </li>
					<li><strong><?php _e('Want to use in page?');?></strong> - <?php _e('Create a page/post and add <code>[navayan_subscribe]</code> shortcode into that page.');?></li>
					<li><?php _e('When you add or edit a post or page, just select \'<strong>Notify Subscribers\' checkbox</strong> to send that post / page notification to your subscribers.');?></li>
				</ul>
				<table cellspacing="0" class="wp-list-table widefat">
					<thead>
						<tr>
							<th><a href='users.php'><?php _e('Total Users');?></a></th>
							<th><a href='users.php?role=administrator'><?php _e('Administrators');?></a></th>
							<th><a href='users.php?role=subscriber'><?php _e('Subscribers');?></a></th>
							<th><a href='users.php?role=editor'><?php _e('Editors');?></a></th>
							<th><a href='users.php?role=author'><?php _e('Authors');?></a></th>
							<th><a href='users.php?role=unconfirmed'><?php _e('Un-confirmed');?></a></th>
							<th><a href="<?php echo CONST_NYS_URL;?>navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/" target="_blank"><?php _e('Export Users');?></a></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo nys_TotalUsers();?></td>
							<td><?php echo nys_UserCount( 'administrator' );?></td>
							<td><?php echo nys_UserCount( 'subscriber' );?></td>
							<td><?php echo nys_UserCount( 'editor' );?></td>
							<td><?php echo nys_UserCount( 'author' );?></td>
							<td><?php echo nys_UserCount( 'unconfirmed' );?></td>
							<td><?php _e( '<strong><a href="'. CONST_NYS_URL .'navayan-csv-export-easiest-way-to-export-all-wordpress-table-data-to-csv-format/" target="_blank">Navayan CSV Export</a></strong> will help you to export <br/>your users/subscribers to <br/>CSV (Comma Separate Value) format' ); ?></td>
						</tr>
					</tbody>
				</table>
				
				<blockquote>
					<?php _e('Donate to us. ' . CONST_NYS_DONATE_INFO);?><br/>
					<?php _e('Donating few dollars will make a difference!');?>
					<a href="http://www.justinparks.com/have-you-made-donation-to-your-wordpress-plugin-developer/" target="_blank"><?php _e('read it why?');?></a>
				</blockquote>
				<p>
					<a href="<?php echo CONST_NYS_DONATE_URL;?>" target="_blank" class="button button-primary button-large"><?php _e('Donate through PayPal');?></a>
					<?php _e(' &nbsp; OR &nbsp; ');?>
					<a href="<?php echo CONST_NYS_WPURL;?>" target="_blank" class="button button-primary button-large"><?php _e('Rate this plugin');?></a>
					<?php _e(' &nbsp; OR &nbsp; ');?>
					<a href="<?php echo CONST_NYS_URL;?>navayan-subscribe-easy-way-to-subscribe-wordpress-website-or-blog/" target="_blank" class="button button-primary button-large"><?php _e('Say Thanks, JaiBhim!');?></a>
				</p>
				<p><?php _e('<strong>Note: </strong> If you are not able to donate through PayPal, <a href="'. CONST_NYS_URL .'contact" target="_blank">write to me</a> for other options.');?></p>
			</div><!-- #nySubscribeAbout -->
			
		</div>	
		
	</div>
</div>

	
<?php } ?>