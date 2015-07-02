<?php
/**
 * WooCommerce Shipping Tracking
 * @package UC
 * @author  Ubercx Developer <info@ubercx.com>
 */
 
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class UC_Frontend extends UC_BACKEND{
	
	protected static $instance = null;

	/**
	 * Initialize the plugin by setting localization, filters.
	 *
	 * @since     1.0.0
	 */
	function __construct() {
		
		//action for the fron-end order track view 
		add_action('woocommerce_view_order', array(&$this, 'uc_front_tracking_display'));
		//handles tracking request
		add_action('wp_ajax_uc_get_tracking_details', array(&$this, 'uc_get_tracking_details'));
		add_action('wp_ajax_nopriv_uc_get_tracking_details', array(&$this, 'uc_get_tracking_details'));

	}
	
	/**
	 * Function to display shipping tracking details tracking button.
	 *
	 * @since     1.0.0
	 */
	 function uc_front_tracking_display( $order_id ){

		$isEnable =  parent::uc_isEnabled();
		if( $isEnable == 'Yes' ){
			$carrier_code = get_post_meta($order_id, '_ubercx_carrier_name', true);
			$track_id = get_post_meta($order_id, '_ubercx_tracking_number', true);
			if($carrier_code != '' &&  $track_id != ''){
				$onclick = "window.open(this.href, '','width=800,height=600,resizable=yes,scrollbars=yes'); return false;";
				echo '<a  onclick="'.$onclick.'" class="button view" href="'.admin_url( 'admin-ajax.php' ).'/?ajax=true&carrier_code='.$carrier_code.'&track_id='.$track_id.'&order='.$order_id.'&action=uc_get_tracking_details">Track Shipment</a>';
			}
		}
	 }
	 
	 /**
	 * Function to display shipping tracking details.
	 *
	 * @since     1.0.0
	 */
	 function uc_get_tracking_details(){
		 if( isset($_GET['carrier_code']) && isset($_GET['track_id']) && !empty($_GET['carrier_code']) && !empty($_GET['track_id'])){	
			$carrier_code = $_GET['carrier_code'];
			$track_id = $_GET['track_id'];
			$response = $this->uc_get_tracking_details_by_tracking_id( $carrier_code, $track_id );
			include( plugin_dir_path( __FILE__ ) .'assets/front-end/views/uc-tracking-details.php');
		 }
		 die();
	 }
	 
	 /**
	 * Function to get tracking details based on tracking id and carrier code.
	 *
	 * @since     1.0.0
	 */
	 function uc_get_tracking_details_by_tracking_id( $carrier_code, $track_id ){

		$user_key = parent::uc_getUserKey();
		$api_url  =  parent::uc_getApiUrl();
		$isEnable =  parent::uc_isEnabled();
		if($isEnable == 'Yes'){
			
			$url = $api_url.'?carrier='.strtoupper($carrier_code).'&trackId='.$track_id.'';    
			// Start cURL
			$curl = curl_init();
			// Headers
			$headers = array();
			$headers[] = 'user_key:'.$user_key;
			//$headers[] = 'Accept: application/json';
			curl_setopt( $curl, CURLOPT_URL, $url );
			curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
			curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
			curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
			curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
			curl_setopt( $curl, CURLOPT_HEADER, false);
			// Get response
			$response = curl_exec($curl);
			// Get HTTP status code
			$status = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			// Close cURL
			curl_close($curl);
			// Return response from server
			if($response!=''){
				$response = json_decode($response);	
			}
			return $response;
		}
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