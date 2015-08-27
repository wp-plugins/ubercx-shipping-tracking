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
?>
<script>


</script>


<style type="text/css">
.shipping-summary-container {
    position: relative;
}
.shipping-summary-container .imageContent {
    opacity: 0.5;
    top:-10px;
    left:25%;
    position: absolute;
    z-index: 0;
}
.shipping-summary-container .ubercxContent {
  z-index: 1;   
}

.tracking-summary-table {
	border: 2px solid #8AC007; 
	border-radius: 15px; 
	border-collapse: initial;
}

.tracking-summary-table th {
	background-color: rgb(138, 192, 7);
        padding: 0px;
        text-align: center;
        vertical-align: middle;	
}

.shipping_details-container .tracking_details {
		border: 2px solid #8AC007; 
		border-radius: 25px 25px 0 0; 
		border-collapse: initial;
	}
	
.tracking_details th {
		background-color: rgb(138, 192, 7);		
	}


table tr.last th, table tr.last td {
	border-bottom: 0 none !important;
}
table tbody th {
	border-bottom: 1px solid #8AC007;
	border-right: 1px solid #8AC007;
}

table tbody td {
	border-bottom: 1px solid #8AC007;
}
table td {
	/*padding: 3px 8px;*/
}
caption, th, td {
	font-weight: normal;
	text-align: center;
	vertical-align: top;
}

table .odd {
	background: #f8f7f5 none repeat scroll 0 0;
}
table .even {
	background: #eeeded none repeat scroll 0 0;
}

button {
	float: right;
}

.woocommerce_tracking{
	margin-top: 15px;
}
</style>
<div class="woocommerce_tracking">
<?php
$mediaURL = plugins_url();
//echo $mediaURL;
$uberCXMediaURL = $mediaURL . '/ubercx-shipping-tracking/assets/';
$shippedImageURL = $uberCXMediaURL . 'shipped_blue_sm.png';
$deliveredImageURL = $uberCXMediaURL . 'delivered_green_sm.png';

//echo $shippedImageURL;
//echo "<br/>";
//echo $deliveredImageURL;
if(!empty($response)
&& is_object($response)
&& isset($response->trackRecord[0])
&& isset($response->trackRecord[0]->trackSummary)){
  if($response->header->status == 'SUCCESS'){  
    $error_code = $response->trackRecord[0]->trackSummary->errorCode;
    $error_message = $response->trackRecord[0]->trackSummary->errorMessage;
    $events = $response->trackRecord[0]->trackEvent;
    $carrier = $response->trackRecord[0]->trackSummary->carrier;
    $trackingNumber = $response->trackRecord[0]->trackSummary->trackingId;
    $isDelivered = ((int)$response->trackRecord[0]->trackSummary->delivered == 1)?true:false;
//    echo (int)$response->trackRecord[0]->trackSummary->delivered;
  //  echo "isDelivered2 ".(int)$isDelivered; 
   if(empty($error_code)){
      if(!empty($events)){ 
?>

<div class="content_area">
  <div class="shipping-summary-container">
    <div class="imageContent">
      <img src="<?php echo ($isDelivered)?$deliveredImageURL:$shippedImageURL ?>" />
      <!--img src="http://45.55.162.107/magento/media//Ubercx/Shippingtracking/delivered_green_sm.png" /-->
    </div>
    <div class="ubercxContent">
      <table class="tracking-summary-table">
	<tbody>
	 <tr>
	   <th class="label" width="20%" style="border-radius: 12px 0 0 0;"><b>Shipping Status:</b></th>
	   <td class="value last" colspan="3" style="border-radius:0 12px 0 0;"><?php echo ($isDelivered)?"DELIVERED":"SHIPPED" ?></td>
	 </tr>
	 <tr>
	  <th class="label" width="20%" style="border-radius: 0 0 0 12px;"><b>Tracking Number:</b></th>
	  <td class="value last"><b><?php echo $trackingNumber ?></b></td>
	  <th class="label" width="15%"><b>Carrier:</b></th>
	  <td class="value last" style="border-radius: 0 0 12px 0;"><?php echo $carrier ?></td>
	 </tr>
	</tbody>
      </table>
    </div>
  </div> 

  <div class="shipping_details-container">
    <table class="tracking_details" cellspacing="0" cellpadding="0">
      <tbody>
	<tr class="th_table_col_headers main">
				<th class="tracking_details_header" style="border-radius: 20px 0 0 0;"><strong>Date/Time </strong></th>
				<th class="tracking_details_header"><strong>Activity </strong></th>
				<th class="tracking_details_header" style="border-radius: 0 20px 0 0;"><strong>Location </strong></th>
	</tr>
	<?php $i = 1; foreach($events as $event){ 
				$date = date('d/m/Y', strtotime($event->date));
				$time = str_replace('Z', '', $event->time);
				$time = date('h:i a', strtotime($event->time));
				$location = $event->location;
				$message = $event->message;
				$tr_class = ($i % 2 == 0) ? 'even' : 'odd';
	?>
	<tr class="th_table_row main <?php echo $date . ' ' .$tr_class ; ?>">
          <td class="date_time"><?php echo $date.' '.$time; ?></td>
	  <td class="status"><?php echo $message; ?></td>
	  <td class="location"><?php echo $location; ?></td>
	</tr>
	<?php $i++; } ?>
      </tbody>
    </table>
  </div>
</div>
<?php }
  } else {
    echo '<h3>'.$error_message.'</h3>';
  }	
} else {
  '<h4>No tracking record found.</h4>';
}
}  else {
  '<h4>No tracking record found.</h4>';
}
?>
</div>
