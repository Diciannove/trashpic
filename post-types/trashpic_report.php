<?php

if(!class_exists('Trashpic_Report'))
{

	
	/**
	 * 
	 * @author paolo
	 *
	 */
	class Trashpic_Report {
		
		const POST_TYPE	= "trashpic-report";
		
		private $_meta	= array(
		  'latitude',
		  'longitude',
			'investigated',
			'smile_phone',
			'approved',
			'note',
		);

    /**
     * 
     */ 
    public function __construct() {
    	
     	// register actions

    	add_action('init', array(&$this, 'init'));
    	add_action('admin_init', array(&$this, 'admin_init'));
    	
    	 
    } // END public function __construct()

    
    
    /**
		 * hook into WP's init action hook
		 */
    public function init() {
     	// Initialize Post Type
     	$this->create_post_type();
     	$this->create_taxonomies();
     	add_action('save_post', array(&$this, 'save_post'));
     	
     	
     } // END public function init()

     
     /**
      * Create the post type
      */
    public function create_post_type()  {
    	
     register_post_type(self::POST_TYPE,
     			              array(
                              'labels' => array(
                                                'name' => __(sprintf('%ss', ucwords(str_replace("_", " ", self::POST_TYPE)))),
                                                'singular_name' => __(ucwords(str_replace("_", " ", self::POST_TYPE)))
                                                ),
                              'public' => true,
                              'has_archive' => true,
                              'description' => __("This is a sample post type meant only to illustrate a preferred structure of plugin development"),
                              'supports' => array('title'),
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
     	);
     
     	register_taxonomy( 'litter_type', array( self::POST_TYPE ), $args );
     } // END public function create_taxonomies()     
     
     
      
     
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
    		
	      foreach($this->_meta as $field_name)  {
     			// Update the post's meta field
     			update_post_meta($post_id, $field_name, $_POST[$field_name]);
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
     						   sprintf('%s Information', ucwords(str_replace("_", " ", self::POST_TYPE))),
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