<?php
/**
 * File Security Check
 */
if ( ! empty( $_SERVER['SCRIPT_FILENAME'] ) && basename( __FILE__ ) == basename( $_SERVER['SCRIPT_FILENAME'] ) ) {
	die ( 'You do not have sufficient permissions to access this page!' );
}

$pid = Prelaunchr()->valid_uuid( $_GET['pid'] );
if ( $pid ) {
	$url = get_permalink() . ( parse_url( untrailingslashit( get_permalink() ) , PHP_URL_QUERY ) ? '&' : '?' ) . 'ref=' . $pid;
?>
<div id="referral-link" ><?php echo $url ?></div>
<?php } ?>