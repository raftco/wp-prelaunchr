<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}
 
/**
 * prelaunchr_before_form hook
 *
 * @hooked prelaunchr_wrapper_start - 10 (outputs prelaunchr wrapper div)
 * @hooked prelaunchr_response - 20 (outputs response div for form feedback/validation messages)
 * 
 */
do_action( 'prelaunchr_before_form'); ?>

	<form class="pform" action="" novalidate>
		<?php 
		/**
		 * prelaunchr_form hook
		 *
		 * @hooked prelaunchr_input_honeypot - 10 (outputs honeypot name field)
		 * @hooked prelaunchr_input_email - 20 (outputs email field)
		 * @hooked prelaunchr_button_submit - 30 (outputs submit button)
		 * 
		 */
		do_action( 'prelaunchr_form');
		?>
	</form><!-- end .pform -->

<?php 
/**
 * prelaunchr_after_form hook
 *
 * @hooked prelaunchr_wrapper_end - 10 (closes the prelaunchr wrapper div)
 * 
 */
do_action( 'prelaunchr_after_form');
?>