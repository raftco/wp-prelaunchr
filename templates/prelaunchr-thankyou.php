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
<h1>Thank YOU!</h1>

<p>You have <?php echo $referrals;?> referrals</p>

<p>Share with your friends using the following link:</p>

<p><a href="<?php the_permalink(); ?>?ref=<?php echo get_query_var('pid'); ?>" ><?php the_permalink(); ?>?ref=<?php echo get_query_var('pid'); ?></a><p>

<?php } ?>