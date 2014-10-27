<?php
/**
 * Prelaunchr Admin
 */
class Prelaunchr_Admin {

	/**
	 * Add Prelaunchr menu
	 */
	public function add_menu_items(){

		add_menu_page(
			'Prelaunchr',
			'Prelaunchr',
			'activate_plugins',
			'prelaunchr',
			array( $this, 'render_list_page' )
		);

		add_action( 'admin_init' , array( $this , 'download_csv' ) );

	} 

	public function render_list_page(){

		/**
		 * Create an instance or our table
		 */
		$Prelaunchr_List_Table = new Prelaunchr_List_Table();

		/**
		 * Fetch, prepare, sort, and filter our data...
		 */
		$Prelaunchr_List_Table->prepare_items();
		
		?>
		<div class="wrap">

			<h2>Prelaunchr</h2>
			
			<form id="prelaunchr-filter" method="get">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php $Prelaunchr_List_Table->display() ?>
			</form>

			<h2>CSV Export</h2>

			<form method="post" action="">
				<p>The Download button below generates a CSV file containing the email addresses, number of referrals, time of signup etc. which an be used to import into a mailing system of your choice.</p>
				<p class="submit">
					<input type="submit" name="prelaunchr_download_csv" id="prelaunchr_download_csv" class="button" value="Download CSV File &raquo;"  />
				</p>
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php wp_nonce_field(); ?>
			</form>

		</div>
		<?php
	}


	public function download_csv(){

		if ( is_admin() && ! empty( $_POST['prelaunchr_download_csv'] ) && check_admin_referer() && current_user_can('manage_options') ) {

			/**
			 * parsecsv-for-php library
			 */
			require_once PRELAUNCHR_PLUGIN_PATH . '/lib/parsecsv-for-php/parsecsv.lib.php';

			global $wpdb;

			/**
			 * Our table name
			 */
			$table_name = $wpdb->prefix . "prelaunchr";

			/**
			 * Prepare the query
			 */
			$query = "SELECT id,email,pid,rmail as referrer, count as referrals, time FROM $table_name A
						LEFT JOIN ( SELECT rid ,COUNT(*) as count FROM $table_name GROUP BY rid ORDER BY count DESC ) B 
						ON A.id = B.rid
						LEFT JOIN ( SELECT id as rid,email as rmail FROM $table_name ) C 
						ON A.rid = C.rid";

			$results = $wpdb->get_results($query, ARRAY_A);

			$csv = new parseCSV();

			$csv->output('data.csv', $results, array('id', 'email', 'pid', 'referrer', 'referrals', 'time'), ',');

			exit(); // all done

		}

	}

}