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
            'hsb_account_settings',                                     // ID used to identify this section and with which to register options
            __('Help Scout account settings', 'wp-dashboard-beacon'),    // Title to be displayed on the administration page
            array( $this, 'hsb_account_settings_description'),          // Callback used to render the description of the section
            'hsb_network_options_page'                                         // Page on which to add this section of options
        );
    }
    
    function hsb_account_settings_description() {
        echo '<p>' . __('Connect your dashboard account','wp-dashboard-beacon') . '</p>';
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