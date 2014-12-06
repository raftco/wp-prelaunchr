<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<label for="name" class="ignore" style="display:none;"><?php _e('Name:', 'prelaunchr'); ?></label>
<input type="text" class="ignore" id="name" name="name" value="" style="display:none;"/>