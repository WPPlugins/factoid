<?php

/* 
Plugin Name: Factoid
Plugin URI: http://www.october.com.au/soft/factoid.htm
Description: Place random quotes or facts on your site through shortcodes or widgets
Version: 1.11
Author: Craig Delahoy 
Author URI: http://www.october.com.au
License: GPLv2 or later. 
Copyright: 2014 October Productions
*/ 

/* Copyright 2014 Craig Delahoy 
(email : craig@october.com.au) 
*/

date_default_timezone_set('Australia/Sydney');

if(!class_exists('WP_octo_factoid'))
{
    class WP_octo_factoid
    {
    	public $version = 111; # also update in text above
    	public $product = "FD01";
		
        /**
         * Construct the plugin object
         */
        public function __construct()
        {
            // register actions for WordPress - first, basic operational stuff
            add_action('admin_init', array(&$this, 'admin_init')); 
            add_action('admin_menu', array(&$this, 'add_menu')); 
			add_action('wp_enqueue_scripts', array(&$this,'register_plugin_styles') );
			add_shortcode( 'factoid', array(&$this, 'fa_shortcode') );
		}

		/**
         * Activate the plugin
         */
        public static function activate()
        {
			//lblog("activate() Factoid activated.");
		}
		
        /**
         * Deactivate the plugin
         */     
        public static function deactivate()
        {
            // Do nothing
			//lblog("deactivate() Factoid Deactivated.");
        } // END public static function deactivate
        
		/**
		 * Initiate admin 
		 */
		public function admin_init()
		{
#		    $this->init_settings();
		    wp_enqueue_script('jquery');
		} // END public static function admin_init    
		
		/**
		 * add a menu item to the settings menu
		 */     
		public function add_menu()
		{
		   $result = add_options_page('Factoid settings', 'Factoid', 'manage_options', 'wp_factoid_plugin', array(&$this, 'plugin_settings_page'));
		} // END public function add_menu()

		/**
		 * Menu Callback
		 * Generates settings page
		 */     
		public function plugin_settings_page()
		{
			//echo "Rendering the settings page ... ";
		    if(!current_user_can('manage_options'))
		    {
		        wp_die(__('You do not have sufficient permissions to administer this system.'));
		    }
		   // Render the settings template
		    include(sprintf("%s/factoidsettings.php", dirname(__FILE__)));
		} // END public function plugin_settings_page()	} 

		public function register_plugin_styles()
		{
			global $wp_styles;
			wp_register_style( 'fa_style', plugins_url('factoid.css', __FILE__) );
			wp_enqueue_style( 'fa_style' );
			wp_register_script( 'fa_script', plugins_url('factoid.js', __FILE__), array( 'jquery' ) );
			wp_enqueue_script( 'fa_script' );
			wp_enqueue_script('jquery-ui-dialog');
			wp_enqueue_style( 'wp-jquery-ui-dialog' );
			// declare the URL to the file that handles the AJAX request (wp-admin/admin-ajax.php)
#			wp_localize_script( 'fa_script', 'MyAjax', array( 'ajaxurl' =>  admin_url( 'admin-ajax.php' ) ) );		    
		}

		/*****************
		 * 
		 * SHORTCODE
		 * 
		 *****************/
		// This method handles the shortcode
		// [factoid foo="foo-value" ...]
		public function fa_shortcode( $atts,$content ) 
		{
//			$this->lb_loadoptions();
			$textout = "";
			extract( shortcode_atts( array(
				'output' => 'normal', // this is for future use - not yet implemented
				'type' => 1,
				'category' => 0,
				'sfw' => 1,
				'width' => '95%',
				'date' => '01/01/2000',
				'style' => 'simple',
				'title' => 'Factoid!'
			), $atts ) );
			$style = 'factoid_'.$style;
			if($output == 'normal')
			{
				$textout .= '<div class="factoid_container '.$style.'" style="width: '.$width.'" >';
					if ( ! empty( $title ) )
					{
						$textout .= '<div class="factoid_title_container '.$style.'">';
						$textout .= __( $title.'<br />', 'text_domain' );
						$textout .= '</div>'; // end factoid_title_container div
					}
					$divid = "factoid_".(rand(100, 999))."_";
					$textout .= '<div id="'.$divid.'" class="factoid_content_container '.$style.'">';
						$textout .= '<div id="'.$divid.'header" class="factoid_header '.$style.'" >';
						$textout .= '</div>'; // end of headline div
						$textout .= '<div id="'.$divid.'content" class="factoid_content '.$style.'">';
//							$textout .= __( 'Type = '.$mytype.'<br />', 'text_domain' );
//							$textout .= __( 'Category = '.$mycategory.'<br />', 'text_domain' );
						$textout .= '</div>'; // end of headline div
						$textout .= '<div id="'.$divid.'source" class="factoid_source '.$style.'">';
						$textout .= '</div>'; // end of headline div
			
						?>
						<script>
							jQuery( function() {
								fa_getFactoid( <?php echo "'".$divid."',".$type.",".$category.",".$sfw; ?> );
							});
						</script>
						<?php
					$textout .= '</div> '; // end factoid_content_container div
					$textout .= '<div class="factoid_footer_container '.$style.'">';
						$textout .= '<div class="factoid_footer_innerleft '.$style.'">';
							$textout .= __( '<a href="javascript:fa_getFactoid('."'".$divid."',".$type.",".$category.",".$sfw.');">');
							$textout .= __( '<img src="http://october.com.au/soft/reload.png" ');
							$textout .= __( 'class = "factoid_image_opacity '.$style.'" width="12" height="12"> </a>');
						$textout .= '</div> '; // end factoid_footer_innerleft div				
						$textout .= '<div class="factoid_footer_innerright '.$style.'">';
							$textout .= __( 'Copyright october.com.au');
						$textout .= '</div> '; // end factoid_footer_innerright div				
					$textout .= '</div> '; // end factoid_footer_container div
				$textout .= '</div> '; // end factoid_container div
			}
			return $textout;
		}
	}
}


