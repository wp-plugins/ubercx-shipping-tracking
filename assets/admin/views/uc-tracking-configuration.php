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

//Simon - Aug 2015
// Default the value for the text fields on the order page and email if they are blank
// Sept. Adding in user_key and enabled as well
//TODO we need to make all the static text be translatable

if(!isset($uc_options['order_text']) || $uc_options['order_text'] == ''){
	$uc_options['order_text'] = 'Your orders has been shipped by [carrier]. The tracking number is: [tracking_id] ';
}

if(!isset($uc_options['email_text']) || $uc_options['email_text'] == ''){
	$uc_options['email_text'] = 'Your orders has been shipped by [carrier]. The tracking number is: [tracking_id] ';
}

if(!isset($uc_options['user_key'])){
	$uc_options['user_key'] = '';
}

if(!isset($uc_options['enable'])){
	$uc_options['enable'] = 'Yes';
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
        
        <p><strong>User Key</strong></p>
        <p><i>Enter your UberCX User Key here. If you do not have one, <a href="https://developer.ubercx.io/signup?plan_ids[]=2357355826609">sign up for a FREE UberCX account here</a> no credit card required</i></p>
				<p><input  style="width:59%;" id="uc_settings[user_key]" name="uc_settings[user_key]" type="text" value="<?php echo $uc_options['user_key']; ?>" required/>
			<span><a target="_blank" href="https://developer.ubercx.io/signup?plan_ids[]=2357355826609">Get your User Key (open a FREE account)</a></span></p>
        <p><strong>Enabled: </strong></p>
        <p><select name="uc_settings[enable]" style="width:59%;">
          <option value="Yes" <?php if($uc_options['enable'] == 'Yes') echo 'selected="selected"'; ?>>Yes</option>
          <option value="No" <?php if($uc_options['enable'] == 'No') echo 'selected="selected"'; ?>>No</option>
             </select>
            
            </p>
        <p><strong>Order Page Text </strong><br><i>Enter the text that will appear on the order page, use the shortcodes [carrier] and [tracking_id] as placeholders
        </i></p>
		<p><input  style="width:59%;" id="uc_settings[order_text]" name="uc_settings[order_text]" type="text" value="<?php echo $uc_options['order_text']; ?>" required/>
        
        <p><strong>Email Text </strong><br><i>Enter the text that will appear in the confriamtion email, use the shortcodes [carrier] and [tracking_id] as placeholders
        </i></p>
		<p><input  style="width:59%;" id="uc_settings[email_text]" name="uc_settings[email_text]" type="text" value="<?php echo $uc_options['email_text']; ?>" required/>
		
        <!-- save the options -->
				<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e( 'Update' ); ?>" />
				</p>
			</form>
		</div><!--end fsb-wrap-->
	</div><!--end wrap-->
