<?php

if(!class_exists('Trashpic_Report')) {

	
	
	/**
	 * 
	 * @author paolo
	 *
	 */
	class Trashpic_Report {
		
		const POST_TYPE	= "trashpic-report";
		private $_meta	= array(
			'label',
			'link',
			'litter_n',
			'latitude',
		  'longitude',
		  'location',
			'pilotarea',
			'id_area',
			'investigated',
			'smile_phone',
			'approved',
			'notified',
			'notified_date',
			'solved',
			'solved_date',
			'note',
			'public_note',
			'picture',
			'category',
		);

    /**
     * 
     */ 
    public function __construct() {
    	
     	// register actions

    	add_action('init', array(&$this, 'init'));
    	add_action('admin_init', array(&$this, 'admin_init'));
    	add_action('post_edit_form_tag', array(&$this, 'update_edit_form') );
    	 
    } // END public function __construct()

    /**
     * 
     */
    public function update_edit_form() {
    	echo ' enctype="multipart/form-data"';
    } // end update_edit_form
    
    /**
		 * hook into WP's init action hook
		 */
    public function init() {
     	// Initialize Post Type
     	$this->create_post_type();
     	$this->create_taxonomies();
     	add_action('save_post', array(&$this, 'save_post'));
     	
     	
     	add_filter('manage_trashpic-report_posts_columns' , array(&$this, 'trashpic_report_columns'));
     	add_action( 'manage_trashpic-report_posts_custom_column', array(&$this, 'manage_trashpic_report_columns') , 10, 2 );
     } // END public function init()

     
     /**
      * Create the post type
      */
    public function create_post_type()  {
    	
    	
    	$capabilities = array(
    			'publish_posts' => 'publish_trashpic-report',
    			'edit_posts' => 'edit_trashpic-report',
    			'edit_others_posts' => 'edit_others_trashpic-report',
    			'delete_posts' => 'delete_trashpic-report',
    			'delete_others_posts' => 'delete_others_trashpic-report',
    			'read_private_posts' => 'read_private_trashpic-report',
    			'edit_post' => 'edit_trashpic-report',
    			'delete_post' => 'delete_trashpic-report',
    			'read_post' => 'read_trashpic-report',
    			'edit_published_posts' => 'edit_published_trashpic-report',
    			
    	);
    	 
    	
     register_post_type(self::POST_TYPE,
     			              array(
                              'labels' => array(
                                                'name' => __(sprintf('%ss', ucwords(str_replace("_", " ", self::POST_TYPE)))),
                                                'singular_name' => __(ucwords(str_replace("_", " ", self::POST_TYPE)))
                                                ),
                              'public' => true,
                              'has_archive' => true,
                              'show_ui' => true,
                              
                              'rewrite' => array('slug' => 'portfolio', 'with_front' => true),
                              'description' => __("report_post_type_description"),
                              'supports' => array('title'),
     			              		  
     			              		  'capability_type' => 'trashpic-report',
                              'capabilities' => $capabilities,
     			              		
                             )

                       );
     }

     
     
     /**
      * Create taxonomies
      */
     public function create_taxonomies() {
     	
     	
     	$labels = array(
     			'name' => __( 'litter_type_tax_name', 'TRASHPIC-plugin' ),
     			'singular_name' => __( 'litter_type_tax_name_singular', 'TRASHPIC-plugin'),
     			'search_items' => __( 'search_litter_type', 'TRASHPIC-plugin' ),
     			'all_items' => __( 'all_litter_type', 'TRASHPIC-plugin' ),
     			'parent_item' => __( 'parent_litter_type', 'TRASHPIC-plugin' ),
     			'parent_item_colon' => __( 'parent_litter_type_colon', 'TRASHPIC-plugin' ),
     			'edit_item' => __( 'edit_litter_type', 'TRASHPIC-plugin' ),
     			'update_item' => __( 'update_litter_type', 'TRASHPIC-plugin' ),
     			'add_new_item' => __( 'add_new_litter_type', 'TRASHPIC-plugin'  ),
     			'new_item_name' => __( 'new_litter_type_name', 'TRASHPIC-plugin' ),
     			'menu_name' => __( 'litter_type_menu_name', 'TRASHPIC-plugin' ),
     	);
     
     	$args = array(
     			'hierarchical' => true,
     			'labels' => $labels,
     			'show_ui' => true,
     			'show_admin_column' => true,
     			'query_var' => true,
     			'rewrite' => array( 'slug' => 'litter_type' ),
     			'capabilities' => array (
            'manage_terms' => 'edit_trashpic-report', //by default only admin
            'edit_terms' => 'edit_trashpic-report',
            'delete_terms' => 'edit_trashpic-report',
            'assign_terms' => 'edit_trashpic-report',  // means administrator', 'editor', 'author', 'contributor'
            )
     	);
     
     	register_taxonomy( 'litter_type', array( self::POST_TYPE ), $args );
     	

     	$labels = array(
     			'name' => __( 'attribute_tax_name', 'TRASHPIC-plugin' ),
     			'singular_name' => __( 'attribute_tax_name_singular', 'TRASHPIC-plugin'),
     			'search_items' => __( 'search_attribute_type', 'TRASHPIC-plugin' ),
     			'all_items' => __( 'all_attribute_type', 'TRASHPIC-plugin' ),
     			'parent_item' => __( 'parent_attribute_type', 'TRASHPIC-plugin' ),
     			'parent_item_colon' => __( 'parent_attribute_colon', 'TRASHPIC-plugin' ),
     			'edit_item' => __( 'edit_attribute', 'TRASHPIC-plugin' ),
     			'update_item' => __( 'update_attribute', 'TRASHPIC-plugin' ),
     			'add_new_item' => __( 'add_new_attribute', 'TRASHPIC-plugin'  ),
     			'new_item_name' => __( 'new_attribute_name', 'TRASHPIC-plugin' ),
     			'menu_name' => __( 'attribute_menu_name', 'TRASHPIC-plugin' ),
     	);
     	 
     	$args = array(
     			'hierarchical' => false,
     			'labels' => $labels,
     			'show_ui' => true,
     			'show_admin_column' => true,
     			'query_var' => true,
     			'rewrite' => array( 'slug' => 'attribute' ),
     			'capabilities' => array (
     					'manage_terms' => 'edit_trashpic-report', //by default only admin
     					'edit_terms' => 'edit_trashpic-report',
     					'delete_terms' => 'edit_trashpic-report',
     					'assign_terms' => 'edit_trashpic-report',  // means administrator', 'editor', 'author', 'contributor'
     			)
     	);
     	 
     	register_taxonomy( 'attribute', array( self::POST_TYPE ), $args );
     	
     } // END public function create_taxonomies()     
     
     
     
     
     /**
      * Aggiungo/modifico le colonne che mi servono nell'area amministrativa
      *  del post-type trashpic
      * @param unknown $columns
      */
     public function trashpic_report_columns($columns) {
     
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
     			'label' => __('label', 'TRASHPIC-plugin'),
     			'location' => __('location', 'TRASHPIC-plugin'),
     			'approved' => __('approved', 'TRASHPIC-plugin'),
     			'notified' => __('notified', 'TRASHPIC-plugin'),
     			'solved' => __('solved', 'TRASHPIC-plugin'),
     	);
     	return array_merge( $new_columns,$columns);
     }
     
     
      
     /**
      * Cambio le etichette delle colonne approved e investigated
      * della tabella amministrativa del post-type trashpic
      * @param unknown $columnhttp://life-smile.eu/wp/trashpic-report/2014030397993/
      * @param unknown $post_id
      */
     public function manage_trashpic_report_columns( $column, $post_id ){
     	global $post;
     	global $trashpic_category;
     	switch( $column ) {
     		
     		case 'label' :
     		case 'location' :
     			echo get_post_meta( $post_id, $column, true );
     			break;
     			
     		case 'category' :
     			$col = get_post_meta( $post_id, $column, true );
     			echo $trashpic_category[$col];
     			break;
     		case 'notified' :
     		case 'approved' :
     		case 'solved' :
     			/* Get the post meta. */
     			$col = get_post_meta( $post_id, $column, true );
     			if ( $col == '1' )
     				echo _e('yes','TRASHPIC-plugin');
     			else
     				echo _e('no','TRASHPIC-plugin');
     			break;
     	}
     }
     
     /**
      * Save the metaboxes for this custom post type
      */
     public function save_post($post_id)  {
     	// verify if this is an auto save routine.
      // If it is our form has not been submitted, so we dont want to do anything
      if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)  {
        return;
      }
    	if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))  {

    		if($_POST['solved']=="1"){
    			$_POST['solved_date'] = date("Y-m-d H:i:s");
    		}
    		
	      foreach($this->_meta as $field_name)  {
	      	
	      	if($field_name=="picture" ){
	      		if(!empty( $_FILES[$field_name]['name'] )){	
	      			$upload = wp_upload_bits($_FILES[$field_name]['name'], null, file_get_contents($_FILES[$field_name]['tmp_name']));
		      		if(isset($upload['error']) && $upload['error'] != 0) {
		      			wp_die('upload_error'. $upload['error']);
	  	    		} else {
	    	  			update_post_meta($post_id, $field_name, $upload);
	      			} 
	      		}
	      	} else  {
	      		update_post_meta($post_id, $field_name, $_POST[$field_name]);
	      	}
     		}
     	} else {
     		
     		return;
     	} // if($_POST['post_type'] == self::POST_TYPE && current_user_can('edit_post', $post_id))
     } // END public function save_post($post_id)

     /**
			* hook into WP's admin_init action hook
			*/

     public function admin_init() {	
     	// Add metaboxes
     	add_action('add_meta_boxes', array(&$this, 'add_meta_boxes'));
     } // END public function admin_init()

     /**
			* hook into WP's add_meta_boxes action hook
			*/
     public function add_meta_boxes()  {
     	// Add this metabox to every selected post
     	add_meta_box(
     							 sprintf('trashpic_%s_section', self::POST_TYPE),
     						   sprintf(__('%s Information', 'TRASHPIC-plugin'), ucwords(str_replace("_", " ", self::POST_TYPE))),
     							 array(&$this, 'add_inner_meta_boxes'),
     							self::POST_TYPE
     							);	
     } // END public function add_meta_boxes()

			/**
			 * called off of the add meta box
       */	
		 public function add_inner_meta_boxes($post){ 
		 	// Render the job order metabox
			include(sprintf("%s/../templates/%s_metabox.php", dirname(__FILE__), self::POST_TYPE));	
		 } // END public function add_inner_meta_boxes($post)

	} // END class Trashpic_Report
} // END if(!class_exists('Trashpic_Report'))
?>