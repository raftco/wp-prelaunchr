<?php
/**
 * Uninstall Prelaunchr
 *
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

/**
 * Delete prelaunchr table with entries
 */
$wpdb->query( "DROP TABLE IF EXISTS " . $wpdb->prefix . "prelaunchr" );

/**
 * Delete rewards CPT posts and post meta
 */
$wpdb->query( "DELETE FROM {$wpdb->posts} WHERE post_type IN ( 'reward' );" );
$wpdb->query( "DELETE FROM {$wpdb->postmeta} meta LEFT JOIN {$wpdb->posts} posts ON posts.ID = meta.post_id WHERE wp.ID IS NULL;" );