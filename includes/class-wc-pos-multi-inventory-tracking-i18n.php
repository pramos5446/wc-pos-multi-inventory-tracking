<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
 * @since      1.0.0
 *
 * @package    Wc_Pos_Multi_Inventory_Tracking
 * @subpackage Wc_Pos_Multi_Inventory_Tracking/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wc_Pos_Multi_Inventory_Tracking
 * @subpackage Wc_Pos_Multi_Inventory_Tracking/includes
 * @author     Pablo Ramos <pramos@elmorrotechnologies.com>
 */
class Wc_Pos_Multi_Inventory_Tracking_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-pos-multi-inventory-tracking',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
