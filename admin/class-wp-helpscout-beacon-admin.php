<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://onedge.be
 * @since      1.0.0
 *
 * @package    Wp_Helpscout_Beacon
 * @subpackage Wp_Helpscout_Beacon/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Helpscout_Beacon
 * @subpackage Wp_Helpscout_Beacon/admin
 * @author     Jan Henckens <jan@onedge.be>
 */
class Wp_Helpscout_Beacon_Admin {

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

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Helpscout_Beacon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Helpscout_Beacon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-helpscout-beacon-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Helpscout_Beacon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Helpscout_Beacon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-helpscout-beacon-admin.js', array( 'jquery' ), $this->version, false );

	}

    /** Settings Initialization **/
    function hsb_register_settings() {

        $settings_fields = 'helpscout_beacon';

        add_settings_section(
            'hsb_account_settings',                                     // ID used to identify this section and with which to register options
            __('Helpscout account settings', 'wp-helpscout-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_account_settings_description'),          // Callback used to render the description of the section
            $settings_fields                                         // Page on which to add this section of options
        );

        add_settings_section(
            'hsb_beacon_settings',                                     // ID used to identify this section and with which to register options
            __('Beacon settings', 'wp-helpscout-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_beacon_settings_description'),          // Callback used to render the description of the section
            $settings_fields                                         // Page on which to add this section of options
        );

        // Subdomain field
        add_settings_field(
            'helpscout_subdomain',                                      // ID used to identify the field throughout the theme
            'Helpscout subdomain',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            $settings_fields,                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.
                'Enter the subdomain of your helpscout account',
                'helpscout_subdomain'
            )
        );

        // Form ID field
        add_settings_field(
            'helpscout_form_id',                                      // ID used to identify the field throughout the theme
            'Form ID',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            $settings_fields,                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.
                'Enter the form ID for your beacon',
                'helpscout_form_id'
            )
        );

        // Finally, we register the fields with WordPress
        register_setting( $settings_fields, 'show_header' );

    } // end sandbox_initialize_theme_options

    function hsb_account_settings_description($args) {
        echo '<p>Connect your HelpScout account</p>';
    } // end hsb_account_settings_description

    function hsb_beacon_settings_description($args) {
        echo '<p>Set up your beacon</p>';
    } // end hsb_beacon_settings_description

    function hsb_textfield_callback($args) {
        // Note the ID and the name attribute of the element match that of the ID in the call to add_settings_field
        // 
        $html = '<input type="text" id="' . $args[1] . '" name="show_header">';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';

        // Here, we will take the first argument of the array and add it to a label next to the checkbox

        echo $html;
    } 


    public function hsb_add_settings_page_callback() {
        ?>

        <div class="wrap">
            <h2><?php echo __('Helpscout Beanson settings', 'wp-helpscout-beacon'); ?></h2>
            <p>Some text describing what the plugin settings do.</p>

            <form method="post" action="options.php">
                <?php
                // Output the settings sections.
                do_settings_sections( 'helpscout_beacon' );
                // Output the hidden fields, nonce, etc.
                settings_fields( 'helpscout_beacon' );
                // Submit button.
                submit_button();
                ?>
            </form>
        </div>
        <?php
	}

	public function hsb_add_settings_page() {
		add_submenu_page( 'tools.php', 'Helpscout Beacon', 'Helpscout Beacon', 'manage_options', 'helpscout_beacon', array( $this, 'hsb_add_settings_page_callback') );
	}






}
