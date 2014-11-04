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
	<form class="pform" action="" novalidate>
		<?php do_action( 'prelaunchr_form'); ?>
		<label for="name" class="ignore"><?php _e('Name:', 'prelaunchr'); ?></label>
		<input type="text" class="ignore" id="name" name="name" value="" />
		<label for="email"><?php _e('Email Address:', 'prelaunchr'); ?> <span class="required">*</span></label>
		<input type="email" id="email" name="email" value="" placeholder="<?php _e('your@email.com', 'prelaunchr'); ?>" required="required" />
		<input type="submit" value="<?php _e('Submit', 'prelaunchr'); ?>" />
	</form><!-- end .pform -->
</div><!-- end .prelaunchr -->