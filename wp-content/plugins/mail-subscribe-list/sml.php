<?php
/*
Plugin Name: Mail Subscribe List
Plugin URI: http://www.webfwd.co.uk/wp-plugins/mail-subscribe-list.php
Description: This plugin allows users to enter their name and email address to subscribe to a list which is available to view and export in the wordpress admin.
Version: 1.1.2
Author: Richard Leishman t/a Webforward
Author URI: http://www.webfwd.co.uk/
License: GPL


Copyright 2012 Richard Leishman t/a Webforward  (email : richard@webfwd.co.uk)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License, version 2, as 
published by the Free Software Foundation.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

GNU General Public License: http://www.gnu.org/licenses/gpl.html

*/

define(PLUGIN_NAME, "Mail Subscribe List");
define(PLUGIN_VER, "1.1.2");

// Plugin Activation
function sml_install()
{
    global $wpdb;
    $table = $wpdb->prefix."sml";
    $structure = "CREATE TABLE $table (
        id INT(9) NOT NULL AUTO_INCREMENT,
        sml_name VARCHAR(200) NOT NULL,
        sml_email VARCHAR(200) NOT NULL,
	UNIQUE KEY id (id)
    );";
    $wpdb->query($structure);
}
register_activation_hook( __FILE__, 'sml_install' );

// Left Menu Button
function register_sml_menu() {
	add_menu_page('Subscribers', 'Subscribers', 'add_users', dirname(__FILE__).'/index.php', '',   plugins_url('sml-admin-icon.png', __FILE__), 58);
}
add_action('admin_menu', 'register_sml_menu');

// Generate Subscribe Form 


function smlsubform($atts=array()){
	extract(shortcode_atts(array(
		"prepend" => '',  
        "showname" => true,
		"nametxt" => 'Name:',
		'nameholder' => 'Name...',
		"emailtxt" => 'Email:',
		'emailholder' => 'Email Address...',
		"submittxt" => 'Submit',
		"jsthanks" => false,
		"thankyou" => 'Thank you for subscribing to our mailing list'
    ), $atts));
	
	$return = '<form class="sml_subscribe" method="post"><input name="sml_subscribe" type="hidden" value="1">';
	
	if ($prepend) $return .= '<p class="prepend">'.$prepend.'</p>';
	
	if ($_POST['sml_subscribe'] && $thankyou) { 
		if ($jsthanks) {
			$return .= "<script>window.onload = function() { alert('".$thankyou."'); }</script>";
		} else {
			$return .= '<p class="sml_thankyou">'.$thankyou.'</p>'; 
		}
	}
	
	
	if ($showname) $return .= '<p class="sml_name"><label for="sml_name">'.$nametxt.'</label><input class="" placeholder="'.$nameholder.'" name="sml_name" type="text" value=""></p>';
	$return .= '<p class="sml_email"><label for="sml_email">'.$emailtxt.'</label><input class="" name="sml_email" placeholder="'.$emailholder.'" type="text" value=""></p>';
	$return .= '<p class="sml_submit"><input name="submit" class="btn sml_submitbtn" type="submit" value="'.$submittxt.'"></p>';
	$return .= '</form>';
 	return $return;
}
add_shortcode( 'smlsubform', 'smlsubform' );

// Handle form Post
if ($_POST['sml_subscribe']) {
	$name = $_POST['sml_name'];
	$email = $_POST['sml_email'];
	if (is_email($email)) {
		
		$exists = mysql_query("SELECT * FROM ".$wpdb->prefix."sml where sml_email like '".$wpdb->escape($email)."' limit 1");
		if (mysql_num_rows($exists) <1) {
			$wpdb->query("insert into ".$wpdb->prefix."sml (sml_name, sml_email) values ('".$wpdb->escape($name)."', '".$wpdb->escape($email)."')");
		}
	}
}


?>