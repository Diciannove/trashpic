<?php
if(!class_exists('Trashpic_Settings')) {
	
	
	
	
	class Trashpic_Settings {
		
		protected  $sino;
		
		/**
		 * Costruttore 
		 */
		public function __construct()	{
			// register actions
      add_action('admin_init', array(&$this, 'admin_init'));
      add_action('admin_menu', array(&$this, 'add_menu'));

		
      $this->sino[1] = __('yes','TRASHPIC-plugin');
      $this->sino[0] = __('no','TRASHPIC-plugin');
      //$this->sino[1] = "Si";
      //$this->sino[0] = "No";
      
		
		} // END public function __construct

		/**
		 * hook into WP's admin_init action hook 
		 */
    public function admin_init() {
    	
    	
    	   global $trashpic_default_options;
         // register your plugin's settings
         register_setting('trashpic-group', 'trashpic_default_latitude');
    	   register_setting('trashpic-group', 'trashpic_default_longitude');
         register_setting('trashpic-group', 'trashpic_default_zoom_level');
         register_setting('trashpic-group', 'trashpic_only_registered_users');
          
         // add your settings section
         add_settings_section(
                              'trashpic-section',
                              'Trashpic Settings',
                              array(&$this, 'settings_section_trashpic'),
                              'trashpic');
        
         // add your setting's fields
         add_settings_field(
                            'trashpic_default_latitude',
                            __('default_latitude','TRASHPIC-plugin'),
                            array(&$this, 'settings_field_input_text'),
                            'trashpic',
                            'trashpic-section',
                            array('field' => 'trashpic_default_latitude',
                                  'description'=>'default: '.$trashpic_default_options['trashpic_default_latitude']));
         
         add_settings_field(
                            'trashpic_default_longitude',
                            __('default_longitude','TRASHPIC-plugin'),
                            array(&$this, 'settings_field_input_text'),
                            'trashpic',
                            'trashpic-section',
                            array('field' => 'trashpic_default_longitude',
                            'description'=>'default: '.$trashpic_default_options['trashpic_default_longitude']));

         add_settings_field(
         										'trashpic_default_zoom_level',
         										__('default_zoom_level','TRASHPIC-plugin'),
         										array(&$this, 'settings_field_input_text'),
         										'trashpic',
         										'trashpic-section',
         										array('field' => 'trashpic_default_zoom_level',
         										'description'=>'default: '.$trashpic_default_options['trashpic_default_zoom_level']));
          
         add_settings_field(
         										'trashpic_only_registered_users',
         										__('only_registered_users','TRASHPIC-plugin'),
									          
         										array(&$this, 'settings_field_radio'),
         									  'trashpic',
                            'trashpic-section',
                            array('field' => 'trashpic_only_registered_users',
                            'description' => '',
                            'predef' => $trashpic_default_options['trashpic_only_registered_users'],
                            'options' => $this->sino , 
                            'description'=>'default: '.$trashpic_default_options['trashpic_only_registered_users']));
                             
         
            // Possibly do additional admin_init tasks
        } // END public static function activate
        

        public function settings_section_wp_plugin_template() {
            // Think of this as help text for the section.
            echo 'These settings do things for the WP Plugin Template.';
        }
        
        /**
         * This function provides text inputs for settings fields
         */
        public function settings_field_input_text($args)  {
            // Get the field name from the $args array
            $field = $args['field'];
            // Get the value of this setting
            $value = get_option($field);
            // echo a proper input type="text"
            echo sprintf('<input type="text" name="%s" id="%s" value="%s" />', $field, $field, $value);
            if ( ! empty( $args['description'] ) )
            	echo ' <p class="description">' . $args['description'] . '</p>';
            
        } // END public function settings_field_input_text($args)

				public function settings_field_radio( $args ) {
					
							if ( empty( $args['field'] ) || ! is_array( $args['options'] ) )
							return false;
							$field = get_option( $args['field']);
							
							$selected = (  get_option( $args['field'],$args['predef'] ) !== NULL ) ? get_option( $args['field'],$args['predef']  ) : '';
							foreach ( (array) $args['options'] as $value => $label )
								echo '<p><label><input type="radio" name="'.$args['field'].'" value="' . esc_attr( $value ) . '"' . checked( $value, $selected, false ) . '> ' . $label . '</input></label></p>';
									if ( ! empty( $args['description'] ) )
											echo ' <p class="description">' . $args['description'] . '</p>';
}        
        
        
        /**
			   * add a menu
         */	
        public function add_menu()  {
            // Add a page to manage this plugin's settings
         		add_options_page(
         		'Trashpic Settings',
         		'Trashpic',
         		'manage_options',
         		'trashpic',
         		array(&$this, 'plugin_settings_page')
         	);
        } // END public function add_menu()
    
        /**
				 * Menu Callback
         */	
        public function plugin_settings_page() {
        	
        	  if(!current_user_can('manage_options')) {
        	  		wp_die(__('insufficient_permissions','TRASHPIC-plugin'));
        	  }

           // Render the settings template
           include(sprintf("%s/templates/settings.php", dirname(__FILE__)));
        } // END public function plugin_settings_page()
        
    } // END class WP_Plugin_Template_Settings
    
} // END if(!class_exists('WP_Plugin_Template_Settings'))
?>