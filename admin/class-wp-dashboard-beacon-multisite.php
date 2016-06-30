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
        
        register_setting( 'hsb_network_options_page', 'hsb_helpscout_subdomain' );
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

    function hsb_add_settings_page_callback() {
      if (isset($_GET['updated'])): ?>
    <div id="message" class="updated notice is-dismissible"><p><?php _e('Options saved.') ?></p></div>
      <?php endif; ?>
    <div class="wrap">
      <h1><?php _e('Helpscout Beacon - Network settings', 'wp-dashboard-beacon'); ?></h1>
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