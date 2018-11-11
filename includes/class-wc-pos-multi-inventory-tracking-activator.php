<?php

/**
 * Fired during plugin activation
 *
 * @link       https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
 * @since      1.0.0
 *
 * @package    Wc_Pos_Multi_Inventory_Tracking
 * @subpackage Wc_Pos_Multi_Inventory_Tracking/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wc_Pos_Multi_Inventory_Tracking
 * @subpackage Wc_Pos_Multi_Inventory_Tracking/includes
 * @author     Pablo Ramos <pramos@elmorrotechnologies.com>
 */
class Wc_Pos_Multi_Inventory_Tracking_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		$this->create_wc_pos_stock_track_table();
	}
	
	
	function create_wc_pos_stock_track_table(){
		global $wpdb;

		$table_name = $wpdb->prefix . 'wc_pos_stock_track';
	
		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE $table_name (
			outlet_id int,
			product_id int,
			stock_level int,
			PRIMARY KEY  (outlet_id,product_id)
		) $charset_collate;";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );

	}

}
