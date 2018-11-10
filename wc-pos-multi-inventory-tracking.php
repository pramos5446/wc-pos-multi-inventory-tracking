<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
 * @since             1.0.0
 * @package           Wc_Pos_Multi_Inventory_Tracking
 *
 * @wordpress-plugin
 * Plugin Name:       WC POS Multi Inventory Tracking
 * Plugin URI:        https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
 * Description:       An addon to track the stock levels of multiple outlets in the WooCommerce Point Of Sale 	developed by Actuality Extensions.
 * Version:           1.0.0
 * Author:            Pablo Ramos
 * Author URI:        https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wc-pos-multi-inventory-tracking
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'PLUGIN_NAME_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wc-pos-multi-inventory-tracking-activator.php
 */
function activate_wc_pos_multi_inventory_tracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-pos-multi-inventory-tracking-activator.php';
	Wc_Pos_Multi_Inventory_Tracking_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wc-pos-multi-inventory-tracking-deactivator.php
 */
function deactivate_wc_pos_multi_inventory_tracking() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wc-pos-multi-inventory-tracking-deactivator.php';
	Wc_Pos_Multi_Inventory_Tracking_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wc_pos_multi_inventory_tracking' );
register_deactivation_hook( __FILE__, 'deactivate_wc_pos_multi_inventory_tracking' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wc-pos-multi-inventory-tracking.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wc_pos_multi_inventory_tracking() {

	$plugin = new Wc_Pos_Multi_Inventory_Tracking();
	$plugin->run();

}
run_wc_pos_multi_inventory_tracking();
