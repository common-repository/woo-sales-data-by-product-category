<?php
/**
 * Plugin Name: WooCommerce Sales Data by Product Category
 * Description: Get Sales data based on Product Category.
 * Version: 1.2
 * Author: MohammedYasar Khalifa
 * Author URI: https://myasark.wordpress.com
 * Text Domain: woo-sales-data-by-product-category
 * Domain Path: /languages
 * License: GPLv2
 */
if ( ! defined( 'ABSPATH' ) ) {exit; }
class WooCommerce_Sales_Data_by_Product_Category{  
	 function __construct() {
       add_action('admin_menu', array($this, 'wc_gsd_bpc_plugin_menu'));
    }
    function wc_gsd_bpc_plugin_menu() { 
      add_submenu_page('woocommerce', 'Export Sales Data by Category', 'Export Sales Data by Product Category', 'view_woocommerce_reports', 'wc_gsd_bpc',  array($this, 'wc_gsd_bpc_function'));
    }
    function wc_gsd_bpc_function() {        
      if (current_user_can("administrator")) {
            include (plugin_dir_path(__FILE__) . 'includes/csv_export.php');
        }
    }
}
$WooCommerce_Sales_Data_by_Product_Category = new WooCommerce_Sales_Data_by_Product_Category();
