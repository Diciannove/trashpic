<?php
/*
Plugin Name: TrashPic
Plugin URI: http://www.life-smile.eu
Version: 0.4
Author: Paolo Selis - Lorenzo Novaro
Author URI: http://19.coop
Description: Monitoring system
*/


// La pagina non può essere caricata direttamente
if (!function_exists('is_admin')) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/* Carico il file di lingua*/
load_plugin_textdomain('TRASHPIC-plugin', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');


$role = get_role( 'author' );
$role->add_cap( 'trashpic' );


global $wp_roles;


//$ra = get_role( 'trashpic_contributor');
//print_r($ra);

// add a new role, same arguments as add_role()
//$wp_roles->add_role( $role, $display_name, $capabilities )


/* Definisco alcuni valori che possono tornarmi utili*/
define( 'TRASHPIC_VERSION', '0.0.2' );
define( 'TRASHPIC_RELEASE_DATE', date_i18n( 'F j, Y', '1375505016' ) );
define( 'TRASHPIC_DIR', WP_PLUGIN_DIR . '/trashpic' );
define( 'TRASHPIC_URL', WP_PLUGIN_URL . '/trashpic' );
define( 'TRASHPIC_URL_JS', TRASHPIC_URL . '/js' );
define( 'TRASHPIC_URL_CSS', TRASHPIC_URL . '/css' );
define( 'TRASHPIC_URL_HELP_IMG', TRASHPIC_URL . '/contextual_help/img/' );

$trashpic_default_options = array('trashpic_default_latitude' =>44.16621,
		                              'trashpic_default_longitude' => 8.27123,
													        'trashpic_default_zoom_level' => 13,
																	'trashpic_only_registered_users' => 1,
																	'trashpic_polygon_in_map' => 1,
																	'trashpic_polygon_in_report' => 1
);



$trashpic_category = array('LAT'=>__('Laterizi', 'TRASHPIC-plugin'),
													 'MOB'=>__('Mobili', 'TRASHPIC-plugin'),
													 'MAT'=>__('Materassi', 'TRASHPIC-plugin'),
													 'ELE'=>__('Elettrodomestici', 'TRASHPIC-plugin'),
													 'VEF'=>__('Vetri e finestre', 'TRASHPIC-plugin'),
													 'PNE'=>__('Pneumatici', 'TRASHPIC-plugin'),
													 'LEG'=>__('Legname lavorato', 'TRASHPIC-plugin'),
													 'AUT'=>__('Carcasse di veicoli o parti', 'TRASHPIC-plugin'),
													 'FER'=>__('Rottami ferrosi', 'TRASHPIC-plugin'),
													 'PLA'=>__('Oggetti in plastica', 'TRASHPIC-plugin'),
													 'ALT'=>__('Altro', 'TRASHPIC-plugin'));



global $wp_version;
if (version_compare($wp_version,"2.5.1","<")){
	exit('[TRASHPIC plugin - ERROR]: At least Wordpress Version 2.5.1 is needed for this plugin!');
}



/**
 * Aggiungo/modifico le colonne che mi servono nell'area amministrativa
 *  del post-type trashpic
 * @param unknown $columns
 */

function trashpic_report_columns($columns) {

	/* Tolgo il titolo e la data */
	unset(
			$columns['date'],
			$columns['title'],
			$columns['title'],
			$columns['cb']
);
	
	
	
	$new_columns = array(
			'cb' => '<input type="checkbox" />', 
			'title' => __('report_number', 'TRASHPIC-plugin'),
			'category' => __('category', 'TRASHPIC-plugin'),
			'approved' => __('approved', 'TRASHPIC-plugin'),
			'investigated' => __('investigated', 'TRASHPIC-plugin'),
	);
	return array_merge( $new_columns,$columns);
}

add_filter('manage_trashpic-report_posts_columns' , 'trashpic_report_columns');

/**
 * Cambio le etichette delle colonne approved e investigated 
 * della tabella amministrativa del post-type trashpic
 * @param unknown $columnhttp://life-smile.eu/wp/trashpic-report/2014030397993/
 * @param unknown $post_id
 */
function manage_trashpic_report_columns( $column, $post_id ){
	global $post;
	global $trashpic_category;
	switch( $column ) {
		case 'category' :
			$col = get_post_meta( $post_id, $column, true );
			echo $trashpic_category[$col];
			break;
		case 'investigated' :
		case 'approved' :
			/* Get the post meta. */
			$col = get_post_meta( $post_id, $column, true );
			if ( $col == '1' )
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
		
		
		private $Trashpic_Settings;
		private $default_option;
		
		/**
		 * Costruttore dell'ogetto principale del plug-in
		 */
		public function __construct() 	{
			// Initialize Settings
			require_once(sprintf("%s/settings.php", dirname(__FILE__)));
			$this->Trashpic_Settings = new Trashpic_Settings();

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
			
			$result = add_role(
					'trashpic_contributor',
					__( 'trashpic Contributor' ),
					array (
								 'publish_trashpic-report' => true,
								 'edit_trashpic-report' => true,
								 'edit_others_trashpic-report' => true,
								 'delete_trashpic-report' => true,
								 'delete_others_trashpic-report' => true,
								 'read_private_trashpic-report' => true,
								 'edit_trashpic-report' => true,
								 'delete_trashpic-report' => true,
								 'read_trashpic-report' => true,
							   'edit_published_trashpic-report' => true,
								 // more standard capabilities here
								'read' => true,
								 
								)
			);
			
			
			$role = get_role( 'administrator');
			$role->add_cap('read_trashpic-report');
			$role->add_cap('edit_trashpic-report');
			$role->add_cap('edit_trashpic-reports');
			$role->add_cap('delete_trashpic-report');
				
			
		} // END public static function activate

		/**
		 * Deactivate the plugin
		 */
		public static function deactivate()
		{
			remove_role( 'trashpic_contributor' );

			$role = get_role( 'administrator');
			$role->remove_cap('read_trashpic-report');
			$role->remove_cap('edit_trashpic-report');
			$role->remove_cap('edit_trashpic-reports');
			$role->remove_cap('delete_trashpic-report');
				
			
			// Do nothing
		} // END public static function deactivate
		
		
		public function getSettings(){
			return $this->Trashpic_Settings;
		}
		
		
		
		
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
		
		require_once(sprintf("%s/contextual_help/trashpic_help.php", dirname(__FILE__)));
		add_action( 'load-post.php', array( 'trashpic_help', 'init' ) );
		add_action( 'load-post-new.php', array( 'trashpic_help', 'init' ) );
		add_action( 'load-edit.php', array( 'trashpic_help', 'init' ) );
		add_action( 'load-edit-tags.php', array( 'trashpic_help', 'init' ) );
		
		
		// Add the settings link to the plugins page
		function plugin_settings_link($links) {
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
		
		function hide_publishing_actions(){
			$my_post_type = 'trashpic-report';
			global $post;
			if($post->post_type == $my_post_type){
				echo '
                <style type="text/css">
                    #misc-publishing-actions,
                    #minor-publishing-actions{
                        display:none;
                    }
                </style>';
			}
		}
		add_action('admin_head-post.php', 'hide_publishing_actions');
		add_action('admin_head-post-new.php', 'hide_publishing_actions');		
		
		function hide_add_new_custom_type()
		{
			global $submenu;
			// replace my_type with the name of your post type
			unset($submenu['edit.php?post_type=trashpic-report'][10]);
		}
		add_action('admin_menu', 'hide_add_new_custom_type');
		
		function hd_add_buttons() {
			global $pagenow;
			if(is_admin()){
				if($pagenow == 'edit.php' && $_GET['post_type'] == 'trashpic-report'){
					echo '<style type="text/css">.add-new-h2{display: none;}</style> ';
				}
			}
		}
		add_action('admin_head','hd_add_buttons');		
		
		
		
		function remove_quick_edit( $actions ) {
			global $post;
			if( $post->post_type == 'trashpic-report' ) {
				unset($actions['edit'] );
				unset($actions['view'] );
				unset($actions['trash'] );
				unset($actions['inline hide-if-no-js']);
			}
			return $actions;
		}
		
		if (is_admin()) {
			add_filter('post_row_actions','remove_quick_edit',10,2);
		}		
		
		
		add_filter( 'redirect_post_location', 'wpse_124132_redirect_post_location' );
		/**
		 * Redirect to the edit.php on post save or publish.
		*/
		function wpse_124132_redirect_post_location( $location ) {
		
			if ( isset( $_POST['save'] )  )
				return admin_url( "edit.php?post_type=trashpic-report" );
		
			return $location;
		}		
		
		add_action( 'views_edit-trashpic-report', 'remove_edit_post_views' );
		
		
function restrict_manage_authors() {
	if (isset($_GET['post_type']) && post_type_exists($_GET['post_type']) && in_array(strtolower($_GET['post_type']), array('trashpic-report'))) {
		
		global $trashpic_category;
		
		echo "<select id='category'  name='admin_filter_category'>";
		echo "<option value=''>". __('all_category_filter','TRASHPIC-plugin')." </option>";
		foreach ($trashpic_category as $c=>$v){
			if($_GET['admin_filter_category']==$c )
			echo "<option selected value='".$c."'>".$v."</option>";
			else
			echo "<option value='".$c."'>".$v."</option>";
		}
		echo "</select>";
		
		
	}
}
add_action('restrict_manage_posts', 'restrict_manage_authors');		


add_filter( 'parse_query', 'wpse45436_posts_filter' );
/**
 * if submitted filter by post meta
 *
 * make sure to change META_KEY to the actual meta key
 * and POST_TYPE to the name of your custom post type
 * @author Ohad Raz
 * @param  (wp_query object) $query
 *
 * @return Void
*/
function wpse45436_posts_filter( $query ){
	global $pagenow;
	$type = 'post';
	if (isset($_GET['post_type'])) {
		$type = $_GET['post_type'];
	}
	if ( 'trashpic-report' == $type && is_admin() && $pagenow=='edit.php' && isset($_GET['admin_filter_category']) && $_GET['admin_filter_category'] != '') {
		$query->query_vars['meta_key'] = 'category';
		$query->query_vars['meta_value'] = $_GET['admin_filter_category'];
	}
}


function remove_edit_post_views( $views ) {
			//unset($views['all']);
			unset($views['publish']);
			unset($views['pending']);
			unset($views['trash']);
			
			$views['pre'] = '<a class="'.$class.'" href="'.admin_url().'edit.php?post_type=trashpic-report&pre=pre">Da approvare</a>';
			$views['app'] = '<a class="'.$class.'" href="'.admin_url().'edit.php?post_type=trashpic-report&app=app">Approvate</a>';
			$views['rif'] = '<a class="'.$class.'" href="'.admin_url().'edit.php?post_type=trashpic-report&rif=rif">Rifiutate</a>';
			return $views;
}
		
		
		/*
		$test = '[
		 {"lon": 8.23432, "lat": 44.19632},
		 {"lon": 8.23741, "lat": 44.18968},
		 {"lon": 8.22883, "lat": 44.18007},
		 {"lon": 8.23329, "lat": 44.16998},
		 {"lon": 8.27998, "lat": 44.14609},
		 {"lon": 8.30127, "lat": 44.15520},
		 {"lon": 8.28410, "lat": 44.18106},
		 {"lon": 8.25595, "lat": 44.20124}
	   ]';
		 	*/
		 //print_r(json_decode($test));
		 	
		
		add_action('pre_get_posts', 'my_special_list');
		add_action('app_get_posts', 'my_special_list');
		add_action('rif_get_posts', 'my_special_list');
		
		function my_special_list( $q ) {
			if(is_admin()) $scr = get_current_screen();
			if ( is_admin() && ( $scr->base === 'edit' ) && $q->is_main_query() ) {
				// To target onlhttp://life-smile.eu/wp/trashpic-report/2014030397993/y a post type uncomment following line and adjust post type name
				// if ( $scr->post_type !== 'post' ) return;
				// if you change the link in function above adjust next line accordingly
				$pre = filter_input(INPUT_GET, 'pre', FILTER_SANITIZE_STRING);
				
				if ( $pre === 'pre' ) {
					// adjust meta query to fit your needs
					$meta_query = array( 'key' => 'approved', 'value' =>'-1', );
					$q->set( 'meta_query', array($meta_query) );
				}
				
				$pre = filter_input(INPUT_GET, 'app', FILTER_SANITIZE_STRING);
				
				if ( $pre === 'app' ) {
					// adjust meta query to fit your needs
					$meta_query = array( 'key' => 'approved', 'value' => '1', );
					$q->set( 'meta_query', array($meta_query) );
				}
				
				$pre = filter_input(INPUT_GET, 'rif', FILTER_SANITIZE_STRING);
				
				if ( $pre === 'rif' ) {
					// adjust meta query to fit your needs
					$meta_query = array( 'key' => 'approved', 'value' => '0', );
					$q->set( 'meta_query', array($meta_query) );
				}
				
			}
		}		
		
		
	}
	
	print_r(get_option( 'trashpic_default_latitude')  );
	
	function trashpic_map_shortcode( $atts ) {
	
		
		$content .= '<fieldset><div class="gllpMap olMap" id="map"></div></fieldset>';
		return $content;
	
	}
	add_shortcode( 'trashpic_map', 'trashpic_map_shortcode' );
	
	
	/**
	 * Questo shortcode viene chiamato dalla stringa  [trashpic_report]
	 * e fa comparire il form di segnalazione
	 * @param unknown $atts
	 * @return unknown
	 */
	function trashpic_submit_report_shortcode( $atts ) {

		
		if(get_trashpic_option( 'trashpic_only_registered_users')){
			// se l'utente non è loggato, allora nulla*/
			if ( !is_user_logged_in() ) return __('only_registered_users','TRASHPIC-plugin') ;
		}
		
		
		//$content = "".TRASHPIC_URL_JS.'/OpenLayers.js';
		ob_start();
		include(TRASHPIC_DIR."/report_shortcode/report_form.php");
		$content .= ob_get_clean();
		return $content;
	
	}
	add_shortcode( 'trashpic_report', 'trashpic_submit_report_shortcode' );
	
	/**
	 * Ora devo includere gli script necessari al form
	 * ma per evitare che venano inclusi in tutte le pagine
	 * verific che ci sia il mio shortcode
	*/
	
	

	
	
	function trashpic_submit_report_shortcode_include() {
		global $post;
		if ( strstr( $post->post_content, '[trashpic_report]' ) ) {
			wp_enqueue_style( 'trashpic-report-style', TRASHPIC_URL_CSS.'/trashpic-report.css' );
			//wp_enqueue_script('OpenLayers', TRASHPIC_URL_JS.'/OpenLayers.js', array('jquery') );
		  wp_enqueue_script('jquery-position-picker', TRASHPIC_URL_JS.'/jquery-position-picker.js', array('OpenLayers'),'1.0.0', true );
			wp_enqueue_script('OpenLayers', 'http://openlayers.org/api/OpenLayers.js"', array('jquery') );
			if(get_trashpic_option( 'trashpic_polygon_in_report'))
			$polygon = json_decode(get_trashpic_option( 'trashpic_polygon'));
				
			
			$par = array (
					'latitude' => get_trashpic_option( 'trashpic_default_latitude') ,
					'longitude' => get_trashpic_option( 'trashpic_default_longitude') ,
					'zoom' => get_trashpic_option( 'trashpic_default_zoom_level') ,
					'polygon' => $polygon
			);
				
			
			wp_localize_script( 'jquery-position-picker', 'trashpic_setting', $par );
				
		}
		if ( strstr( $post->post_content, '[trashpic_map]' ) ) {
			wp_enqueue_style( 'trashpic-report-style', TRASHPIC_URL_CSS.'/trashpic-report.css' );
			//wp_enqueue_script('OpenLayers', TRASHPIC_URL_JS.'/OpenLayers.js', array('jquery') );
			wp_enqueue_script('OpenLayers', 'http://openlayers.org/api/OpenLayers.js"', array('jquery') );
			
			wp_enqueue_script('trashpic_map', TRASHPIC_URL_JS.'/trashpic_map.js', array('OpenLayers'),'1.0.0', true );
			
			
			
			$posts = get_posts(array(
					'post_type'   => 'trashpic-report',
					'post_status' => 'publish',
					'meta_query' => array( array('key' => 'approved','value'=>1)),
					'posts_per_page' => -1,
					'fields' => 'ids'
			)
			);
			
			foreach($posts as $p){
				$img = @get_post_meta($p, 'picture', true);
				$tpost[] = array("id"=>$p,"lat"=>get_post_meta($p,"latitude",true),
						                      "lon"=>get_post_meta($p,"longitude",true),
																	"img"=>$img['url']
				 );
			}
			
			
			if(get_trashpic_option( 'trashpic_polygon_in_map'))
				$polygon = json_decode(get_trashpic_option( 'trashpic_polygon'));
				
			$par = array (
					'latitude' => get_trashpic_option( 'trashpic_default_latitude') ,
					'longitude' => get_trashpic_option( 'trashpic_default_longitude') ,
					'zoom' => get_trashpic_option( 'trashpic_default_zoom_level') ,
					'polygon' => $polygon,
					'tmarkers' => $tpost
						
					
			);
				
			wp_localize_script( 'trashpic_map', 'trashpic_setting', $par );
			
		
		}
		
		
	}
	add_action( 'wp_print_styles', 'trashpic_submit_report_shortcode_include' );
	
	function get_trashpic_option($var){
		global $trashpic_default_options;
		$value = get_option($var, $trashpic_default_options[$var]);
		if(trim($value) == "") $value = $trashpic_default_options[$var];
		return $value;
	}
	
	
	
	
	
	
	/**
	 * Intercetto il submit del form e faccio il salvataggio
	*/
	function add_trashpic_report(){
	
		if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'trashpic_report' ){
	
			if(get_trashpic_option( 'trashpic_only_registered_users')){
				// se l'utente non è loggato, allora nulla*/
				if ( !is_user_logged_in() ) return;
				
				global $current_user;
				get_currentuserinfo();
				$logged_in_user = $current_user->ID;				
				
			}

			$upload_overrides = array( 'test_form' => FALSE );
			$file_array = array(
					'name'      => $_FILES['file']['name'],
					'type' 	    => $_FILES['file']['type'],
					'tmp_name'	=> $_FILES['file']['tmp_name'],
					'error'	    => $_FILES['file']['error'],
					'size'     	=> $_FILES['file']['size'],
			);
			
			if ( !empty( $file_array['name'] ) ) {
				$uploaded_file = wp_handle_upload( $file_array, $upload_overrides );
				// checks the file type and stores in in a variable
				$wp_filetype = wp_check_filetype( basename( $uploaded_file['file'] ), null );

			
				if ( $uploaded_file ) {
					$wp_filetype = wp_check_filetype( basename( $uploaded_file['file'] ), null );
					//$wp_filetype = $uploaded_file['type'];
					$filename = $uploaded_file['file'];
					$wp_upload_dir = wp_upload_dir();
					$attachment = array(
							'guid' => $wp_upload_dir['url'] . '/' . basename( $filename ),
							'post_mime_type' => $wp_filetype,
							'post_title' => preg_replace('/\.[^.]+$/', '', basename($filename)),
							'post_content' => '',
							'post_status' => 'inherit'
					);
					
					$attach_id = wp_insert_attachment( $attachment, $filename);
					require_once( ABSPATH . 'wp-admin/includes/image.php' );
					$attach_data = wp_generate_attachment_metadata( $attach_id, $filename );
					wp_update_attachment_metadata( $attach_id, $attach_data );
				}
				
			}
			
			
			/* recupero l'id dell'utente*/
			global $current_user;
			$user_id		= $current_user->ID;
			/* genero un id univoco della segnalazione*/
			$postTitle = date("YmdB").rand(10,100);
			/* prendo i campi del form */
			$latitude = trim($_POST['latitude']);
			$longitude = trim($_POST['longitude']);
			$category = trim($_POST['category']);
			$public_note = trim($_POST['public_note']);
				
			
			global $error_array;
			$error_array = array();
	
			/* eseguo i controlli sui campi che mi interessano */
			if (empty($postTitle)) $error_array[]='Please add a title.';
			if (empty($latitude))  $error_array[] = __('error_longitude_mandatory','TRASHPIC-plugin');
			if (empty($longitude))  $error_array[] =__('error_latitude_mandatory','TRASHPIC-plugin');
			//if (empty($category))  $error_array[] =__('error_category_mandatory','TRASHPIC-plugin');
				
			if (count($error_array) == 0){
	
			 $post_information = array(
			 		'post_title' => $postTitle,
			 		'post_type' => 'trashpic-report',
			    'post_status' => 'publish',
			 		'post_author' => $logged_in_user
			 		
			 );
	
			 $post_id = wp_insert_post($post_information);
	
			 if($post_id) {
			 	__update_post_meta( $post_id, 'latitude', $latitude);
			 	__update_post_meta( $post_id, 'longitude', $longitude);
			 	__update_post_meta( $post_id, 'category', $category);
			 	__update_post_meta( $post_id, 'approved', '-1');
			 	__update_post_meta( $post_id, 'public_note', $public_note);
			 	__update_post_meta_img( $post_id, 'picture', $_FILES);
			 		
			 }
	
				global $notice_array;
				$notice_array = array();
				$notice_array[] = __('notice_success_new_report','TRASHPIC-plugin');
				add_action('trashpic-notice', 'trashpic_notices');
			} else {
				add_action('trashpic-notice', 'trashpic_errors');
			}
		}
	}
	
	add_action('init','add_trashpic_report');
	
	
	/**
	 * Updates post meta for a post. It also automatically deletes or adds the value to field_name if specified
	 *
	 * @access     protected
	 * @param      integer     The post ID for the post we're updating
	 * @param      string      The field we're updating/adding/deleting
	 * @param      string      [Optional] The value to update/add for field_name. If left blank, data will be deleted.
	 * @return     void
	*/
	function __update_post_meta( $post_id, $field_name, $value = '' ) {
		if ( empty( $value ) OR ! $value ) 	{
			delete_post_meta( $post_id, $field_name );
		} elseif ( ! get_post_meta( $post_id, $field_name ) ) 	{
			add_post_meta( $post_id, $field_name, $value );
		} else 	{
			update_post_meta( $post_id, $field_name, $value );
		}
	}
	
	
	function __update_post_meta_img( $post_id, $field_name, $file ) {
		$upload = wp_upload_bits($file[$field_name]['name'], null, file_get_contents($file[$field_name]['tmp_name']));
		if(isset($upload['error']) && $upload['error'] != 0) {
			wp_die('There was an error uploading your file. The error is: ' . $upload['error']);
		} // end if/else
	
		if ( ! get_post_meta( $post_id, $field_name ) )	{
			add_post_meta( $post_id, $field_name, $upload );
		} else 	{
			update_post_meta( $post_id, $field_name, $upload );
		}
	}
	
	
	
	function trashpic_errors(){
	
		echo '<script>jQuery(function($){setTimeout(function() {$(".trashpic-error").hide("slow");},10000);});</script>';
		global $error_array;
		foreach($error_array as $error){
			echo '<div class="trashpic-error">' . $error . '</div>';
		}
	}
	
	function trashpic_notices(){
		echo '<script>jQuery(function($){setTimeout(function() {$(".trashpic-notice").hide("slow");},10000);});</script>';
	
		global $notice_array;
		foreach($notice_array as $notice){
			echo '<div class="trashpic-notice">' . $notice . '</div>';
		}
	}
	
	
	/*
	function include_template_files() {
		global $wp;
		$plugindir = dirname( __FILE__ );
	
		
		if ('trashpic-report' == get_post_type() ){
			echo entro;
			$templatefilename = 'single-post-trashpic-report.php';
			$template = $plugindir . '/theme_files/' . $templatefilename;
			return $template;
		}
	}
	add_filter( 'template_include', 'include_template_files' );	
	
	*/
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}











































?>