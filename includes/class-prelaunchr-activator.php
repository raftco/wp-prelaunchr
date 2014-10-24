<?php
/**
 * Fired during plugin activation
 */
class Prelaunchr_Activator {

	/**
	 * Do stuff on Activation
	 */
	public function activate() {

		flush_rewrite_rules();

		Prelaunchr_Activator::add_db_table();

	}

	public function add_db_table() {

		global $wpdb;

		$table_name = $wpdb->prefix . "prelaunchr";
		  
		$sql = "CREATE TABLE $table_name (
			id INT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
			time INT NOT NULL,
			email VARCHAR(254) NOT NULL,
			pid VARCHAR(36) NOT NULL,
			rid INT(20) UNSIGNED,
			KEY pid (pid),
			KEY time (time),
			PRIMARY KEY id (id),
			UNIQUE (email,pid)
		);";

		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

		if ( function_exists('dbDelta') ) {
			dbDelta($sql);
		}

		add_option( 'prelaunchr_db_version', Prelaunchr()->get_version() );

	}

}