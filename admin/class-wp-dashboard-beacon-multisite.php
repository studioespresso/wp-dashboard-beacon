<?php

/**
 * The admin-specific functionality of the plugin.
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
class Wp_dashboard_Beacon_Multisite {

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
        * class
        */
        $user = new WP_User( get_current_user_id() );
        if(!empty($user->first_name) && !empty($user->last_name)) {
            $userName = $user->first_name . ' ' . $user->last_name;
        } else {
            $userName = $user->nickname;
        }
        $userEmail = $user->user_email;

        $userRole = $user->roles['0'];
        $allowedRoles = get_option('hsb_allowed_user_roles');
        if($allowedRoles != "") {
            if(array_key_exists($userRole, $allowedRoles)) {
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

    function hsb_enqueue_colourpicker( $hook ) {
        if ( 'tools_page_dashboard_beacon' != $hook AND 'settings_page_hsb_network_options_page' != $hook) {
            return;
        }
        // Add the color picker css file
        wp_enqueue_style( 'wp-color-picker' );
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'js/wp-dashboard-beacon-colourpicker.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }


    function hsb_network_admin_menu() {
        // Create our options page.
        add_submenu_page('settings.php', __('Helpscout Beacon', 'wp-dashboard-beacon'),
            __('Beacon Settings', 'wp-dashboard-beacon'), 'manage_network_options',
            'hsb_network_options_page', array( $this, 'hsb_add_settings_page_callback'));

        add_settings_section(
            'hsb_network_options_page',
            __('Customize your beacon', 'wp-dashboard-beacon'),
            array( $this, 'hsb_account_settings_description'),
            'hsb_network_options_page'
        );

        // Form ID field
        add_settings_field(
            'hsb_helpscout_form_id',                                      // ID used to identify the field throughout the theme
            'Beacon form ID',                                                   // The label to the left of the option interface element
            array( $this, 'hsb_textfield_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_network_options_page',                                         // The page on which this option will be displayed
            'hsb_network_options_page',                                     // The name of the section to which this field belongs
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
            'hsb_network_options_page',                                         // The page on which this option will be displayed
            'hsb_network_options_page',                                     // The name of the section to which this field belongs
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
            'hsb_network_options_page',                                         // The page on which this option will be displayed
            'hsb_network_options_page',                                     // The name of the section to which this field belongs
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

        register_setting( 'hsb_network_options_page', 'hsb_helpscout_subdomain' );
        register_setting( 'hsb_network_options_page', 'hsb_helpscout_form_id' );
        register_setting( 'hsb_network_options_page', 'hsb_beacon_options' );


        /**
         * Display settings
         */
        add_settings_section(
            'hsb_network_display_settings',                                     // ID used to identify this section and with which to register options
            __('Customize your beacon', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_network_display_settings_description'),          // Callback used to render the description of the section
            'hsb_network_display_settings'                                         // Page on which to add this section of options
        );

        // Beacon icon
        add_settings_field(
            'hsb_beacon_icon',                                      // ID used to identify the field throughout the theme
            __('Beacon Icon', 'wp-dashboard-beacon'),                                                   // The label to the left of the option interface element
            array( $this, 'hsb_select_callback'),              // The name of the function responsible for rendering the option interface
            'hsb_network_display_settings',                                         // The page on which this option will be displayed
            'hsb_network_display_settings',                                     // The name of the section to which this field belongs
            array(                                                      // The array of arguments to pass to the callback. In this case, just a description.'dashboard_enable_contact_form'
                __('Select an icon to be used in your beacon', 'wp-dashboard-beacon'),
                'hsb_beacon_icon',
                'options' => array(
                    '' => 'Select an icon',
                    'question' => __('Question', 'wp-dashboard-beacon'),
                    'beacon' => __('Beacon', 'wp-dashboard-beacon'),
                    'buoy' => __('Buoy', 'wp-dashboard-beacon'),
                    'message' => __('Message', 'wp-dashboard-beacon'),
                    'search' => __('Search', 'wp-dashboard-beacon')
                )
            )
        );

        register_setting( 'hsb_network_display_settings', 'hsb_beacon_icon' );
        
        /**
         * Network settings
         */
        add_settings_section(
            'hsb_network_settings',
            __('Network settings', 'wp-dashboard-beacon'),
            array( $this, 'hsb_network_settings_description'),
            'hsb_network_settings'
        );
        
        add_settings_field(
            'hsb_network_enabled_sites',
            __('Enable beacon on these sites', 'wp-dashboard-beacon'),
            array($this, 'hsb_checkboxes_callback'),
            'hsb_network_settings',
            'hsb_network_settings',
            array(__('Enabled sites will inherit the beacon settings from the network', 'wp-dashboard-beacon'),
            'hsb_network_enabled_sites',
            'options' => $this->get_network_sites()
            )
        );
        
        register_setting( 'hsb_network_settings', 'hsb_network_enabled_sites');

    }

    function hsb_account_settings_description() {}
    function hsb_beacon_settings_description() {}
    function hsb_permissions_settings_description() {}
    function hsb_network_settings_description() {}
    function hsb_network_display_settings_description() {}

    function hsb_textfield_callback($args) {
        $html = '<input type="text" id="' . $args[1] . '" name="' . $args[1] . '" value="' . get_site_option($args[1]) .'">';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }
    
    function hsb_select_callback($args) {
        $html = '<select id="' . $args[1] . '" name="' . $args[1] . '">';
        foreach ($args['options'] as $key => $value) {
            $html .= '<option value="' . $key . '"' . selected( get_site_option($args[1]), $key, false) . '>' . $value . '</option>';
        }
        $html .= '</select>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }
    
    function hsb_textarea_callback($args) {
        $html = '<textarea size="2" type="text" id="' . $args[1] . '" name="' . $args[1] . '">' . get_site_option($args[1]) . '</textarea>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    function hsb_checkbox_callback($args) {
        $html = '<input type="checkbox" id="' . $args[1] .'" name="' . $args[1] .'" value="1" ' . checked(1, get_site_option($args[1]), false) . '/>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }
    
    public function hsb_colourpicker_callback($args) {
        $val = ( null !== get_site_option('hsb_beacon_colour')  ) ? get_site_option('hsb_beacon_colour') : $args['options']['default'];
        $html = '<input type="text" name="' . $args[1] .'" value="' . $val . '" class="hsb_beacon_colour" >';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }
    
    public function hsb_checkboxes_callback($args) {
        $html = '';
        $val = get_site_option($args[1]);
        $options = $val;
        foreach ($args['options'] as $key => $option) {
            $checked = '';
            $label = $option['label'];
            $option = preg_replace('/[^a-zA-Z0-9._\-]/', '', strtolower($option['id']));
            $id = $args[1] . '-' . '' . '-'. $option;
            $name = $args[1] . '[' . $option .']';
            if($options) {
                $checked = checked($options[$option], 1, false);
            }
            $html .= '<input id="' . esc_attr( $id ) . '" type="checkbox" value="1" name="' . esc_attr( $name ) . '" ' . $checked . ' /><label for="' . esc_attr( $id ) . '">' . esc_html( $label ) . '</label><br />';
        }
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }
    
    public function get_network_sites() {
        $sites = array();
        $i = 0;
        foreach (wp_get_sites() as $id => $site) {
            $blog = get_blog_details($site['blog_id']);
            $sites[$blog->blog_id]['id'] = $blog->blog_id;
            $sites[$blog->blog_id]['name'] = $blog->blogname;
            $sites[$blog->blog_id]['label'] = $blog->blogname;
            $sites[$blog->blog_id]['path'] = $blog->siteurl;
            $i++;
        }
        return $sites;
    }


    function hsb_add_settings_page_callback() {
        if (isset($_GET['updated'])): ?>
            <div id="message" class="updated notice is-dismissible"><p><?php _e('Options saved.') ?></p></div>
        <?php endif; ?>
        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'hsb_network_options_page'; // end if?>
        <div class="wrap">
            <h1><?php _e('Helpscout Beacon - Network settings', 'wp-dashboard-beacon'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=hsb_network_options_page&tab=hsb_network_options_page" class="nav-tab <?php echo $active_tab == 'hsb_network_options_page' ? 'nav-tab-active' : ''; ?>"><?php echo __('Setup','wp-dashboard-beacon'); ?></a>
                <a href="?page=hsb_network_options_page&tab=hsb_network_display_settings" class="nav-tab <?php echo $active_tab == 'hsb_network_display_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Display settings','wp-dashboard-beacon'); ?></a>
                <a href="?page=hsb_network_options_page&tab=hsb_network_settings" class="nav-tab <?php echo $active_tab == 'hsb_network_settings' ? 'nav-tab-active' : ''; ?>"><?php echo __('Network settings','wp-dashboard-beacon'); ?> </a>
            </h2>
            <form method="POST" action="edit.php?action=hsb_update_settings"><?php
                settings_fields('hsb_network_options_page');
                if( $active_tab == 'hsb_network_options_page' ) {
                    do_settings_sections('hsb_network_options_page');
                } elseif ( $active_tab == 'hsb_network_display_settings' ) {
                    do_settings_sections('hsb_network_display_settings');
                } elseif ( $active_tab == 'hsb_network_settings' ) {
                    do_settings_sections('hsb_network_settings');
                };
                submit_button(); ?>
            </form>
        </div>
    <?php
    }
     
    function hsb_update_settings() {
        check_admin_referer('hsb_network_options_page-options');
    
        // This is the list of registered options.
        global $new_whitelist_options;
        $options = array_merge($new_whitelist_options['hsb_network_options_page'], $new_whitelist_options['hsb_network_settings'], $new_whitelist_options['hsb_network_display_settings']);
        
        foreach ($options as $option) {
            if (isset($_POST[$option])) {
                update_site_option($option, $_POST[$option]);
            }
            if($option == 'hsb_network_enabled_sites' && $_POST['hsb_network_enabled_sites'] === NULL) {
                update_site_option($option, '');
            }
        }
        
        wp_redirect(add_query_arg(array('page' => 'hsb_network_options_page',
            'updated' => 'true'), network_admin_url('settings.php')));
        exit;
    }

}