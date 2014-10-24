<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}
?>
<div class="prelaunchr">
	<div class="response"></div>
	<form class="pform" action="">
		<label for="email">Email Address: <span class="required">*</span></label>  
		<input type="email" id="email" name="email" value="" placeholder="your@email.com" required="required" />  
		<input type="submit" value="Submit" />
	</form><!-- end .pform -->
</div><!-- end .prelaunchr -->