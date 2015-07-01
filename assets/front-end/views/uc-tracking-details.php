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
<style type="text/css">
table {
	border: 1px solid #bebcb7;
	width: 100%;
}
table tr.last th, table tr.last td {
	border-bottom: 0 none !important;
}
table tbody th, table tbody td {
	border-bottom: 1px solid #d9dde3;
	border-right: 1px solid #d9dde3;
}
table td {
	padding: 3px 8px;
}
caption, th, td {
	font-weight: normal;
	text-align: left;
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
</style>
<div class="woocommerce_tracking">
<div class="page-title title-buttons">
	<button onclick="window.close(); window.opener.focus();" class="button"><span><span>Close Window</span></span></button>
	<h3>Tracking Information</h3>
</div>
<h4 class="sub-title">Order #<?php echo $_REQUEST['order']; ?></h4>
<div>Tracking Number: <?php echo $track_id; ?></div>
<?php
if(!empty($response)){
	if($response->header->status == 'SUCCESS'){  
	?>
<?php 
		foreach($response->trackRecord as $resp_array){
			$error_code = $resp_array->trackSummary->errorCode;
			$error_message = $resp_array->trackSummary->errorMessage;
			$events = $resp_array->trackEvent;
			$carrier = $resp_array->trackSummary->carrier;
		}
		?>
<div>Carrier: <?php echo $carrier;  ?></div>
<?php 
		
		if(empty($error_code)){
			if(!empty($events)){ ?>
<div class="content_area">
	<table class="tracking_details" cellspacing="0" cellpadding="0">
		<tbody>
			<tr class="th_table_col_headers main">
				<th class="date_time_area"><div class="sort_arrow detail_screen_ss sort_arrow_up"></div>
					<div class="date_time"><strong>Date/Time </strong></div></th>
				<th class="status"><strong>Activity </strong></th>
				<th class="location" style="display: table-cell;"><strong>Location </strong></th>
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
	<?php }
		} else {
			echo '<h3>'.$error_message.'</h3>';
		}
		
		?>
	<?php 
	} else {
		'<h4>No tracking record found.</h4>';
	}
}  else {
	'<h4>No tracking record found.</h4>';
}
?>
</div>