//  Add the widget!

class factoid_widget extends WP_Widget {

	function __construct() {
		parent::__construct(
			// Base ID of the widget
			'factoid_widget',
			// Widget name as it will appear in UI
			__('Factoid', 'text_domain'),
			// Widget description
			array( 'description' => __( 'Place random quotes or facts on your site', 'text_domain' ), )
		);
	}

	// Create the widget front-end
	public function widget( $args, $instance ) {
		$title = apply_filters( 'widget_title', $instance['title'] );
		$stylearray = array("none","simple","simpledark","simpleblue","simplewarm","simplevintage","simpleelegant","usercustom");
		if ( isset( $instance[ 'style' ] ) ) {
			$style = ($instance['style'] > 0) ? "factoid_".$stylearray[$instance['style']] : "";
		}
		else {
			$style = "";
		}
		if ( isset( $instance[ 'sfw' ] ) ) {
			$sfw = $instance[ 'sfw' ];
		}
		else {
			$sfw = __( 1, 'text_domain' );
		}


		// before and after widget arguments are defined by themes
		echo $args['before_widget'];
		if ( ! empty( $title ) )
		{
			echo $args['before_title'] . $title . $args['after_title'];
		}
		$ftitle = $instance['ftitle'];
		$mytype = $instance[ 'type' ];
		$mycategory = $instance[ 'category' ];
		// go and get the factoid!
		
		// This is where you run the code and display the output
		echo '<div class="factoid_container '.$style.'">';
			if ( ! empty( $ftitle ) )
			{
				echo '<div class="factoid_title_container '.$style.'">';
				echo __( $ftitle.'<br />', 'text_domain' );
				echo '</div>'; // end factoid_title_container div
			}
			$divid = "factoid_".(rand(100, 999))."_";
			echo '<div id="'.$divid.'" class="factoid_content_container '.$style.'">';
				echo '<div id="'.$divid.'header" class="factoid_header '.$style.'" >';
				echo '</div>'; // end of headline div
				echo '<div id="'.$divid.'content" class="factoid_content '.$style.'">';
//					echo __( 'Type = '.$mytype.'<br />', 'text_domain' );
//					echo __( 'Category = '.$mycategory.'<br />', 'text_domain' );
				echo '</div>'; // end of headline div
				echo '<div id="'.$divid.'source" class="factoid_source '.$style.'">';
				echo '</div>'; // end of headline div
			
				?>
					<script>
						jQuery( function() {
							fa_getFactoid( <?php echo "'".$divid."',".$mytype.",".$mycategory.",".$sfw; ?> );
						});
					</script>
				<?php
			echo '</div> '; // end factoid_content_container div
			echo '<div class="factoid_footer_container '.$style.'">';
				echo '<div class="factoid_footer_innerleft '.$style.'">';
					echo __( '<a href="javascript:fa_getFactoid('."'".$divid."',".$mytype.",".$mycategory.",".$sfw.');">');
					echo __( '<img src="http://october.com.au/soft/reload.png" ');
					echo __( 'class = "factoid_image_opacity '.$style.'" width="12" height="12"> </a>');
				echo '</div> '; // end factoid_footer_innerleft div				
				echo '<div class="factoid_footer_innerright '.$style.'">';
					echo __( 'Copyright october.com.au');
				echo '</div> '; // end factoid_footer_innerright div				
			echo '</div> '; // end factoid_footer_container div
		echo '</div> '; // end factoid_container div
		echo $args['after_widget'];
	}

