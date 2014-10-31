<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}

$pid = get_query_var('pid');

if ( ! empty ( $pid ) ) {

	$referrals = Prelaunchr()->get_referral_count_from_pid($pid);

	$referrals = ( empty( $referrals ) ) ? 0 : $referrals; 

?>
<h2>Thank you for signing up!</h2>

<p>Don't leave your friends out</p>

<p>Invite your friends &amp; earn rewards</p>

<p>Share your unique link via email, Facebook or Twitter &amp earn rewards for each friend who signs up.</p>

<div id="referral-link" ><?php the_permalink(); ?>?ref=<?php echo get_query_var('pid'); ?></div>

<div class="share"></div>

<?php Prelaunchr()->prelaunchr_get_template_part( 'prelaunchr', 'referrals' ); ?>

<?php } ?>