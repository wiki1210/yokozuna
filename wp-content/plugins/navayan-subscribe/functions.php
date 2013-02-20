<?php
	/***************************************************
	* EXIT IF PLUGIN'S CORE CLASS OR FUNCTION NOT FOUND
	* *************************************************/
	function coreNotFound( $str = 'class' ){
		return __('Core '. $str .' missing! Please <a href="'. CONST_NYS_WPURL .'">re-install '. CONST_NYS_NAME .'</a>');
	}
	
	function nysRandomNumber($len) {
		$chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
		$charlength = strlen($chars);
		$i = 0;
		srand((double)microtime()*1000000);
		while ($i <= $len) {
			$num = rand() % $charlength;
			$tmp = substr($chars, $num, 1);
			$code= isset($code) ? $code : '';
			$code= $code . $tmp;
			$i++;
		}
		return $code;
	}
	
	// GLOBALS
	$vEmail	= isset($_POST['ny_email']) ? $_POST['ny_email'] : '';
	$vName	= isset($_POST['ny_name']) ? $_POST['ny_name'] : '';
	$vCustom= isset($_POST['ny_custom']) ? $_POST['ny_custom'] : '';
	
	include_once 'fields.php';
	
	$metaSlug = 'ny_notify_subscribers';
	global $vEmail, $vName, $vCustom;
	global $metaSlug;
	
	/***************************************************
	* PLUGIN'S CORE FUNCTION
	* *************************************************/
	function nys_Call(){
		add_action('admin_menu','nys_CreateMenu');
		add_filter('user_contactmethods','nys_ExtendContact',10,1);
		nys_CreatePage();
		//if ( has_action('post_submitbox_misc_actions') ){
			add_action( 'post_submitbox_misc_actions', 'nys_Box' );
		//}elseif ( has_action('post_submitbox_start') ){
			//add_action( 'post_submitbox_start', 'nys_Box' );
		//}
		add_action('widgets_init', 'ny_subscribe_widget_init');
		add_shortcode('navayan_subscribe_optin', 'navayan_subscribe_optin');
	}
	
	/***************************************************
	* GET OPTION VALUE WITH MINIMUM FILTER
	* *************************************************/
	function getOption( $option, $defValue = false ){
		$str = get_option( $option ) ? get_option( $option ) : $defValue;
		$str = html_entity_decode(nl2br($str));
		return stripcslashes( preg_replace('/[\s]+/', ' ', $str ) );
	}
	
	/***************************************************
	* CREATE 'Navayan Subscribe OptIn' PAGE IF NOT EXIST
	* *************************************************/
	function nys_CreatePage(){
		$pageid = get_nysPageID();
		if ( !$pageid ){
			wp_insert_post( array(
				'post_title' => 'Navayan Subscribe OptIn',
				'post_status' => 'publish', 
				'post_type' => 'page',
				'post_author' => 1,
				'post_content' => '[navayan_subscribe_optin]'
			));
		}
	}

	/***************************************************
	* GET 'Navayan Subscribe OptIn' PAGE ID
	* *************************************************/
	function get_nysPageID(){
		$nysPage = get_page_by_path( 'navayan-subscribe-optin' );
		return (int) $nysPage->ID;
	}
	
	/***************************************************
	* CREATE MENU UNDER 'DASHBOARD -> TOOLS' TAB
	* *************************************************/
	function nys_CreateMenu() {
		if (function_exists('add_options_page')) {
			add_management_page( __( CONST_NYS_NAME, CONST_NYS_SLUG ), __( CONST_NYS_NAME, CONST_NYS_SLUG ), 'manage_options', CONST_NYS_SLUG, 'ny_subscribe_admin');
		}
	}
	
	/***************************************************
	* EXTEND USERMETA FOR ADDITIONAL CUSTOM FIELD
	* *************************************************/
	function nys_ExtendContact( $contactmethods ) {
		$contactmethods[ 'ny_subscribe_field_custom' ] = get_option( 'ny_subscribe_field_custom' );
		return $contactmethods;
	}	
	
	/***************************************************
	* ADD NOTIFICATION CHECKBOX TO 'PUBLISH' METABOX
	* *************************************************/
	function nys_Box(){
		global $metaSlug;
		echo '<div id="nySubscribeBox" style="position:relative; padding: 8px 10px; border-bottom:1px solid #DFDFDF">';
		echo '<label><input type="checkbox" value="1" name="'. $metaSlug .'" />&nbsp; ';
		_e( 'Notify Subscribers' );
		echo '</label> <span style="position:absolute; right: 10px"> <a style="text-decoration:none" href="tools.php?page='. CONST_NYS_SLUG .'">';
		_e( 'Settings' );
		echo '</a>';
		echo ' | '. nys_original_post( __('Help') );
		echo '</span></div>';
	}
	
	
	/***************************************************
	* NOTIFICATION CHECKBOX
	* *************************************************/
	if ( has_action('publish_post') || has_action('save_post') ){		
		if ( isset($_POST['publish']) ){ // IF POST/PAGE IS PUBLISHED
			add_action( 'publish_post', 'nys_UpdateBox', 10, 1 );
		}elseif ( isset($_POST['save']) ){ // FALLBACK - IF 'publish_post' ACTION NOT PRESENT AND POST/PAGE IS UPDATED/SAVED
			add_action( 'save_post', 'nys_UpdateBox', 10, 1 );
		}
	}
	
	function nys_UpdateBox(){
		global $post, $metaSlug;
		$checked = isset($_POST[$metaSlug]) ? $_POST[$metaSlug] : '';
		if ( !empty($checked) ){
			NAVAYAN_SUBSCRIBE::nys_SendNotification( $post->ID );
		}
		$checked = '';
		unset($checked);
	}
	
	
	/***************************************************
	* GET VARIOUS USERS COUNT
	* *************************************************/
	function nys_TotalUsers(){
		global $wpdb;	
		$users = $wpdb->get_var( "SELECT COUNT(ID) FROM ". $wpdb->prefix ."users" );
		return (int) $users;
	}
	function nys_UserCount( $role ){
		global $wpdb;
		$ifRole = $role ? " LIKE '%$role%' " : " = '' ";		
		$users = $wpdb->get_var( "SELECT COUNT(umeta_id) FROM ". $wpdb->prefix ."usermeta WHERE meta_key='". $wpdb->prefix ."capabilities' AND meta_value $ifRole " );
		return (int) $users;
	}
	
	class NYS_FIELDS{
		function form_label_email	(){	return getOption( 'ny_subscribe_email_field_text', __('E-Mail') );}
		function form_label_name(){		return getOption( 'ny_subscribe_name_field_text', __('Name') );}
		function form_label_custom(){	return getOption( 'ny_subscribe_field_custom' );}		
		function form_msg_name(){			return getOption( 'ny_subscribe_name_field_error_msg', __('Type Name') );}
		function form_msg_custom(){		return getOption( 'ny_subscribe_field_custom_error_message', __('Required...') );}		
		function form_msg_email(){		return getOption( 'ny_subscribe_field_email_invalid', __('Invalid Email') );}
	}
	

	class NAVAYAN_SUBSCRIBE{
		
		/***************************************************
		 * GET POST FEATURED IMAGE FOR NOTIFICATION
		 * *************************************************/
		function nys_FeaturedImage($post_id){
			if ( has_post_thumbnail() ){
				echo get_the_post_thumbnail( $post_id, 'thumbnail' );
			}
		}		
		
		/***************************************************
		 * REPLACE ALL SUBSTITUTES
		 * *************************************************/
		function nys_ReplaceSubstitutes( $post_id = 0, $strToReplace = '' ){
			$post = get_post( $post_id );
			$nysPage = get_permalink( get_nysPageID() );
			
			$str = array(
				'{SITE_NAME}',
				'{SITE_URL}',
				'{POST_NAME}',
				'{POST_CONTENT}',
				'{POST_EXCERPT}',
				'{POST_CATEGORIES}',
				'{POST_TAGS}',
				'{PERMALINK}',
				'{AUTHOR}',
				'{ADMIN_EMAIL}',
				'{AUTHOR_EMAIL}',
				'{UNSUBSCRIBE}'
			);
			$replaceWith = array(
				get_option('blogname'),
				get_option('siteurl'),
				$post->post_title,
				'<div style="clear:both; margin: 14px 0">'. $post->post_content .'</div>',
				'<div style="clear:both; margin: 14px 0">'. $post->post_excerpt .'</div>',
				get_the_category_list( __( ', ', '', $post->ID ) ),
				get_the_tag_list( '', __( ', ', '', $post->ID ) ),
				'<a href="'. get_permalink( $post->ID ) .'" target="_blank">'. $post->post_title .'</a>',
				stripslashes( get_the_author_meta( 'display_name', $post->post_author ) ),
				get_option('admin_email'),
				get_the_author_meta( 'user_email', $post->post_author ),
				'<a href="'. $nysPage .'" target="_blank">'. getOption('ny_unsubscribe_label', 'Un-subscribe') .'</a>' // http://siteurl/navayan-subscribe-optin/
			);
			
			return str_replace ( $str, $replaceWith, $strToReplace );
		}
		
		
		/***************************************************
		 * SEND NEW POST NOTIFICATION TO SUBSCRIBERS
		 * *************************************************/
		function nys_SendNotification( $post_id = 0 ) {
			global $wpdb;
			$post = get_post( $post_id );
			$subscribersArray = '';			
			$subscriber_email = mysql_query("SELECT user_email
																			FROM ". $wpdb->prefix ."users u, ". $wpdb->prefix ."usermeta um
																			WHERE um.meta_key='". $wpdb->prefix ."capabilities'
																			AND um.meta_value LIKE '%subscriber%'
																			AND um.user_id = u.ID ") or die(mysql_error());
			
			while ($row = mysql_fetch_array($subscriber_email)){
				$subscribersArray .= $row['user_email'].',';
			}
			
			$blogname = stripslashes ( get_option('blogname') );
			$adminEmail = get_option('admin_email' );
			$from = self::nys_ReplaceSubstitutes( $post->ID, getOption( 'nyEmailFrom', $adminEmail ) );
			ini_set("sendmail_from","<$from>");
			$to = '';
			$bcc = substr(  preg_replace( '/[\s]+/', '', $subscribersArray ), 0, -1);
			$subject	= __( self::nys_ReplaceSubstitutes( $post->ID, getOption( 'nyEmailSubject' ) ) );
			$headers  = "MIME-Version: 1.0 \r\n";
			$headers .= "Content-type: text/html; charset=utf-8 \r\n";
			$headers .= "From: $blogname <$from>\r\n";			
			$headers .= "Bcc: ". $bcc . "\r\n"; // SEND BCC MAIL - IT WILL SAVE TIME AND EXECUTION
			$mailedBy = "-f $adminEmail";
			$mailBody = getOption( 'nyEmailBody' );
			$mailBody = str_replace('{POST_FEATURED_IMAGE}', self::nys_FeaturedImage( $post->ID ), $mailBody ); // FEATURED POST IMAGE

			$message = "<!DOCTYPE html>
									<html>
										<head><title>". $blogname ."</title></head>
										<body style='margin: 20px'>"
											. __( self::nys_ReplaceSubstitutes( $post->ID, $mailBody ) ) ."
										</body>
									</html>";
			return @wp_mail( $to, $subject, $message, $headers, $mailedBy );
		}
		
	}
	
	// MADE CLASS NYS_FIELDS GLOBAL TO AVOID ITERATIONS
	$class_NYS_FIELDS = class_exists('NYS_FIELDS') ? new NYS_FIELDS : exit( coreNotFound() );
	global $class_NYS_FIELDS;
	
	
	/***************************************************
	* NAVAYAN SUBSCRIBE WIDGET IN SIDEBAR
	* *************************************************/
	class ny_subscribe_widget extends WP_Widget {
		public function __construct() {
			$this->WP_Widget(
				2,
				'',
				array(
					'name' => __( CONST_NYS_NAME ),
					'description' => __('Displays Navayan Subscribe form as a widget in sidebar')
				)
			);
		}
		
		public function form() {
			echo '<p class="no-options-widget">' . __('<a href="tools.php?page='. CONST_NYS_SLUG .'">Settings</a>') . '</p>';
			return 'noform';
		}
	
		public function widget( $args ) {
			extract($args);
			
			// DISPLAY SUBSCRIBERS COUNT TO USER
			$showCount='';
			if ( getOption( 'ny_subscribe_field_show_count' ) == 1 ){
				$showCount = ' ('. __( nys_UserCount( 'subscriber' ) ) .')';
			}
			
			$title = apply_filters( 'widget_title', getOption( 'ny_subscribe_field_form_heading', __('Subscribe') ) . $showCount );
			echo $before_widget;
			
			if ( ! empty( $title ) ){				
				echo $before_title . $title . $after_title;
			}
				
			if ( function_exists('navayan_subscribe') ){	
				navayan_subscribe();
			}
			echo $after_widget;
		}
	}
	function ny_subscribe_widget_init() {
		register_widget('ny_subscribe_widget');
	}
	
	
	/***************************************************
	* SUBSCRIBE FORM UI
	* *************************************************/
	if ( !function_exists('navayan_subscribe') ){	
		function navayan_subscribe(){
			wp_enqueue_style( '', CONST_NYS_DIR . 'default.css' );
			
			$wrapper_id = 'ny_subscribe_wrapper';
			echo "<div id='". $wrapper_id ."'>";
			
			// EXCLUDE SUBSCRIBE FORM FOR LOGGED IN USER
			if ( !is_user_logged_in() ) {
				wp_enqueue_script( CONST_NYS_SLUG, CONST_NYS_DIR .'default.js', array('jquery'), '1.9' );

				echo '<p>'. getOption( 'ny_subscribe_field_form_description', __('Know the updates by subscribing') ) .'</p>';

				if ( isset( $_POST['ny_subscribe_submit'] ) ){
					nys_AddSubscriberSubmit ();
				}				
				echo "<form class='v". CONST_NYS_VERSION ."' id='ny_subscribe_form' method='post' action='#". $wrapper_id ."'>";
				
				nys_FormFields();
	
				echo "<p id='ny_subscribe_submit_wrapper'>
							<input type='submit' name='ny_subscribe_submit' id='ny_subscribe_submit' value='". getOption( 'ny_subscribe_field_label', __('Subscribe') ) ."' />
						</p>
					</form>";
			}else{
				_e( "<p>". getOption( 'ny_subscribe_logged_in_msg', __('You are already joined us!') ) ."</p>" );
			}
			
			echo '</div>';
		}
		add_shortcode('navayan_subscribe', 'navayan_subscribe');
	}
	
	/***************************************************
	* DOUBLE OPTIN - SUBSCRIBE, UNSUBSCRIBE, DELETE
	* *************************************************/
	function navayan_subscribe_optin(){
		global $wpdb, $class_NYS_FIELDS;
		
		# CHECK 'Navayan Subscribe OptIn' PAGE EXIST
		if ( is_page('navayan-subscribe-optin') ) {
			if ( is_user_logged_in() ){
				_e( '<h3>'. getOption( 'ny_subscribe_logged_in_msg_unsub', __('You are logged in! Please log out to unsubscribe!') ) .'</h3>' );
			}else{
				$nysemail	= isset($_GET['nysemail']) ? $_GET['nysemail'] : ''; // someone@gmail.com
				$nyskey		= isset($_GET['nyskey']) ? $_GET['nyskey'] : ''; // random key
				$nystype	= isset($_GET['nystype']) ? $_GET['nystype'] : ''; // subscribe / unsubscribe				
				
				// DISPLAY UNSUBSCRIBE FORM IF THERE IS NO PARAM IN URL
				if ( !$nysemail && !$nyskey && !$nystype ){
					$beforeSubmit = '<p>'. __( getOption( 'ny_unsubscribe_msg_before_submit', 'Please type your email address to unsubscribe' ) ) .'</p>';
					if ( isset($_POST['unsubscribe_submit']) ){
						$unsub_email = trim( stripcslashes ( $_POST['unsubscribe_email'] ) );
						$getUser = get_user_by( 'email', $unsub_email );
						if ( is_email ( $unsub_email ) ){
							if (  $getUser ){
								// SEND UNSUBSCRIBE CONFIRMATION EMAIL TO USER
								nysOptInEmail( 'unsubscribe', $unsub_email, 'nyEmailUnSubscribeSubject', 'nyEmailUnSubscribeBody' );
								_e( '<p style="color:#090"><strong>'. getOption( 'ny_unsubscribe_msg_after_submit', 'Please check your email for unsubscribe confirmation.' ) ) .'</strong></p>';
							}else{
								_e( '<p style="color:#f00">'. getOption( 'ny_unsubscribe_msg_email_not_exist', 'Cannot unsubscribe! This email is not exist in our database' ) ) .'</p>';
							}
						}else{
							echo $beforeSubmit;
							echo '<p style="color:#f00">'. __( $class_NYS_FIELDS->form_msg_email() ) .'</p>';
						}
					}else{
						echo $beforeSubmit;
					}
					
					echo '<form id="navayan_unsubscribe_form" name="navayan_unsubscribe_form" method="post">';
					echo '<p><input required="required" type="email" name="unsubscribe_email" id="unsubscribe_email" placeholder="'.__( $class_NYS_FIELDS->form_label_email() ).'" /></p>';
					echo '<p id="ny_unsubscribe_submit_wrapper"><input type="submit" name="unsubscribe_submit" id="unsubscribe_submit" value="'. __( getOption( 'ny_unsubscribe_button_label', __('UnSubscribe') ) ) .'" /></p>';
					echo '</form>';
					
				} else {
					
					// SUBSCRIBE / UNSUBSCRIBE PROCESSES
					if ( is_email($nysemail) ){
				
						$getUser = get_user_by( 'email', $nysemail );
						
						if ( $getUser ){			
							
							// SUBSCRIBE PROCESS
							if( empty($getUser->roles[0]) && 
									$nysemail == $getUser->user_email && 
									$nyskey == $getUser->user_pass && 
									$nystype == 'subscribe'
								){
								delete_user_meta( $getUser->ID, $wpdb->prefix . 'capabilities' );
								update_user_meta( $getUser->ID, $wpdb->prefix . 'capabilities', 'subscriber' );
								$newPass = nysRandomNumber(10);
								$wpdb->query( "UPDATE ". $wpdb->prefix ."users SET user_pass = '". wp_hash_password( $newPass ) ."' WHERE ID = ". $getUser->ID );
								echo '<p style="clear:both; font-weight:700; padding:4px 12px; color:#090;	border:1px solid #3C6; background: #C4FFAF;">'. getOption( 'ny_subscribe_field_sub_confirmed', __('Congrats! Your subscription has been confirmed!') ) .'</p>';
								
								// TODO - SEND CREDENTIALS TO SUBSCRIBER							
								
								# SEND PLAIN EMAIL TO ADMIN
								if( getOption( 'ny_subscribe_field_send_email' ) == 1 ){							
									$fname = get_user_meta( $getUser->ID, 'first_name' );									
									$custom= get_user_meta( $getUser->ID, 'ny_subscribe_field_custom' );
									$message = __("New member subscribed to your website/blog ". get_option('blogname') );						
									if ( getOption( 'ny_subscribe_field_show_name' ) == 1 && !empty($fname[0]) ){
										$message .= __("\nName: ". stripslashes($fname[0]) );
									}
									$message .= __("\nEmail: ". $nysemail );
									if ( !empty($custom) ){
										$message .= __("\n". getOption('ny_subscribe_field_custom') .": ". stripslashes($custom[0]) );
									}
									$message .= __("\nDate: ". date( get_option('date_format')) );
					
									$headers  = "MIME-Version: 1.0 \r\n";
									$headers .= "Content-type: text/plain; charset=utf-8 \r\n";
									$headers .= "From: ". CONST_NYS_NAME;
									@wp_mail( get_option('admin_email'), __('New Subscriber!'), $message, $headers );
								}
							}
							
							// UN-SUBSCRIBE PROCESS
							if( !empty($getUser->roles[0]) &&
									$getUser->roles[0] == 'subscriber' &&
									$nysemail == $getUser->user_email && 
									$nyskey == $getUser->user_pass && 
									$nystype == 'unsubscribe'
								){
								$fileUser = './wp-admin/includes/user.php';		
								if ( file_exists ( $fileUser ) ){
									require_once( $fileUser );
									$deleteSubscriber = wp_delete_user( $getUser->ID, 1 );
									if ( $deleteSubscriber ){ # IF SUCCESSFULLY UNSUBSCRIBED, SHOW MESSAGE
										echo '<p style="clear:both; font-weight:700; padding:4px 12px; color:#090;	border:1px solid #3C6; background: #C4FFAF;">'. __( getOption( 'ny_unsubscribe_optin_confirmed_msg', 'You have successfully un-subscribed!' ) ) . '</p>';
									}
								}else{
									exit( 'Wordpress has dropped some core files!' );
								}
							}
						}
					}
				}
			}
		}
	}
	
	
	/***************************************************
	* SUBSCRIBE FROM FIELDS
	* *************************************************/
	function nys_FormFields(){
		global $vEmail, $vName, $vCustom;
		global $class_NYS_FIELDS;
		
		if( getOption( 'ny_subscribe_field_show_name' ) == 1){
			if ( getOption( 'ny_subscribe_field_placeholder' ) ){
				echo "<p>
					<input required='required' placeholder='".__( $class_NYS_FIELDS->form_label_name() )."' title='".__( $class_NYS_FIELDS->form_label_name() )."' type='text' name='ny_name' id='ny_name' rel='". $class_NYS_FIELDS->form_msg_name() ."' value='". stripslashes( $vName ) ."' />
				</p>";
			}else{
				echo "<p>
					<label for='ny_name'>".__( $class_NYS_FIELDS->form_label_name() )."</label>
					<input type='text' required='required' name='ny_name' id='ny_name' rel='". $class_NYS_FIELDS->form_msg_name() ."' value='". stripslashes( $vName ) ."' />
				</p>";
			}
		}
		
		if ( getOption( 'ny_subscribe_field_placeholder' ) ){
			echo "<p>
				<input type='email' required='required' placeholder='".__( $class_NYS_FIELDS->form_label_email() )."' title='".__( $class_NYS_FIELDS->form_label_email() )."' type='text' name='ny_email' id='ny_email' rel='". $class_NYS_FIELDS->form_msg_email() ."' value='". stripslashes( $vEmail ) ."' />
			</p>";
		}else{
			echo "<p>
				<label for='ny_email'>".__( $class_NYS_FIELDS->form_label_email() )."</label>
				<input type='email' aria-required='true' required='required' name='ny_email' id='ny_email' rel='". $class_NYS_FIELDS->form_msg_email() ."' value='". stripslashes( $vEmail ) ."' />
			</p>";
		}
		
		if( $class_NYS_FIELDS->form_label_custom() ){
			if ( getOption( 'ny_subscribe_field_placeholder' ) ){
				echo "<p>
					<input required='required' placeholder='".__( $class_NYS_FIELDS->form_label_custom() )."' title='".__( $class_NYS_FIELDS->form_label_custom() )."' type='text' name='ny_custom' id='ny_custom' rel='". $class_NYS_FIELDS->form_msg_custom() ."' value='". stripslashes( $vCustom ) ."' />
				</p>";
			}else{
				echo "<p>
					<label for='ny_custom'>". __( $class_NYS_FIELDS->form_label_custom() ) ."</label>
					<input type='text' required='required' name='ny_custom' id='ny_custom' rel='". $class_NYS_FIELDS->form_msg_custom() ."' value='". stripslashes( $vCustom ) ."' />
				</p>";
			}
		}
	}
	
	
	/***************************************************
	* ADD USERS WHEN FORM SUBMITTED
	* *************************************************/
	function nys_AddSubscriberSubmit(){
		$fileFormatting = ABSPATH . WPINC . '/formatting.php';
		$fileUser = ABSPATH . WPINC . '/user.php';
		
		if ( file_exists ( $fileFormatting ) && file_exists ( $fileUser ) ){
			require_once( $fileFormatting );
			require_once( $fileUser );
		}else{
			exit( __('Wordpress has dropped some core files!') );
		}
		
		global $class_NYS_FIELDS;
		global $vEmail, $vName, $vCustom;
		$return='';
		
		if( getOption( 'ny_subscribe_field_show_name' ) == 1){ 
			$val_name = $vName;
		}
		if( $class_NYS_FIELDS->form_label_custom() ){
			$val_custom = $vCustom;
		}
		
		if ( !is_email( $vEmail ) ) {
			$return['err'] = true;
			$return['msg'] = $class_NYS_FIELDS->form_msg_email();
		} elseif ( email_exists( $vEmail ) ){			
			$return['err'] = true;
			$return['msg'] = getOption( 'ny_subscribe_field_email_exist', __('This Email already registered') );
		} else {

			if ( getOption( 'ny_subscribe_field_show_name' ) == 1 && $val_name == ''){
				$return['err'] = true;
				$return['msg'] = $class_NYS_FIELDS->form_msg_name();
			} elseif ( $class_NYS_FIELDS->form_label_custom() && $val_custom == ''){					
				$return['err'] = true;
				$return['msg'] = $class_NYS_FIELDS->form_msg_custom();
			} else {
				$val_name			= mysql_real_escape_string($val_name);
				$val_custom		= isset($val_custom) ? sanitize_user( str_replace('@',' ', $val_custom ) ) : '';
				$clean_login	= sanitize_user( $vEmail );
				$val_pass			= nysRandomNumber(14);
				$val_id				= wp_create_user( $clean_login, $val_pass, $vEmail );
				$user = new WP_User($val_id);
				$user->set_role('unconfirmed');
				
				if ( !$val_id ){
					$return['err'] = true;
					$return['msg'] = getOption( 'ny_subscribe_field_unable_to_subscribe', __('Unable to subscribe') );
				}else{
					update_user_meta( $user->ID, 'ny_subscribe_field_custom', $val_custom );
					update_user_meta( $user->ID, 'first_name', $val_name );
					
					// SEND SUBSCRIBE CONFIRMATION EMAIL TO USER
					nysOptInEmail( 'subscribe', $vEmail, 'nyEmailSubscribeSubject', 'nyEmailSubscribeBody' );
					
					if ( !is_user_logged_in() ){
						$return['err'] = false;
						$return['msg'] = getOption( 'ny_subscribe_field_success', __('To confirm your subscription, please check your email.') );
					}
				}
			}
		}

		$cls = $return['err'] == true ? 'nys-error' : 'nys-success';
		echo '<p class="'.$cls.'">'. $return['msg'] .' </p>';
		
		if($return['err'] == false){
			if( getOption( 'ny_subscribe_field_hide_form' ) == 1){
				echo '<style type="text/css">#ny_subscribe_form{display:none}</style>';
			}
			echo '<script type="text/javascript">
							if( typeof jQuery != "undefined" ){
								jQuery(function ($){
									$("#ny_subscribe_form input:text").val("");
								});
							}
						</script>';
		}
	}
	
	/***************************************************
	* ADMIN FIELDS - DISPLAY, UPDATE
	* *************************************************/
	function nysAdminForm( $totalFields, $arrayVar, $postBtn, $msg ){
		// ADD FORM FIELDS IF NOT EXIST
		for($i = 0; $i < $totalFields; $i++){
			if( !get_option( $arrayVar[$i]['slug'] ) ){
				update_option( $arrayVar[$i]['slug'], $arrayVar[$i]['val'] );
			}
		}
	
		// UPDATE VALUES
		if( isset($_POST[$postBtn]) ){								
			for ( $i = 0; $i < $totalFields; $i++ ){
				if ( $arrayVar[$i]['type'] == 'checkbox' ){
					$mine[$i]['value'] = isset($_POST[ $arrayVar[$i]['slug'] ]) ? 1 : 0;
				}else{
					$mine[$i]['value'] = @$_POST[$arrayVar[$i]['slug']];
				}
				update_option( $arrayVar[$i]['slug'], $mine[$i]['value'] );
			}
			echo '<p class="nys-success">'. $msg . '</p>';
		}
		
		// DISPLAY FIELDS
		for ( $i = 0; $i < $totalFields; $i++ ){
			if ( $arrayVar[$i]['type'] == 'title' ){
				echo '<h3>'. __( $arrayVar[$i]['label'] ) .'</h3>';
			}else{
				$checked = get_option($arrayVar[$i]['slug']) == '1' ? 'checked="checked"' : '';
				if ( $arrayVar[$i]['type'] == 'textarea' ) {
					echo '<p id="wrapper_'. $arrayVar[$i]['slug'] .'">';
					echo '<label for="'. $arrayVar[$i]['slug'] .'" style="vertical-align:top">'. __( $arrayVar[$i]['label'] ) .'</label>';
					echo '<textarea name="'. $arrayVar[$i]['slug'] .'" id="'. $arrayVar[$i]['slug'] .'">'. stripslashes( get_option( $arrayVar[$i]['slug'] ) ) .'</textarea>';
					echo '</p>';
				}else{
					echo '<p id="wrapper_'. $arrayVar[$i]['slug'] .'">';
					echo '<label for="'. $arrayVar[$i]['slug'] .'">'. __( $arrayVar[$i]['label'] ) .'</label>';
					echo '<input '. $checked .' type="'. $arrayVar[$i]['type'] .'" name="'. $arrayVar[$i]['slug'] .'" id="'. $arrayVar[$i]['slug'] .'" value="'. stripslashes( get_option( $arrayVar[$i]['slug'] ) ) .'" />';
					echo '</p>';
				}
			}
		}
	}
	
	/***************************************************
	* DOUBLE OPT-IN EMAILS
	* *************************************************/
	function nysOptInEmail( $eType, $eTo, $eSubject, $eBody ){
		global $post;
		$blogname = stripslashes ( get_option('blogname') );
		$adminEmail = get_option('admin_email' );
		$from = NAVAYAN_SUBSCRIBE::nys_ReplaceSubstitutes( $post->ID, getOption( 'nyEmailFrom', $adminEmail ) );
		ini_set("sendmail_from","<$from>");
		$subject	= __( NAVAYAN_SUBSCRIBE::nys_ReplaceSubstitutes( null, getOption( $eSubject ) ) );
		$headers  = "MIME-Version: 1.0 \r\n";
		$headers .= "Content-type: text/html; charset=utf-8 \r\n";
		$headers .= "From: $blogname <$from>\r\n";
		$mailedBy = "-f $adminEmail";
		$mailBody = getOption( $eBody );
		$getUser = get_user_by( 'email', $eTo );
		$nysOptInUrl = get_permalink( get_nysPageID() ) . '?nysemail='. $eTo .'&nyskey='.$getUser->user_pass . '&nystype='. $eType;
		$nysOptInLabel = '';
		
		if ( $eType == 'subscribe'){
			$nysOptInLabel = getOption('ny_subscribe_optin_label', 'Click here to confirm your subscription');
		}else if($eType == 'unsubscribe'){
			$nysOptInLabel = getOption('ny_unsubscribe_optin_label', 'Click here to un-subscribe');
		}
		
		$message = "<!DOCTYPE html>
								<html>
								<head><title>". $blogname ."</title></head>
								<body style='margin: 20px'>
								". __( NAVAYAN_SUBSCRIBE::nys_ReplaceSubstitutes( null, $mailBody ) ) ."
								<br/>
								<p> <a href='". $nysOptInUrl ."' rel='nofollow' >". $nysOptInLabel ."</a></p>
								</body>
								</html>";
		@wp_mail( $eTo, $subject, $message, $headers, $mailedBy );
	}
	
?>