	// Widget Backend
	public function form( $instance ) {
		$mywidgetid = $this->name."_".$this->number."_";
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Factoid!', 'text_domain' );
		}
		if ( isset( $instance[ 'ftitle' ] ) ) {
			$ftitle = $instance[ 'ftitle' ];
		}
		else {
			$ftitle = __( 'Factoid!', 'text_domain' );
		}
		if ( isset( $instance[ 'type' ] ) ) {
			$type = $instance[ 'type' ];
		}
		else {
			$type = __( 1, 'text_domain' );
		}
		if ( isset( $instance[ 'category' ] ) ) {
			$category = $instance[ 'category' ];
		}
		else {
			$category = __( 0, 'text_domain' );
		}
		if ( isset( $instance[ 'style' ] ) ) {
			$style = $instance[ 'style' ];
		}
		else {
			$style = __( 0, 'text_domain' );
		}
		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Theme Title:' ); ?></label><br />
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" 
				name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
			<br />
			<label for="<?php echo $this->get_field_id( 'ftitle' ); ?>"><?php _e( 'Factoid Title:' ); ?></label><br />
			<input class="widefat" id="<?php echo $this->get_field_id( 'ftitle' ); ?>" 
				name="<?php echo $this->get_field_name( 'ftitle' ); ?>" type="text" value="<?php echo esc_attr( $ftitle ); ?>" />
			<br />
			<label for="<?php echo $this->get_field_id( 'type' ); ?>"><?php _e( 'Factoid type:' ); ?></label><br />
			<select name="<?php echo $this->get_field_name( 'type' ); ?>" id="<?php echo $this->get_field_id( 'type' ); ?>" 
				size="1" onChange="setupfa(this);">
				<option value="1">1. Factoids - short trivia</option>
				<option value="2">2. Quotes</option>
				<!-- option value="3">3. On this day</option -->
			</select>
			<br />
			<label for="<?php echo $this->get_field_id( 'category' ); ?>"><?php _e( 'Type category:' ); ?></label><br />
			<select name="<?php echo $this->get_field_name( 'category' ); ?>" id="<?php echo $this->get_field_id( 'category' ); ?>" 
				size="1" >
					<option value="0">0. All</option>
			</select>
			<br />
			<label for="<?php echo $this->get_field_id( 'style' ); ?>"><?php _e( 'Display style:' ); ?></label><br />
			<select name="<?php echo $this->get_field_name( 'style' ); ?>" id="<?php echo $this->get_field_id( 'style' ); ?>" 
				size="1" >
				<option value="0">No style</option>
				<option value="1">Simple light</option>
				<option value="2">Simple dark</option>
				<option value="3">Simple blue</option>
				<option value="4">Simple warm</option>
				<option value="5">Simple vintage</option>
				<option value="6">Simple elegant</option>
				<option value="7">User custom style</option>
			</select>
			<br />
			<input type="checkbox" name="<?php echo $this->get_field_name( 'sfw' ); ?>" id="<?php echo $this->get_field_id( 'sfw' ); ?>">
			<label for="<?php echo $this->get_field_id( 'sfw' ); ?>"><?php _e( ' Only show Safe for Work content' ); ?></label>
		</p>
		<script>
			var <?php echo $mywidgetid; ?>type = <?php echo $type; ?>;
			var typebox = document.getElementById("<?php echo $this->get_field_id( 'type' ); ?>");
			typebox.selectedIndex = <?php echo $mywidgetid; ?>type - 1;
			setupfa(typebox);
			var <?php echo $mywidgetid; ?>category = <?php echo $category; ?>;
			var catbox = document.getElementById("<?php echo $this->get_field_id( 'category' ); ?>");
			catbox.selectedIndex = <?php echo $mywidgetid; ?>category;
			var <?php echo $mywidgetid; ?>style = <?php echo $style; ?>;
			var stylebox = document.getElementById("<?php echo $this->get_field_id( 'style' ); ?>");
//			alert("STYLE: <?php echo $mywidgetid; ?>style = <?php echo $style; ?>");
			stylebox.selectedIndex = <?php echo $mywidgetid; ?>style;
			
