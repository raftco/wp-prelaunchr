<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}
?>

<?php 
/**
 * prelaunchr_before_thankyou hook
 *
 * @hooked prelaunchr_wrapper_start - 10 (outputs prelaunchr wrapper div)
 * 
 */
do_action( 'prelaunchr_before_thankyou'); ?>

	<div class="pthanks">

		<?php 
		/**
		 * prelaunchr_thankyou hook
		 *
		 * @hooked prelaunchr_thankyou_intro - 10 (outputs .prelaunchr wrapper)
		 * @hooked prelaunchr_referral_link - 20 (outputs the users referral link)
		 * @hooked prelaunchr_social_share - 30 (outputs the social share buttons)
		 * @hooked prelaunchr_referral_stats - 40 (outputs the users referral stats)
		 * 
		 */
		do_action( 'prelaunchr_thankyou');
		?>

	</div>

<?php 
/**
 * prelaunchr_after_thankyou hook
 *
 * @hooked prelaunchr_wrapper_end - 10 (closes the prelaunchr wrapper div)
 * 
 */
do_action( 'prelaunchr_after_thankyou');
?>