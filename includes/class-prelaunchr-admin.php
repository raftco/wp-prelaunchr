<?php
/**
 * Prelaunchr Admin
 */
class Prelaunchr_Admin {

	/**
	 * Add Prelaunchr menu
	 */
	public function add_menu_items(){

		add_menu_page( 'Prelaunchr', 'Prelaunchr', 'activate_plugins', 'prelaunchr', array( $this, 'render_list_page' ) );

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
			
			<div style="background:#ECECEC;border:1px solid #CCC;padding:0 10px;margin-top:5px;border-radius:5px;-moz-border-radius:5px;-webkit-border-radius:5px;">

				<p>Download emails as: <a href="#" target="_blank" style="text-decoration:none;">CSV</a>.</p>

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

}