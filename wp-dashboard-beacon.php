<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              http://onedge.be
 * @since             1.0.0
 * @package           Wp_Dashboard_Beacon
 *
 * @wordpress-plugin
 * Plugin Name:       Dashboard Beacon
 * Plugin URI:        http://onedge.be
 * Description:       Easily integrate a Helpscout Beacon in your dashboard or website.
 * Version:           1.0.0
 * Author:            Jan Henckens
 * Author URI:        http://onedge.be
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-dashboard-beacon
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-dashboard-beacon-activator.php
 */
function activate_wp_dashboard_beacon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-dashboard-beacon-activator.php';
	Wp_Dashboard_Beacon_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-dashboard-beacon-deactivator.php
 */
function deactivate_wp_dashboard_beacon() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-dashboard-beacon-deactivator.php';
	Wp_Dashboard_Beacon_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_dashboard_beacon' );
register_deactivation_hook( __FILE__, 'deactivate_wp_dashboard_beacon' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-dashboard-beacon.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_dashboard_beacon() {

	$plugin = new Wp_Dashboard_Beacon();
	$plugin->run();

}
run_wp_dashboard_beacon();
