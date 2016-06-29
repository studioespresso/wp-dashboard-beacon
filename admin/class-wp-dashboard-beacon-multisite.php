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
	
    function hsb_multisite_register_settings() {
    	        // Account settings, displayed on tab 1
        add_settings_section(
            'hsb_account_settings',                                     // ID used to identify this section and with which to register options
            __('Help Scout account settings', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_account_settings_description'),          // Callback used to render the description of the section
            'hsb_account_settings'                                         // Page on which to add this section of options
        );

        // Form ID field
        add_settings_field(
            'hsb_helpscout_form_id',                                      // ID used to identify the field throughout the theme
            'Beacon form ID',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_account_settings',                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.
                __('Enter the form ID for your beacon', 'wp-dashboard-beacon'),
                'hsb_helpscout_form_id'
            )
        );

        // Subdomain field
        add_settings_field(
            'hsb_helpscout_subdomain',                                      // ID used to identify the field throughout the theme
            __('Help Scout subdomain', 'wp-dashboard-beacon'),                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_account_settings',                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.
                __('Enter the subdomain of your Helpscout docs account', 'wp-dashboard-beacon'),
                'hsb_helpscout_subdomain'
            )
        );

        // Beacon options
        add_settings_field(
            'hsb_beacon_options',                                      // ID used to identify the field throughout the theme
            __('Beacon options', 'wp-dashboard-beacon'),                                                   // The label to the left of the option interface element
            array( $this, 'hsb_select_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_account_settings',                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                __('Set your beacon functions', 'wp-dashboard-beacon'),
                'hsb_beacon_options',
                'options' => array(
                    'contact' => __('Contact form','wp-dashboard-beacon'),
                    'docs' => __('Docs search', 'wp-dashboard-beacon'),
                    'contact_docs' => __('Contact form and docs search', 'wp-dashboard-beacon')
                ),
            )
        );

        register_setting( 'hsb_account_settings', 'hsb_helpscout_subdomain' );
        register_setting( 'hsb_account_settings', 'hsb_helpscout_form_id' );
        register_setting( 'hsb_account_settings', 'hsb_beacon_options' );
    }
    
    function hsb_account_settings_description($args) {
        echo '<p>' . __('Connect your dashboard account','wp-dashboard-beacon') . '</p>';
    }

    function hsb_beacon_settings_description($args) {}
    function hsb_permissions_settings_description($args) {}

    function hsb_textfield_callback($args) {
        $html = '<input type="text" id="' . $args[1] . '" name="' . $args[1] . '" value="' . get_option($args[1]) .'">';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
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
            
                    ?>
        <div class="wrap">
            <h2><?php echo __('Help Scout beacon settings', 'wp-dashboard-beacon'); ?></h2>
            <?php settings_errors(); ?>
            <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'hsb_account_settings'; // end if?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=dashboard_beacon&tab=hsb_account_settings" class="nav-tab <?php echo $active_tab == 'hsb_account_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Setup','wp-dashboard-beacon'); ?></a>
                </h2>
            <form method="post" action="settings.php">
                <?php
                if( $active_tab == 'hsb_account_settings' ) {
                    settings_fields( 'hsb_account_settings' );
                    do_settings_sections( 'hsb_account_settings' );
                }
                submit_button();
                ?>
            </form>
        </div>
        <?php
            
           

	}
	
	public function hsb_multisite_add_settings_page() {
	         add_submenu_page( 'settings.php', 'Helpscout Beacon', 'Helpscout Beacon', 'manage_network', 'hsb-settings', array( $this, 'hsb_add_settings_page_callback') );
	}
}