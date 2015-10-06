<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://onedge.be
 * @since      1.0.0
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
class Wp_Dashboard_Beacon_Admin {

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
        * defined in Wp_dashboard_Beacon_Loader as all of the hooks are defined
        * in that particular class.
        *
        * The Wp_dashboard_Beacon_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */


		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-dashboard-beacon-admin.css', array(), $this->version, 'all' );

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
        * defined in Wp_dashboard_Beacon_Loader as all of the hooks are defined
        * in that particular class.
        *
        * The Wp_dashboard_Beacon_Loader will then create the relationship
        * between the defined hooks and the functions defined in this
        * class.
        */
        $user = new WP_User( get_current_user_id() );
        $user->roles['0'];

        $formId = get_option('hsb_helpscout_form_id');
        if($formId) {
            wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-dashboard-beacon-beacon.js', array( 'jquery' ), $this->version, false );
            wp_localize_script( $this->plugin_name, 'hsb_settings', array(
                'formId' => get_option('hsb_helpscout_form_id'),
                'subDomain' => get_option('hsb_helpscout_subdomain'),
                'beaconOptions' => get_option('hsb_beacon_options'),
                'icon' => get_option('hsb_beacon_icon'),
                'colour' => get_option('hsb_beacon_colour'),
                'credits' => get_option('hsb_hide_credits'),
                'formInstructions' => get_option('hsb_form_instructions'),
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
                )
            ));
       }

	}

    function hsb_enqueue_colourpicker( $hook ) {
        if ( 'tools_page_dashboard_beacon' != $hook ) {
            return;
        }
        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/wp-dashboard-beacon-colourpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
}

    /** Settings Initialization **/
    function hsb_register_settings() {

        // Account settings, displayed on tab 1
        add_settings_section(
            'hsb_account_settings',                                     // ID used to identify this section and with which to register options
            __('Help Scout account settings', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_account_settings_description'),          // Callback used to render the description of the section
            'hsb_account_settings'                                         // Page on which to add this section of options
        );

        // Subdomain field
        add_settings_field(
            'hsb_helpscout_subdomain',                                      // ID used to identify the field throughout the theme
            'helpscout subdomain',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_account_settings',                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.
                'Enter the subdomain of your Helpscout docs account',
                'hsb_helpscout_subdomain'
            )
        );

        // Form ID field
        add_settings_field(
            'hsb_helpscout_form_id',                                      // ID used to identify the field throughout the theme
            'Beacon form ID',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_account_settings',                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.
                'Enter the form ID for your beacon',
                'hsb_helpscout_form_id'
            )
        );

        // Beacon options
        add_settings_field(
            'hsb_beacon_options',                                      // ID used to identify the field throughout the theme
            'Beacon options',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_select_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_account_settings',                                         // The page on which this option will be displayed
            'hsb_account_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                'Set your beacon functions',
                'hsb_beacon_options',
                'options' => array(
                    'contact' => 'Contact form',
                    'docs' => 'Docs search',
                    'contact_docs' => 'Contact form and docs search'
                ),
            )
        );

        register_setting( 'hsb_account_settings', 'hsb_helpscout_subdomain' );
        register_setting( 'hsb_account_settings', 'hsb_helpscout_form_id' );
        register_setting( 'hsb_account_settings', 'hsb_beacon_options' );

        // Display settings, displayed on tab 2
        add_settings_section(
            'hsb_beacon_display_settings',                                     // ID used to identify this section and with which to register options
            __('Customize your beacon', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_beacon_settings_description'),          // Callback used to render the description of the section
            'hsb_beacon_display_settings'                                         // Page on which to add this section of options
        );

        // Beacon icon
        add_settings_field(
            'hsb_beacon_icon',                                      // ID used to identify the field throughout the theme
            'Beacon Icon',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_select_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_beacon_display_settings',                                         // The page on which this option will be displayed
            'hsb_beacon_display_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                'Select an icon to be used in your beacon',
                'hsb_beacon_icon',
                'options' => array(
                    '' => 'Select an icon',
                    'question' => 'Question',
                    'beacon' => 'Beacon',
                    'buoy' => 'Buoy',
                    'message' => 'Message',
                    'search' => 'Search'
                )
            )
        );

        // Beacon colour
        add_settings_field(
            'hsb_beacon_colour',                                      // ID used to identify the field throughout the theme
            'Beacon colour',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_colourpicker_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_beacon_display_settings',                                         // The page on which this option will be displayed
            'hsb_beacon_display_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                'Pick a colour to be used for the beacon icon and text labels',
                'hsb_beacon_colour',
                'options' => array(
                    'default' => '#0C5E99'
                )
            )
        );

        // Allow attachments
        add_settings_field(
            'hsb_allow_attachments',                                      // ID used to identify the field throughout the theme
            'Allow attachments',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_checkbox_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_beacon_display_settings',                                         // The page on which this option will be displayed
            'hsb_beacon_display_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                '',
                'hsb_allow_attachments'
            )
        );

        // Hide powered by Help Scout
        add_settings_field(
            'hsb_hide_credits',                                      // ID used to identify the field throughout the theme
            'Hide \'Powered by Help Scout\'',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_checkbox_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_beacon_display_settings',                                         // The page on which this option will be displayed
            'hsb_beacon_display_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                '',
                'hsb_hide_credits'
            )
        );

        register_setting( 'hsb_beacon_display_settings', 'hsb_beacon_icon' );
        register_setting( 'hsb_beacon_display_settings', 'hsb_beacon_colour' );
        register_setting( 'hsb_beacon_display_settings', 'hsb_allow_attachments' );
        register_setting( 'hsb_beacon_display_settings', 'hsb_hide_credits' );

        // Permissions, displayed on tab 3
        add_settings_section(
            'hsb_permissions_settings',                                     // ID used to identify this section and with which to register options
            __('Beacon permissions and visibility', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_permissions_settings_description'),          // Callback used to render the description of the section
            'hsb_permissions_settings'                                         // Page on which to add this section of options
        );

        // Minimum user role
        add_settings_field(
            'hsb_allowed_user_roles',                                      // ID used to identify the field throughout the theme
            'User role',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_wp_user_roles_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_permissions_settings',                                         // The page on which this option will be displayed
            'hsb_permissions_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                'Enable beacon for user who\'s role is at least the selected role' ,
                'hsb_allowed_user_roles',
                'options' => $this->hsb_get_roles()
            )
        );
        register_setting( 'hsb_permissions_settings', 'hsb_allowed_user_roles' );


    }

    function hsb_account_settings_description($args) {
        echo '<p>Connect your dashboard account</p>';
    }

    function hsb_beacon_settings_description($args) {}
    function hsb_permissions_settings_description($args) {}

    function hsb_textfield_callback($args) {
        $html = '<input type="text" id="' . $args[1] . '" name="' . $args[1] . '" value="' . get_option($args[1]) .'">';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    function hsb_checkbox_callback($args) {
        $html = '<input type="checkbox" id="' . $args[1] .'" name="' . $args[1] .'" value="1" ' . checked(1, get_option($args[1]), false) . '/>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    function hsb_select_callback($args) {
        $html = '<select id="' . $args[1] . '" name="' . $args[1] . '">';
        foreach ($args['options'] as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected( get_option($args[1]), $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    public function hsb_colourpicker_callback($args) {
        $val = ( null !== get_option('hsb_beacon_colour')  ) ? get_option('hsb_beacon_colour') : $args['options']['default'];
        $html = '<input type="text" name="' . $args[1] .'" value="' . $val . '" class="hsb_beacon_colour" >';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    public function hsb_wp_user_roles_callback($args) {
        $html = '';
        $val = get_option($args[1]);
        $options = $val[1];
        $i = 1;
        foreach ($args['options'] as $key => $option) {
            $checked = '';
            $label = $option['label'];
            $option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($option['name']));

            $id = $args[1] . '-' . '' . '-'. $option;
            $name = $args[1] . '[' . $i . '][' . $option .']';
            $checked = checked($options[$option], 1, false);

            $html .= '<input id="' . esc_attr( $id ) . '" type="checkbox" value="1" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
        }
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    public function hsb_get_roles() {
        $roles = array();
        $i = 0;
        foreach (get_editable_roles() as $role_name => $role_info) {
            $roles[$i]['name'] = $role_name;
            $roles[$i]['label'] = $role_info['name'];
            $i++;
        }
        return $roles;
    }

    public function hsb_add_settings_page_callback() {
        ?>
        <div class="wrap">
            <h2><?php echo __('Help Scout beacon settings', 'wp-dashboard-beacon'); ?></h2>
            <?php settings_errors(); ?>
            <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'hsb_account_settings'; // end if?>

            <h2 class="nav-tab-wrapper">
                <a href="?page=dashboard_beacon&tab=hsb_account_settings" class="nav-tab <?php echo $active_tab == 'hsb_account_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Setup','wp-dashboard-beacon'); ?></a>
                <a href="?page=dashboard_beacon&tab=hsb_beacon_display_settings" class="nav-tab <?php echo $active_tab == 'hsb_beacon_display_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Display settings','wp-dashboard-beacon'); ?> </a>
                <a href="?page=dashboard_beacon&tab=hsb_permissions_settings" class="nav-tab <?php echo $active_tab == 'hsb_permissions_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Permissions','wp-dashboard-beacon'); ?> </a>            </h2>
            <form method="post" action="options.php">
                <?php
                if( $active_tab == 'hsb_account_settings' ) {
                    settings_fields( 'hsb_account_settings' );
                    do_settings_sections( 'hsb_account_settings' );
                } elseif( $active_tab =='hsb_beacon_display_settings' ) {
                    settings_fields( 'hsb_beacon_display_settings' );
                    do_settings_sections( 'hsb_beacon_display_settings' );
                } elseif( $active_tab =='hsb_permissions_settings' ) {
                    settings_fields( 'hsb_permissions_settings' );
                    do_settings_sections( 'hsb_permissions_settings' );
                } // end if/else

                submit_button();
                ?>
            </form>
        </div>
        <?php
	}

	public function hsb_add_settings_page() {
		add_submenu_page( 'tools.php', 'Dashboard Beacon', 'Dashboard Beacon', 'manage_options', 'dashboard_beacon', array( $this, 'hsb_add_settings_page_callback') );
	}

}
