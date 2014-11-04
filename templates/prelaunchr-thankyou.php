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
<h2><?php _e( "Thank you for signing up!", Prelaunchr()->get_plugin_name() ); ?></h2>

<p><?php _e( "Don't leave your friends out", Prelaunchr()->get_plugin_name() ); ?></p>

<p> <?php _e( "Invite your friends &amp; earn rewards", Prelaunchr()->get_plugin_name() ); ?></p>

<p><?php _e( "Share your unique link via email, Facebook or Twitter &amp earn rewards for each friend who signs up.", Prelaunchr()->get_plugin_name() ); ?></p>

<div id="referral-link" ><?php the_permalink(); ?>?ref=<?php echo get_query_var('pid'); ?></div>

<div class="share"></div>

<?php Prelaunchr()->prelaunchr_get_template_part( 'prelaunchr', 'referrals' ); ?>

<?php } ?>