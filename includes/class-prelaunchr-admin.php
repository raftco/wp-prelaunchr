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

		add_submenu_page(
			'prelaunchr', 
        	'Download CSV',
        	'Download CSV',
        	'download_csv',
        	'download'
        );

		add_action('admin_init',array($this,'download_csv'));

	} 

	public function render_list_page(){

		if ( $_GET['download'] ) {
			var_dump('poo');
		}

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
			
			<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">

				<p>Download emails as: <a href="?download=csv" target="_blank" style="text-decoration:none;">CSV</a></p>

			</div>
			
			<!-- Forms are NOT created automatically, so you need to wrap the table in one to use features like bulk actions -->
			<form id="movies-filter" method="get">
				<!-- For plugins, we also need to ensure that the form posts back to our current page -->
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<!-- Now we can render the completed list table -->
				<?php $Prelaunchr_List_Table->display() ?>
			</form>
			
		</div>
		<?php
	}


	public function download_csv(){

			/**
			 * parsecsv-for-php library
			 */
			require_once PRELAUNCHR_PLUGIN_PATH . '/lib/parsecsv-for-php/parsecsv.lib.php';


global $pagenow;

      if ($pagenow=='admin.php' &&  
          isset($_GET['page'])  && 
          $_GET['page']=='download') {


		global $wpdb;

		/**
		 * Our table name
		 */
		$table_name = $wpdb->prefix . "prelaunchr";

   		/**
   		 * Prepare the query
   		 */
		//$query = "SELECT id,email,pid,rid,COALESCE(count, 0) as referrals FROM $table_name A LEFT JOIN ( SELECT rid as countid,COUNT(*) as count FROM $table_name GROUP BY rid ORDER BY count DESC ) B ON A.id = B.countid";
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