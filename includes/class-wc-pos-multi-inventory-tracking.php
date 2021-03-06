<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/elmorrotechnologies/wc-pos-multi-inventory-tracking
 * @since      1.0.0
 *
 * @package    Wc_Pos_Multi_Inventory_Tracking
 * @subpackage Wc_Pos_Multi_Inventory_Tracking/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Wc_Pos_Multi_Inventory_Tracking
 * @subpackage Wc_Pos_Multi_Inventory_Tracking/includes
 * @author     Pablo Ramos <pramos@elmorrotechnologies.com>
 */
class Wc_Pos_Multi_Inventory_Tracking {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Wc_Pos_Multi_Inventory_Tracking_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $plugin_name    The string used to uniquely identify this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'PLUGIN_NAME_VERSION' ) ) {
			$this->version = PLUGIN_NAME_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'wc-pos-multi-inventory-tracking';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Wc_Pos_Multi_Inventory_Tracking_Loader. Orchestrates the hooks of the plugin.
	 * - Wc_Pos_Multi_Inventory_Tracking_i18n. Defines internationalization functionality.
	 * - Wc_Pos_Multi_Inventory_Tracking_Admin. Defines all hooks for the admin area.
	 * - Wc_Pos_Multi_Inventory_Tracking_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-pos-multi-inventory-tracking-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wc-pos-multi-inventory-tracking-i18n.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wc-pos-multi-inventory-tracking-admin.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wc-pos-multi-inventory-tracking-public.php';

		$this->loader = new Wc_Pos_Multi_Inventory_Tracking_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Wc_Pos_Multi_Inventory_Tracking_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Wc_Pos_Multi_Inventory_Tracking_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Wc_Pos_Multi_Inventory_Tracking_Admin( $this->get_plugin_name(), $this->get_version() );

		
		/***********************
				Actions
		************************/
		//Remove Product From Stock Table
		$this->loader->add_action('after_delete_post',$plugin_admin,'wc_pos_remove_product_stocks');
		
		//Set Stock Level
		$this->loader->add_action('woocommerce_product_set_stock',$plugin_admin, 'wc_pos_set_product_stock_by_outlet');
		
		//Add Product
		$this->loader->add_action('save_post_product',$plugin_admin,'wc_pos_add_product_to_outlets_stocks',10,3);
	
		//For adding functionalities to UI components in admin pages.
		$this->loader->add_action('admin_init',$plugin_admin,'wc_pos_admin_pages_functions',10);
		
		//To Create a New blog when user register
		$this->loader->add_action( 'wpmu_new_blog',$plugin_admin,'user_role_to_new_blog', 10, 2 );
		
		$this->loader->add_action('admin_enqueue_scripts',$plugin_admin,'wc_pos_display_import_to_outlet_button',0);
		/***********************
				Filters
		************************/

		//Get Product's Stock By Outlet
		add_filter( 'woocommerce_product_get_stock_quantity',$plugin_admin,'wc_pos_get_product_stock_by_outlet', 10, 2 );
		
		add_filter( 'woocommerce_product_variation_get_stock_quantity',$plugin_admin,'wc_pos_get_product_stock_by_outlet', 10, 2 );


	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Wc_Pos_Multi_Inventory_Tracking_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Wc_Pos_Multi_Inventory_Tracking_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}

}
