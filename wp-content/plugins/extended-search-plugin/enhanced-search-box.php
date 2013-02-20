<?php
/*
Plugin Name: Enhanced Search Box
Plugin URI: http://wordpress.org/extend/plugins/extended-search-plugin/
Description: Add extra functionality to default search box using jQuery.
Version: 0.6.1
Author: Jake Snyder
Author URI: http://Jupitercow.com/
Disclaimer: Use at your own risk. No warranty expressed or implied is provided.
*/

$enhanced_sb = new Enhanced_Search_Box();

class Enhanced_Search_Box
{
	var $default_text,
		$options = array();
		
	function __construct()
	{
		$this->default_text = "Search...";
		$this->options['default'] = $this->default_text;
		
		register_activation_hook(__FILE__,   array($this, 'add_options'));
		#register_deactivation_hook(__FILE__, array($this, 'delete_options'));
		
		$this->plugin_init();
	}
	
	/**
	 * Add Options
	 *
	 * @return void
	 */
	function add_options()
	{
		add_option("enhanced_sb_default", $this->default_text);
	}
	
	/**
	 * Delete Options
	 *
	 * @return void
	 */
	function delete_options()
	{
		delete_option("enhanced_sb_default");
	}
	
	function get_options()
	{
		$this->options['default'] = get_option('enhanced_sb_default');
	}
	
	/**
	 * Init
	 *
	 * @return void
	 */
	function plugin_init()
	{
		add_action( 'init',       array($this, 'register_scripts') );
		add_action( 'wp_head',    array($this, 'wp_head') );
		
		add_action( 'admin_menu', array($this, 'admin_menu') );
		
		$this->get_options();
	}
	
	/**
	 * WP Head, add scripts in the site header
	 *
	 * @return void
	 */
	function wp_head()
	{
		$this->print_scripts();
	}
	
	/**
	 * Register Scripts
	 *
	 * @return void
	 */
	function register_scripts()
	{
		wp_register_script( 'enhanced_sb-js', plugins_url('enhanced-search-box.js', __FILE__), array( 'jquery' ), '', true );
	}
	
	/**
	 * Print Scripts
	 *
	 * @return void
	 */
	function print_scripts()
	{
		$this->add_default_text();
		
		wp_enqueue_script(array(
			'jquery',
			'enhanced_sb-js'
		));
	}
	
	/**
	 * Add default search text to header js variable
	 *
	 * @return void
	 */
	function add_default_text()
	{
		?> 
<script type="text/javascript">var enhanced_sb_default = "<?php echo esc_js($this->options['default']); ?>";</script>
		<?php
	}
	
	
/* Options Page
-------------------------------------------------------------------------------------- */
	
	/**
	 * Setup options page by adding it to the menu
	 *
	 * @return void
	 */
	function admin_menu()
	{
		add_options_page('Enhanced Search Box', 'Enhanced Search Box', 'manage_options', 'enhancedsb', array($this, 'add_options_page'));
	}
	
	/**
	 * Create the options page
	 *
	 * @return void
	 */
	function add_options_page()
	{
		if (! empty($_POST) && isset($_POST['submit']) ) $this->update_options();
		
		?> 
		<div class="wrap">
			<h2>Enhanced Search Box Options</h2>
			<?php $this->options_form(); ?> 
		</div>
		<?php
	}
	
	/**
	 * Update options
	 *
	 * @return void
	 */
	function update_options()
	{
		$updated = false;
		
		if (! empty($_POST) && isset($_POST['enhanced_sb_default']) )
		{
			update_option( 'enhanced_sb_default', esc_sql($_POST['enhanced_sb_default']) );
			$updated = true;
		}
		
		if ( $updated )
		{
			?> 
			<div id="message" class="updated fade">
				<p>Options updated</p>
			</div>
			<?php
			
			add_action( 'admin_menu', 'update_menu_items' );
			$this->get_options();
		}
		else
		{
			?> 
			<div id="message" class="error fade">
				<p>Unable to update options</p>
			</div>
			<?php
		}
	}
	
	/**
	 * Options page update form
	 *
	 * @return void
	 */
	function options_form()
	{
		?> 
		<form method="post">
			<table class="form-table">
				<tr valign="top">
					<th scope="row"><label for="enhanced_sb_default">Default search box text:</label></th>
					<td><input style="width:250px" type="text" name="enhanced_sb_default" value="<?php echo esc_attr($this->options['default']); ?>" /></td>
				</tr>
			</table>
			<p class="submit"><input type="submit" name="submit" value="Save Changes" class="button-primary" /></p>
		</form>
		<?php
	}
}

?>