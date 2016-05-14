<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://onedge.be
 * @since      1.0.0
 *
 * @package    Wp_dashboard_Beacon
 * @subpackage Wp_dashboard_Beacon/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_dashboard_Beacon
 * @subpackage Wp_dashboard_Beacon/public
 * @author     Jan Henckens <jan@onedge.be>
 */
class Wp_dashboard_Beacon_Public {

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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_dashboard_Beacon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_dashboard_Beacon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		// wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-dashboard-beacon-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_dashboard_Beacon_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_dashboard_Beacon_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
        $showOnFrontEnd = get_option('hsb_show_on_frontend');
    	$formId = get_option('hsb_helpscout_form_id');
        if($showOnFrontEnd == '1' && $formId) {
	        $user = new WP_User( get_current_user_id() );
	        if(!empty($user->first_name) && !empty($user->last_name)) {
            	$userName = $user->first_name . ' ' . $user->last_name;
        	} else {
            	$userName = $user->nickname;
        	}
        	$userEmail = $user->user_email;
            if($formId) {
                wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-dashboard-beacon-beacon.js', array( 'jquery' ), $this->version, false );
                wp_localize_script( $this->plugin_name, 'hsb_settings', array(
                    'formId' => get_option('hsb_helpscout_form_id'),
                    'subDomain' => get_option('hsb_helpscout_subdomain'),
                    'beaconOptions' => get_option('hsb_beacon_options'),
                    'icon' => get_option('hsb_beacon_icon'),
                    'colour' => get_option('hsb_beacon_colour'),
                    'credits' => get_option('hsb_hide_credits'),
                    'formInstructions' => get_option('hsb_beacon_intro'),
                    'allowAttachments' => get_option('hsb_allow_attachments'),
                    'strings' => array(
                        'searchLabel' => __('What can we help you with?', 'wp-dashboard-beacon'),
                        'searchErrorLabel' => __('Your search timed out. Please double-check your internet connection and try again.', 'wp-dashboard-beacon'),
                        'noResultsLabel' => __('No results found for', 'wp-dashboard-beacon'),
                        'contactLabel' => __('Send a Message', 'wp-dashboard-beacon'),
                        'attachFileLabel' => __('Attach a file', 'wp-dashboard-beacon'),
                        'attachFileError' => __('The maximum file size is 10mb', 'wp-dashboard-beacon'),
                        'nameLabel' => __('Your Name', 'wp-dashboard-beacon'),
                        'nameError' => __('Please enter your name', 'wp-dashboard-beacon'),
                        'emailLabel' => __('Email address', 'wp-dashboard-beacon'),
                        'emailError' => __('Please enter a valid email address', 'wp-dashboard-beacon'),
                        'topicLabel' => __('Select a topic', 'wp-dashboard-beacon'),
                        'topicError' => __('Please select a topic from the list', 'wp-dashboard-beacon'),
                        'subjectLabel' => __('Subject', 'wp-dashboard-beacon'),
                        'subjectError' => __('Please enter a subject', 'wp-dashboard-beacon'),
                        'messageLabel' => __('How can we help you?', 'wp-dashboard-beacon'),
                        'messageError' => __('Please enter a message', 'wp-dashboard-beacon'),
                        'sendLabel' => __('Send', 'wp-dashboard-beacon'),
                        'contactSuccessLabel' => __('Message sent!', 'wp-dashboard-beacon'),
                        'contactSuccessDescription' => __('Thanks for reaching out! Someone from our team will get back to you soon.', 'wp-dashboard-beacon')
                    ),
                    'userName' => $userName,
                    'userEmail' => $userEmail,
                ));
           }
        }
	}

}
