<?php
/**
 * UberCX Shipping Tracking
 * @package UC
 * @author  Ubercx Developer <ubercx@jframeworks.com>
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UC_BACKEND{

	/**
	 *
	 * @since   1.0.0
	 *
	 * @var     string
	 */
	protected $version = '1.0.0';
	/**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
	
	/**
	 * Unique identifier for your plugin.
	 *
	 * @since    1.0.0
	 *
	 * @var      string
	 */
	public $plugin_slug = 'uc-tracking-configuration';

	/**
	 * Initialize the plugin by setting localization, filters.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		
		// Database variables
		global $wpdb;
		$this->db 					= &$wpdb;
		//Adds menu
		add_action( 'admin_menu', array( &$this, 'uc_admin_menu'), 12 );
		//uc register settings
	    add_action( 'admin_init', array( &$this, 'uc_register_settings' ) );	
		//adds meta box in order overview page
		add_action('add_meta_boxes', array( &$this, 'uc_add_order_tracking_meta_box') );
		//save tracking values
		add_action( 'woocommerce_process_shop_order_meta', array(&$this, 'save_tracking_details_for_orders') );
		
		//Simon - Aug 2015
		//Add the email hook
		add_action('woocommerce_email_before_order_table', array(&$this, 'insert_info_on_email'));
		
		//Simon - Aug 2015
		//Add the hook to show the shipped icon
		add_action( 'manage_shop_order_posts_custom_column', array( $this, 'show_shipped_icon' ) );
	}
	
	
	/**
	 * Function to register activation actions
	 * 
	 * @since 1.0.0
	 */
	function uc_plugin_activate(){
			
		//Check for WooCommerce Installment
		if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) and current_user_can( 'activate_plugins' ) ) {
			// Stop activation redirect and show error
			wp_die('Sorry, but this plugin requires the Woocommerce to be installed and active. <br><a href="' . admin_url( 'plugins.php' ) . '">&laquo; Return to Plugins</a>');
		}
		update_option('uc_plugin_activate', true);
	}	
   
 	/**
	 * Function to register deactivation actions
	 * 
	 * @since 1.0.0
	 */
	function uc_plugin_deactivate_plugin(){ 
	
		delete_option('uc_plugin_activate');
		delete_option('uc_settings');
	}
	
	
	/**
	 * Function to add admin menu
	 * 
	 * @since 1.0.0
	 */
	function uc_admin_menu() {
		
		$admin_role = 'manage_options';
		add_submenu_page( 'woocommerce', 'UberCX Shipping Tracking' ,  'UberCX Shipping Tracking', $admin_role, $this->plugin_slug, array( $this, 'uc_main_admin_page' )); 
	}
	
	/**
	 * Function to show main admin setting page
	 * 
	 * @since 1.0.0
	 */
	function uc_main_admin_page() {
		$uc_options = get_option('uc_settings');
		include('assets/admin/views/uc-tracking-configuration.php');
	}
	
	
	/**
	 * Function to register the plugin settings options
	 * 
	 * @since 1.0.0
	 */
	public function uc_register_settings() {
		register_setting('uc_register_settings', 'uc_settings' );
	}	
	
	/**
	 * Function to get end-point of API
	 * 
	 * @since 1.0.0
	 */
	function uc_getApiUrl(){
		if(file_exists(plugin_dir_path( __FILE__ ).'config.txt')){
			$response = file_get_contents(plugin_dir_path( __FILE__ ).'config.txt');
			$response = json_decode($response);
			if(!empty($response)){
				return $response->api_endpoint;
			}
		} 
	}
	/**
	 * Function to get userkey
	 * 
	 * @since 1.0.0
	 */
	public function uc_getUserKey(){
		$sq_options = get_option('uc_settings');
		$user_key = $sq_options['user_key'];
		return $user_key;
	}

	/**
	 * Function to check if plugin is enabled
	 * 
	 * @since 1.0.0
	 */
     public function uc_isEnabled(){
		$sq_options = get_option('uc_settings');
		$enable = $sq_options['enable'];
		return $enable;
	}	
	
	/**
	 * Function to add order tracking details to the order overview page
	 * 
	 * @since 1.0.0
	 */
	function uc_add_order_tracking_meta_box(){
		
		 add_meta_box('woocommerce-ubercx', 'UberCX Tracking Information', array(&$this, 'uc_meta_box_view'), 'shop_order', 'side', 'high');
	}
	
	/**
	 * Function to display order tracking form on order overview page
	 * 
	 * @since 1.0.0
	 */
	function uc_meta_box_view(){
		
		global $post, $wpdb;
		$carrier_list = array('UPS', 'USPS', 'FEDEX', 'DHL');
		$carrier_name = get_post_meta($post->ID, '_ubercx_carrier_name', true);
		echo '<p class="form-field">';
		echo '<label for="ubercx_carrier">Carrier:</label><br />';
		echo '<select id="ubercx_carrier" name="ubercx_carrier">';
		echo '<option value="">-Please Select Carrier-</option>';
		foreach($carrier_list as $list){
			$selected =  ( $list == $carrier_name ) ? 'selected="selected"' : '';
			echo '<option value="'.$list.'" '.$selected.'>'.$list.'</option>';
		}
		echo '</select>';
		
		woocommerce_wp_text_input(array(
			'id' => 'ubercx_tracking_number',
			'label' => 'Tracking Number',
			'placeholder' => 'Tracking Number',
			'description' => 'Tracking Number',
			'class' => '',
			'value' => get_post_meta($post->ID, '_ubercx_tracking_number', true),
		));
	}
	
	/**
	 * Function to save tracking details
	 * 
	 * @since 1.0.0
	 */
	function save_tracking_details_for_orders( $post_id ){
		
		if ( isset( $_POST['ubercx_tracking_number'] ) ) {
			update_post_meta( $post_id, '_ubercx_carrier_name', woocommerce_clean( $_POST['ubercx_carrier'] ) );
			update_post_meta( $post_id, '_ubercx_tracking_number', woocommerce_clean( $_POST['ubercx_tracking_number'] ) );
		}
	}

	
	/**
	 * Inserts the order details on the completed email
	 * Created by Simon - Aug 2015
	 */
	function insert_info_on_email($order){
		
		//first lets get the meta data for this order
		$carrier_code = get_post_meta($order->id, '_ubercx_carrier_name', true);
		$track_id = get_post_meta($order->id, '_ubercx_tracking_number', true);
		
		
		//if no tracking number return
		if($track_id == ''){
			return;
		}
		
		$opt = get_option('uc_settings');
			
		$txt = '';
			
		if(isset($opt['email_text'])){
			$txt = $opt['email_text'];
		} else {
			//no email text template so return
			return;
		}
			
		//now do the replacement
		$txt = str_replace('[carrier]', $carrier_code, $txt);
		$txt = str_replace('[tracking_id]', $track_id, $txt);
		
		echo $txt;	
	}
	
	
	//Simon Aug 2015
	//Show the shipped icon on the order summary page
	function show_shipped_icon( $col ){
		if ( $col != 'order_status'  ) {
			return;
		}
		
		global $the_order;
		

		//if we don't have a shipping number for this order just return
		$carrier_code = get_post_meta($the_order->id, '_ubercx_carrier_name', true);
		$track_id = get_post_meta($the_order->id, '_ubercx_tracking_number', true);
		
		
		//if no tracking number return
		if($track_id == '' || $carrier_code == ''){
			return;
		}

		//create the tooltip message
		//TODO - need to add localization
		$msg = 'The order shipped via: ' . $carrier_code . ' with tracking number: ' . $track_id;
		//ok so lets show it!
		echo '<a class="tips" style="display:inline-block;height:30px; padding-top:0; padding-bottom:0" href="#" data-tip="' . $msg . '">';
		echo '<img  style="height:30px;" src="' . plugins_url("assets/shipped.png", __FILE__) . '" data-tip="dsfdsfdf" /></a>';
	}
	
	/**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	
}