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

    function hsb_network_admin_menu() {
        // Create our options page.
        add_submenu_page('settings.php', __('Helpscout Beacon', 'wp-dashboard-beacon'),
            __('Beacon Settings', 'wp-dashboard-beacon'), 'manage_network_options',
            'hsb_network_options_page', array( $this, 'hsb_add_settings_page_callback'));

        // Account settings, displayed on tab 1
        add_settings_section(
            'hsb_network_options_page',                                     // ID used to identify this section and with which to register options
            __('Help Scout account settings', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_account_settings_description'),          // Callback used to render the description of the section
            'hsb_network_options_page'                                         // Page on which to add this section of options
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
        register_setting( 'hsb_network_options_page', 'hsb_helpscout_form_id' );
    }  
    
    function hsb_account_settings_description() {
        echo '<p>' . __('Connect your dashboard account','wp-dashboard-beacon') . '</p>';
    }
    
    function hsb_textfield_callback($args) {
        $html = '<input type="text" id="' . $args[1] . '" name="' . $args[1] . '" value="' . get_site_option($args[1]) .'">';
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
    
    function hsb_textarea_callback($args) {
        $html = '<textarea size="2" type="text" id="' . $args[1] . '" name="' . $args[1] . '">' . get_option($args[1]) . '</textarea>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    function hsb_checkbox_callback($args) {
        $html = '<input type="checkbox" id="' . $args[1] .'" name="' . $args[1] .'" value="1" ' . checked(1, get_option($args[1]), false) . '/>';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }
    
    public function hsb_colourpicker_callback($args) {
        $val = ( null !== get_option('hsb_beacon_colour')  ) ? get_option('hsb_beacon_colour') : $args['options']['default'];
        $html = '<input type="text" name="' . $args[1] .'" value="' . $val . '" class="hsb_beacon_colour" >';
        $html .= '<p class="description" id="tagline-description"> '  . $args[0] . ' </p>';
        echo $html;
    }

    function hsb_add_settings_page_callback() {
        if (isset($_GET['updated'])): ?>
            <div id="message" class="updated notice is-dismissible"><p><?php _e('Options saved.') ?></p></div>
        <?php endif; ?>
        <?php $active_tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'hsb_network_options_page'; // end if?>
        <div class="wrap">
            <h1><?php _e('Helpscout Beacon - Network settings', 'wp-dashboard-beacon'); ?></h1>
            <h2 class="nav-tab-wrapper">
                <a href="?page=dashboard_beacon&tab=hsb_network_options_page" class="nav-tab <?php echo $active_tab == 'hsb_network_options_page' ? 'nav-tab-active' : ''; ?>"><?php echo __('Setup','wp-dashboard-beacon'); ?></a>
            </h2>
            <form method="POST" action="edit.php?action=hsb_update_network_options"><?php
                settings_fields('hsb_network_options_page');
                do_settings_sections('hsb_network_options_page');
                submit_button(); ?>
            </form>
        </div>
    <?php
    }


    /**
     * This function here is hooked up to a special action and necessary to process
     * the saving of the options. This is the big difference with a normal options
     * page.
     */
     
    function hsb_update_network_options() {
      check_admin_referer('hsb_network_options_page-options');
    
      // This is the list of registered options.
      global $new_whitelist_options;
      $options = $new_whitelist_options['hsb_network_options_page'];
    
      foreach ($options as $option) {
        if (isset($_POST[$option])) {

          update_site_option($option, $_POST[$option]);
        } else {
          delete_site_option($option);
        }
      }
    
      wp_redirect(add_query_arg(array('page' => 'hsb_network_options_page',
          'updated' => 'true'), network_admin_url('settings.php')));
      exit;
    }

}