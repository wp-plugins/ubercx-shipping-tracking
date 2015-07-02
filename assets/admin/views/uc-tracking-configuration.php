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

<div class="wrap">
		<div id="fsb-wrap" class="fsb-help">
			<h2>UberCX Shipping Tracking Settings</h2>
			  <?php
			  if ( ! isset( $_REQUEST['updated'] ) )
				  $_REQUEST['updated'] = false;
			 ?>
			  <?php if ( false !== $_REQUEST['updated'] ) : ?>
			<div class="updated fade"><p><strong><?php _e( 'Options saved' ); ?></strong></p></div>
			<?php endif; ?>
			<form method="post" action="options.php">
				<?php settings_fields( 'uc_register_settings' ); ?>
        <strong>Track Shipment allows customer to track his order shipping using USPS,FedEx,UPS,DHL shipping methods.</strong>
        
        <p><strong>User Key</strong></p>
				<p><input  style="width:59%;" id="uc_settings[user_key]" name="uc_settings[user_key]" type="text" value="<?php echo $uc_options['user_key']; ?>" required/>
			<span><a target="_blank" href="http://ubercx.io/plans">Get your User Key (open FREE account)</a></span></p>
        <p><strong>Enabled: </strong></p>
        <p><select name="uc_settings[enable]" style="width:59%;">
          <option value="Yes" <?php if($uc_options['enable'] == 'Yes') echo 'selected="selected"'; ?>>Yes</option>
          <option value="No" <?php if($uc_options['enable'] == 'No') echo 'selected="selected"'; ?>>No</option>
             </select>
            
            </p>
				<!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Update' ); ?>" />
				</p>
			</form>
		</div><!--end fsb-wrap-->
	</div><!--end wrap-->
