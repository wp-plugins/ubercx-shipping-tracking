<?php
 /**
 * Plugin Name: UberCX WooCommerce Order Tracking
 * Plugin URI: http://woothemes.com/products/woocommerce-plugin-ubercx-shipping-tracking/
 * Description: Easily manage your order tracking. Customers will get notified when the order is complete and shipped.
 * Version: 1.1.2
 * Author: UberCX
 * Author URI: https://ubercx.io/
 * Developer: UberCX Team
 * Developer URI: https://ubercx.io/
 * Text Domain: woocommerce-extension
 * Domain Path: /languages
 *
 * Copyright: © 2009-2015 WooThemes.
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */
 
 // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'UC_MAIN_FILE' ) ) {
	define( 'UC_MAIN_FILE', __FILE__ );
}

if ( ! defined( 'UC_URL' ) ) {
	define( 'UC_URL', plugin_dir_url(__FILE__) );
}

require_once( plugin_dir_path( __FILE__ ) . 'class-uc-backend.php' );
require_once( plugin_dir_path( __FILE__ ) . 'class-uc-frontend.php' );

// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
register_activation_hook( __FILE__, array( 'UC_BACKEND', 'uc_plugin_activate' ) );
//Code For Deactivation 
register_deactivation_hook( __FILE__, array( 'UC_BACKEND', 'uc_plugin_deactivate_plugin' ) );
UC_BACKEND::get_instance();
UC_Frontend::get_instance();
?>