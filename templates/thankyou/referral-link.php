<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}

$pid = Prelaunchr()->valid_uuid( $_GET['pid'] );
if ( $pid ) {
?>
<div id="referral-link" ><?php the_permalink(); ?>?ref=<?php echo $pid; ?></div>
<?php } ?>