<?php
	
	/**
	* The admin-specific functionality of the plugin.
	*
	* @link       https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
	* @since      1.0.0
	*
	* @package    Wc_Pos_Multi_Inventory_Tracking
	* @subpackage Wc_Pos_Multi_Inventory_Tracking/admin
	*/
	
	/**
	* The admin-specific functionality of the plugin.
	*
	* Defines the plugin name, version, and two examples hooks for how to
	* enqueue the admin-specific stylesheet and JavaScript.
	*
	* @package    Wc_Pos_Multi_Inventory_Tracking
	* @subpackage Wc_Pos_Multi_Inventory_Tracking/admin
	* @author     Pablo Ramos <pramos@elmorrotechnologies.com>
	*/
	class Wc_Pos_Multi_Inventory_Tracking_Admin {
	
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
	
		
	
		
		function wc_pos_set_product_stock_by_outlet($product) {
			global $wpdb;
			
			$outlet_id = esc_attr(get_user_meta(get_current_user_id(), 'outlet', true));
			$current_global_stock = get_post_meta($product->get_id(), '_stock',true);
			
			if ($current_global_stock === NULL){ 
				$current_global_stock = 0;
			}
			
			$wpdb->update(
							$wpdb->prefix."wc_pos_stock_track",
							array('stock_level' => $current_global_stock),
							array('product_id' => $product->get_id(),'outlet_id' => $outlet_id)
						);
			
			
			if ($current_global_stock > 0){
				update_post_meta($product->get_id(),'_stock_status', 'instock');
			}else{
				update_post_meta($product->get_id(),'_stock_status', 'outofstock');
			}
			
			$result = $wpdb->get_results("SELECT SUM(stock_level) as total FROM ".$wpdb->prefix."wc_pos_stock_track	WHERE product_id = ".$product->get_id());
			
			update_post_meta($product->get_id(),'_stock', $result[0]->total);
			
		}
		
		function wc_pos_get_product_stock_by_outlet( $value, $product ) {
			
			global $wpdb;
			
			$value = 0;
			$outlet_id = esc_attr(get_user_meta(get_current_user_id(), 'outlet', true));
			$product_data = $wpdb->get_results("SELECT stock_level FROM ".$wpdb->prefix."wc_pos_stock_track
												WHERE product_id = ".$product->	get_id()." AND outlet_id = ".$outlet_id);
			
			if (sizeof($product_data)>0){
				$value = $product_data[0]->stock_level;
			}
			
			//$value = $product->get_id(); // <== Just for testing
			return $value;
		}
		
		
		function wc_pos_remove_product_stocks($post_id){
			global $wpdb;
			
			$wpdb->delete($wpdb->prefix."wc_pos_stock_track",
						array('product_id'=>$post_id)
						);
		}
		
		
		function wc_pos_add_product_to_outlets_stocks( $post_id, $post, $update ){
			global $wpdb;
			
			$outlets = $wpdb->get_results("SELECT id FROM ".$wpdb->prefix."wc_poin_of_sale_outlets");
			
			foreach($outlets as $outlet){
				$wpdb->insert($wpdb->prefix."wc_pos_stock_track",
								array(
									'outlet_id'=>esc_attr($outlet->id),
									'product_id'=>$post_id,
									'stock_level'=>'0'
									),
								array('%d','%d','%d')
							);	
			}
		}
		
		
		function wc_pos_display_import_to_outlet_button(){
			global $pagenow;
			
			if (!isset($_GET['page'])){return;}
		
			if ($pagenow == 'admin.php' && $_GET['page'] == 'wc_pos_outlets') {
				wp_enqueue_script( 'js_wc_pos_sync_outlet_button', plugin_dir_path( __FILE__ ) .'/admin/js/wc_pos_sync_outlet_button.js' );
			}
		}
		
		
		//For adding functionalities to UI components in admin pages.
		function wc_pos_admin_pages_functions(){
			global $pagenow;
			
			if (!isset($_GET['page'])){return;}
			if (!isset($_GET['action'])){return;}
			
			if ($pagenow == 'admin.php') {
				
				if ($_GET['page'] == 'wc_pos_outlets'){
					switch ($_GET['action']){
						case 'add_products':
							wc_pos_add_products_to_outlet();
							break;
					}
				}
			}
		}
		
		
		function wc_pos_add_products_to_outlet(){
			
			global $wpdb;
			
			$outlets = $wpdb->get_results("SELECT id FROM {$wpdb->prefix}wc_poin_of_sale_outlets outlets 
										WHERE outlets.id NOT IN (SELECT outlet_id FROM {$wpdb->prefix}wc_pos_stock_track)");
			
			$products = $wpdb->get_results("SELECT id FROM {$wpdb->posts} WHERE post_type = 'product' and post_title != 'POS custom product'");
			
			
			foreach ($outlets as $outlet){
				foreach ($products as $product){
					//echo $outlet->id."=>".$product->id."\n";
					$wpdb->insert($wpdb->prefix."wc_pos_stock_track",
								array('outlet_id' => $outlet->id,'product_id' => $product->id,'stock_level' => stock_level));
				}			
			}
			//echo 'executed!';
			
		}
}