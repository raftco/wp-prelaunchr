<?php
/**
 * Prelaunchr Admin
 */
class Prelaunchr_Admin {

	/**
	 * Add Prelaunchr menu
	 */
	public function add_menu_items(){

		/**
		 * Add top level menu item for Prelaunchr
		 */
		add_menu_page(
			'Prelaunchr',
			'Prelaunchr',
			'activate_plugins',
			'prelaunchr',
			array( $this, 'render_list_page' ),
			'dashicons-megaphone',
			30.9
		);

		/**
		 * Add submenu item for viewing entries
		 */
		add_submenu_page( 
			'prelaunchr', 
			'Prelaunchr', 
			'View Entries',
			'activate_plugins', 
			'prelaunchr', 
			array( $this, 'render_list_page' )
		);  

		/**
		 * Reorder menu items
		 */
		add_action( 'admin_menu', array( $this , 'reorder_menu_items' ), 999 );

		/**
		 * Intercept query and generate csv for downloading
		 */
		add_action( 'admin_init' , array( $this , 'download_csv' ) );

	}

	/**
	 * Reorder prelaunchr menu items so that viewing entries is the first option - rather than the prize groups cpt
	 */
	public function reorder_menu_items() {

		global $submenu, $menu;

		if ( $submenu['prelaunchr'] ) {

			$temp = array_reverse( $submenu['prelaunchr'] );

			$submenu['prelaunchr'] = $temp;

		}

	}

	/**
	 * Register the post type
	 */
	public function register_prize_group_cpt() {

		
		$labels = array(
			'name'               => __( 'Prize Groups', Prelaunchr()->get_plugin_name() ),
			'singular_name'      => __( 'Prize Group', Prelaunchr()->get_plugin_name() ),
			'all_items'          => __( 'Prize Groups', Prelaunchr()->get_plugin_name() ),
			'add_new_item'       => __( 'Add New Prize Group', Prelaunchr()->get_plugin_name() ),
			'edit_item'          => __( 'Edit Prize Group', Prelaunchr()->get_plugin_name() ),
			'new_item'           => __( 'New Prize Group', Prelaunchr()->get_plugin_name() ),
			'view_item'          => __( 'View Prize Group', Prelaunchr()->get_plugin_name() ),
			'search_items'       => __( 'Search Prize Groups', Prelaunchr()->get_plugin_name() ),
			'not_found'          => __( 'No prize groups found', Prelaunchr()->get_plugin_name() ),
			'not_found_in_trash' => __( 'No prize groups found in trash', Prelaunchr()->get_plugin_name() ),
			'menu_name'      	 => __( 'Prize Groups', Prelaunchr()->get_plugin_name() ),
		);

		$labels = apply_filters( 'prize_group_cpt_labels' , $labels );		
		
		$args = array(
			'description' => __( 'Based on the amount of referrals a user has brought to the site, they are put into a different "prize group". The groups, amounts, and prizes are completely up to you to set.', Prelaunchr()->get_plugin_name() ),
			'labels' => $labels,
			'public' => false,
			'menu_icon' => 'dashicons-groups',
			'show_ui' => true, 
			'query_var' => true,
			'has_archive' => false,
			'rewrite' => array( 'slug' => 'prize-group', 'with_front' => false ),
			'capability_type' => 'page',
			'hierarchical' => false,
			'taxonomies' => array(''),
			'show_in_menu' => 'prelaunchr',
			'show_in_nav_menus' => false,
			'supports' => array('title', 'editor', 'thumbnail', 'excerpt' )
		); 
		
		$args = apply_filters( 'prize_group_cpt_args' , $args );
		
		register_post_type( 'prize_group' , $args );
		
	}

	/**
	 * Filter the "post updated" messages
	 */
	public function prize_group_cpt_messages( $messages ) {
		global $post;

		$messages[ 'prize_group' ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Prize Group updated.', Prelaunchr()->get_plugin_name() ), esc_url( get_permalink($post->ID) ) ),
			2 => __('Custom field updated.', Prelaunchr()->get_plugin_name() ),
			3 => __('Custom field deleted.', Prelaunchr()->get_plugin_name() ),
			4 => __('Prize Group updated.', Prelaunchr()->get_plugin_name() ),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Prize Group restored to revision from %s', Prelaunchr()->get_plugin_name() ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Prize Group published.', Prelaunchr()->get_plugin_name() ), esc_url( get_permalink($post->ID) ) ),
			7 => __('Prize Group saved.', Prelaunchr()->get_plugin_name() ),
			8 => sprintf( __('Prize Group submitted.', Prelaunchr()->get_plugin_name() ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
			9 => sprintf( __('Prize Group scheduled for: <strong>%1$s</strong>.', Prelaunchr()->get_plugin_name() ),
			  // translators: Publish box date format, see http://php.net/date
			  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ),
			10 => sprintf( __('Prize Group draft updated.', Prelaunchr()->get_plugin_name() ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
		);

		return $messages;
	}

	/**
	 * Build and render the prelaunchr list page / menu item
	 */
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

	/**
	 * Add the prize group meta box
	 */
	public function add_meta_box() {
		add_meta_box( 
			'prize_group_meta', 
			__( 'Additional Information', Prelaunchr()->get_plugin_name()  ), 
			array(  $this , 'do_prize_group_meta_box' ), 
			'prize_group' , 
			'normal', 
			'high' );
	}

	/**
	 * Output the member details meta box HTML
	 *
	 * @param WP_Post $object Current post object
	 * @param array $box Metabox information
	 */
	public function do_prize_group_meta_box( $object, $box ) {
	
		wp_nonce_field( basename( __FILE__ ), 'prelaunchr-prize-group' );

		?>

		<p>
			<label for='prelaunchr-referrals-needed'>
				<?php _e( 'Referrals Needed:', Prelaunchr()->get_plugin_name() ); ?>
				<input type='number' id='prelaunchr-referrals-needed' name='prelaunchr-referrals-needed' value='<?php echo esc_attr( get_post_meta( $object->ID, '_prelaunchr-referrals-needed', true ) ); ?>' />
			</label>
		</p>

<?php
	}

	/**
	 * Save the member details metadata
	 *
	 * @wp-action save_post
	 * @param int $post_id The ID of the current post being saved.
	 */
	public static function save_meta( $post_id ) {

		/* Verify the nonce before proceeding. */
		if ( !isset( $_POST['prelaunchr-prize-group'] ) || !wp_verify_nonce( $_POST['prelaunchr-prize-group'], basename( __FILE__ ) ) )
			return $post_id;

		$meta = array(
			'prelaunchr-referrals-needed'
		);

		foreach ( $meta as $meta_key ) {
			$new_meta_value = $_POST[$meta_key];

			/* Get the meta value of the custom field key. */
			$meta_value = get_post_meta( $post_id, '_' . $meta_key , true );

			/* If there is no new meta value but an old value exists, delete it. */
			if ( '' == $new_meta_value && $meta_value )
				delete_post_meta( $post_id, '_' . $meta_key , $meta_value );

			/* If a new meta value was added and there was no previous value, add it. */
			elseif ( $new_meta_value && '' == $meta_value )
				add_post_meta( $post_id, '_' . $meta_key , $new_meta_value, true );

			/* If the new meta value does not match the old value, update it. */
			elseif ( $new_meta_value && $new_meta_value != $meta_value )
				update_post_meta( $post_id, '_' . $meta_key , $new_meta_value );
		}
	}


	/**
	 * Intercept query and generate csv for download
	 */
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