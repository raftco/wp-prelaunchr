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

	$max = Prelaunchr()->get_max_reward_referral_count();

	if ( ! empty( $max ) ) {

		if ( $referrals > $max ) {
			$referrals = $max;
		}

		$percentage = ( $referrals / $max ) * 100;

	}

	$rewards = Prelaunchr()->get_rewards();

	if ( ! empty( $rewards ) ) {

	?>

<h2><?php _e( "Here's how it works", Prelaunchr()->get_plugin_name() ); ?></h2>
<p><?php printf( __( 'You currently have %d Referrals!', Prelaunchr()->get_plugin_name() ), $referrals ); ?></p>
<div class="referrals">
	<ul class="labels">
		<li class="referrals"><?php _e( "Referrals", Prelaunchr()->get_plugin_name() ); ?></li>
		<li class="rewards"><?php _e( "Rewards", Prelaunchr()->get_plugin_name() ); ?></li>
	</ul>
	<ul class="referral-progress">
		<li class="reward">
			<div class="referrals">0</div>
		</li>
	<?php foreach ( $rewards as $post ) : setup_postdata( $post ); ?>
	<?php
	$reward_friends = get_post_meta( $post->ID, '_prelaunchr-referrals-needed', true );
	$reward_percentage = ( $reward_friends / $max ) * 100;
	?>
		<li class="reward" style="left:<?php echo round($reward_percentage, 2) . '%'; ?>" >
			<div class="referrals"><?php echo $reward_friends; ?></div>
			<div class="rewards"><?php the_title(); ?></div>
		</li>
	<?php endforeach; ?>
	</ul>
	<?php wp_reset_postdata(); ?>
	<div class="progress" data-prelaunchr-progress="<?php echo round($percentage, 2); ?>">
	  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="<?php echo round($percentage, 2); ?>" aria-valuemin="0" aria-valuemax="100" >
	    <?php echo $referrals; ?>
	  </div>
	</div>
</div>
<?php } ?>
<?php } ?>