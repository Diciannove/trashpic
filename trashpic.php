<?php
/*
Plugin Name: TrashPic
Plugin URI: http://www.life-smile.eu
Version: 0.0.1
Author: Paolo Selis - Lorenzo Novaro
Author URI: http://19.coop
Description: Monitoring system
*/


// don't load directly
if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}
load_plugin_textdomain('TRASHPIC-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
//wp_die(  WP_PLUGIN_DIR . '/trashpic/languages');
//load_plugin_textdomain('TRASHPIC-plugin',  WP_PLUGIN_DIR . '/trashpic/languages/');


define( 'TRASHPIC_VERSION', '0.0.1' );
define( 'TRASHPIC_RELEASE_DATE', date_i18n( 'F j, Y', '1375505016' ) );
define( 'TRASHPIC_DIR', WP_PLUGIN_DIR . '/trashpic' );
define( 'TRASHPIC_URL', WP_PLUGIN_URL . '/trashpic' );

global $wp_version;
if (version_compare($wp_version,"2.5.1","<")){
	exit('[TRASHPIC plugin - ERROR]: At least Wordpress Version 2.5.1 is needed for this plugin!');
}


/**
 * Aggiungo/modifico le colonne che mi servono
 * @param unknown $columns
 */

function trashpic_report_columns($columns) {

	unset(
			$columns['title'],
			$columns['date']
	);
	$new_columns = array(
			'title' => __('report_number', 'TRASHPIC-plugin'),
			'approved' => __('approved', 'TRASHPIC-plugin'),
			'investigated' => __('investigated', 'TRASHPIC-plugin'),
	);
	return array_merge( $new_columns,$columns);
}


add_filter('manage_trashpic-report_posts_columns' , 'trashpic_report_columns');

function manage_trashpic_report_columns( $column, $post_id ){
	global $post;
	switch( $column ) {
		case 'investigated' :
		case 'approved' :
			/* Get the post meta. */
			$col = get_post_meta( $post_id, $column, true );
			if ( $col )
				echo _e('yes','TRASHPIC-plugin');
			else
				echo _e('no','TRASHPIC-plugin');
			break;
		
	}
	
}

add_action( 'manage_trashpic-report_posts_custom_column', 'manage_trashpic_report_columns', 10, 2 );





if(!class_exists('Trashpic'))
{
	class Trashpic
	{
		/**
		 * Construct the plugin object
		 */
		public function __construct()
		{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$Trashpic_Settings = new Trashpic_Settings();

			// Register custom post types
			require_once(sprintf("%s/post-types/trashpic_report.php", dirname(__FILE__)));
			$Trashpic_Report = new Trashpic_Report();
			
			
		} 

		
		// END public function __construct

		/**
		 * Activate the plugin
		 */
		public static function activate()
		{
			// Do nothing
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			// Do nothing
		} // END public static function deactivate
		
		
	} // END class Trashpic
} // END if(!class_exists('Trashpic'))

if(class_exists('Trashpic'))
{
	// Installation and uninstallation hooks
	register_activation_hook(__FILE__, array('Trashpic', 'activate'));
	register_deactivation_hook(__FILE__, array('Trashpic', 'deactivate'));

	// instantiate the plugin class
	$trashpic = new Trashpic();

	// Add a link to the settings page onto the plugin page
	if(isset($trashpic))
	{
		// Add the settings link to the plugins page
		function plugin_settings_link($links)
		{
			$settings_link = '<a href="options-general.php?page=trashpic">'.__('Settings','TRASHPIC-plugin').'</a>';
			array_unshift($links, $settings_link);
			return $links;
		}
		function register_plugin_styles() {
			wp_register_style( 'trashpic', plugins_url( 'trashpic/css/trashpic.css' ) );
			wp_enqueue_style('trashpic');
		}
		
		add_action( 'admin_print_scripts-post-new.php', 'trashpic_admin_script', 11 );
		add_action( 'admin_print_scripts-post.php', 'trashpic_admin_script', 11 );
		
		function trashpic_admin_script() {
			global $post_type;
			if( 'trashpic-report' == $post_type )
			  wp_register_style( 'trashpic', plugins_url( 'trashpic/css/trashpic.css' ) );
			  wp_enqueue_style('trashpic');
			//wp_enqueue_script( 'portfolio-admin-script', get_stylesheet_directory_uri() . '/admin.js' );
		}		
		
		$plugin = plugin_basename(__FILE__);
		//$plugin = WP_PLUGIN_DIR . '/trashpic/'.__FILE__;
    //wp_die($plugin);
				add_filter("plugin_action_links_$plugin", 'plugin_settings_link');
		add_action( 'wp_enqueue_scripts', 'register_plugin_styles' );
		
	}
}

?>