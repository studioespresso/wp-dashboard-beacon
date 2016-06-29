<?php

/**
 * The multisite-specific functionality of the plugin.
 *
 * @link       http://onedge.be
 * @since      1.3.0
 *
 * @package    Wp_dashboard_Beacon
 * @subpackage Wp_dashboard_Beacon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_dashboard_Beacon
 * @subpackage Wp_dashboard_Beacon/admin
 * @author     Jan Henckens <jan@onedge.be>
 */
class Wp_Dashboard_Beacon_Multisite {
    
    
	/**
    * The ID of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $plugin_name    The ID of this plugin.
    */
	private $plugin_name;

	/**
    * The version of this plugin.
    *
    * @since    1.0.0
    * @access   private
    * @var      string    $version    The current version of this plugin.
    */
	private $version;

	/**
    * Initialize the class and set its properties.
    *
    * @since    1.0.0
    * @param      string    $plugin_name       The name of this plugin.
    * @param      string    $version    The version of this plugin.
    */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}
	
    function hsb_register_multisite_settings() {
    	
    }

	
	public function hsb_add_settings_page_callback() {
	    global $wpdb;
	        $blogs = $wpdb->get_results( $wpdb->prepare("SELECT blog_id, domain, path FROM $wpdb->blogs WHERE site_id = %d AND public = '1' AND archived = '0' AND mature = '0' AND spam = '0' AND deleted = '0' ORDER BY domain ASC, path ASC
", $wpdb->siteid), ARRAY_A );

            // put it in array  
            foreach ( (array) $blogs as $details ) {$blog_list[ $details['blog_id'] ] = $details;}
            unset( $blogs );
            $blogs = $blog_list;

            // if is valid array
            if (is_array( $blogs ) ){
                    echo '<ul>';
                    $array= array();
                    // reorder
                    $array= array_slice( $blogs, 0, count( $blogs ) );
                    for($i=0;$i<count($array);$i++){
                    // get data for each id
                    $blog = get_blog_details( $array[$i]['blog_id'] );
                    // print it
                    echo '<li><a href="'.$blog->siteurl.'">'.$blog->blogname.'</a></li>';
                    }
                    echo '</ul>';
            }
            
            
           

	}
	
	public function hsb_multisite_add_settings_page() {
	         add_menu_page ('Helpscout Beaonc', 'Beacon settings', 'manage_network', 'hsb-settings', array( $this, 'hsb_add_settings_page_callback'));
	}
}