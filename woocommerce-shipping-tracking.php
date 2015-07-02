<?php
/**
 * UberCX Shipping Tracking
 * @package   UC
 * @author    Ubercx Developer <ubercx@jframeworks.com>
 * @link      http://ubercx.io/
 * @copyright 2015 Ubercx
 * @wordpress-plugin
 * Plugin Name: UberCX Shipping Tracking
 * Plugin URI:  http://ubercx.io/
 * Description: UberCX Shipping Tracking is a WooCommerce addon that allows customers to track shipping of their orders from their account section.
 * Version: 1.0.0
 * Author: UberCX Developer
 * Author URI:  http://ubercx.io/
 * License: GPL-2.0
 * Text Domain: ubercx-shipping-tracking
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