			function setupfa(typesel)
			{
				var <?php echo $mywidgetid; ?>category = <?php echo $type; ?>;
				var categorybox = document.getElementById("<?php echo $this->get_field_id( 'category' ); ?>");
				var selected_type = typesel.selectedIndex;
				//alert('Change <?php echo $this->get_field_id( 'category' ); ?> triggered by ' + typesel + ' set to ' + selected_type);
				var catarray = new Array();
				catarray[0] = ['0. All Factoids','1. General','2. Showbiz','3. Travel','4. History'];
				catarray[1] = ['0. All quotes','1. Inspirational','2. Showbiz','3. Humourous','4. General'];
				catarray[2] = ['0. All on this day','1. General','2. Showbiz'];
				categorybox.options.length=0;
				for(f=0;f<catarray[selected_type].length; f++)
				{
					categorybox.options[f]=new Option(catarray[selected_type][f], f, false, false);
				}
//				alert("Setting category to <?php echo $category; ?>");
				categorybox.selectedIndex = <?php echo $category; ?>;
			}
		</script>
		<?php
	}

	// Updating widget replacing old instances with new
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['ftitle'] = ( ! empty( $new_instance['ftitle'] ) ) ? strip_tags( $new_instance['ftitle'] ) : '';
		$instance['type'] = ( ! empty( $new_instance['type'] ) ) ? strip_tags( $new_instance['type'] ) : 1;
		$instance['category'] = ( ! empty( $new_instance['category'] ) ) ? strip_tags( $new_instance['category'] ) : 0;
		$instance['style'] = ( ! empty( $new_instance['style'] ) ) ? strip_tags( $new_instance['style'] ) : 0;
		return $instance;
	}

}; // Class factoid_widget ends here



if(class_exists('WP_octo_factoid')) 
{ 
	// Installation and uninstallation hooks 
	register_activation_hook(__FILE__, array('WP_octo_factoid', 'activate')); 
	register_deactivation_hook(__FILE__, array('WP_octo_factoid', 'deactivate')); 
	// instantiate the plugin class 
	$factoid = new WP_octo_factoid(); 
} 
// Add a link to the settings page onto the plugin page
if(isset($factoid))
{
    // Add the settings link to the plugins page
    function factoid_plugin_settings_link($links)
    { 
        $settings_link = '<a href="options-general.php?page=wp_factoid_plugin">Settings</a>'; 
        array_unshift($links, $settings_link); 
        return $links; 
    }

    $plugin = plugin_basename(__FILE__); 
    add_filter("plugin_action_links_$plugin", 'factoid_plugin_settings_link');
}

// Register and load the widget
function factoid_load_widget() {
    register_widget( 'factoid_widget' );
}
add_action( 'widgets_init', 'factoid_load_widget' );




?>
