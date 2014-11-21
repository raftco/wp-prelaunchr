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
			__( 'Prelaunchr', Prelaunchr()->get_plugin_name()  ),
			__( 'Prelaunchr', Prelaunchr()->get_plugin_name()  ),
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
			__( 'Prelaunchr', Prelaunchr()->get_plugin_name()  ), 
			__( 'Entries', Prelaunchr()->get_plugin_name()  ),
			'activate_plugins', 
			'prelaunchr', 
			array( $this, 'render_list_page' )
		);  

		/**
		 * Add submenu item for exporting entries
		 */
		add_submenu_page( 
			'prelaunchr', 
			__( 'Prelaunchr', Prelaunchr()->get_plugin_name()  ), 
			__( 'Export', Prelaunchr()->get_plugin_name()  ),
			'activate_plugins', 
			'prelaunchr-export', 
			array( $this, 'render_export_page' )
		);

		/**
		 * Add submenu item for settings
		 */
		add_submenu_page( 
			'prelaunchr', 
			__( 'Prelaunchr', Prelaunchr()->get_plugin_name()  ), 
			__( 'Settings', Prelaunchr()->get_plugin_name()  ),
			'activate_plugins', 
			'prelaunchr-settings', 
			array( $this, 'render_settings_page' )
		);

		/**
		 * Reorder menu items
		 */
		add_action( 'admin_menu', array( $this , 'reorder_menu_items' ), 999 );

		/**
		 * Intercept query and generate csv for downloading
		 */
		add_action( 'admin_init' , array( $this , 'download_csv' ) );

		/**
		 * Register settings
		 */
		add_action( 'admin_init', array( $this , 'setup_prelaunchr_settings' ) );

	}

	/**
	 * Reorder prelaunchr menu items so that viewing entries is the first option - rather than the reward cpt
	 */
	public function reorder_menu_items() {

		global $submenu;

		if ( $submenu['prelaunchr'] ) {

			/**
			 * get rewards and entries menu items
			 */
			$rewards = $submenu['prelaunchr'][0];
			$entries = $submenu['prelaunchr'][1];

			/**
			 * now swap them around
			 */
			$submenu['prelaunchr'][0] = $entries;
			$submenu['prelaunchr'][1] = $rewards;

		}

	}

	/**
	 * Register the post type
	 */
	public function register_reward_cpt() {

		
		$labels = array(
			'name'               => __( 'Rewards', Prelaunchr()->get_plugin_name() ),
			'singular_name'      => __( 'Reward', Prelaunchr()->get_plugin_name() ),
			'all_items'          => __( 'Rewards', Prelaunchr()->get_plugin_name() ),
			'add_new_item'       => __( 'Add New Reward', Prelaunchr()->get_plugin_name() ),
			'edit_item'          => __( 'Edit Reward', Prelaunchr()->get_plugin_name() ),
			'new_item'           => __( 'New Reward', Prelaunchr()->get_plugin_name() ),
			'view_item'          => __( 'View Reward', Prelaunchr()->get_plugin_name() ),
			'search_items'       => __( 'Search Rewards', Prelaunchr()->get_plugin_name() ),
			'not_found'          => __( 'No rewards found', Prelaunchr()->get_plugin_name() ),
			'not_found_in_trash' => __( 'No rewards found in trash', Prelaunchr()->get_plugin_name() ),
			'menu_name'      	 => __( 'Rewards', Prelaunchr()->get_plugin_name() ),
		);

		$labels = apply_filters( 'reward_cpt_labels' , $labels );		
		
		$args = array(
			'description' => __( 'Based on the amount of referrals a user has brought to the site, they are put into a different "reward group". The groups, amounts, and prizes are completely up to you to set.', Prelaunchr()->get_plugin_name() ),
			'labels' => $labels,
			'public' => false,
			'menu_icon' => 'dashicons-groups',
			'show_ui' => true, 
			'query_var' => true,
			'has_archive' => false,
			'rewrite' => array( 'slug' => 'reward', 'with_front' => false ),
			'capability_type' => 'page',
			'hierarchical' => false,
			'taxonomies' => array(''),
			'show_in_menu' => 'prelaunchr',
			'show_in_nav_menus' => false,
			'supports' => array('title', 'editor', 'thumbnail', 'excerpt' )
		); 
		
		$args = apply_filters( 'reward_cpt_args' , $args );
		
		register_post_type( 'reward' , $args );
		
	}

	/**
	 * Filter the "post updated" messages
	 */
	public function reward_cpt_messages( $messages ) {
		global $post;

		$messages[ 'reward' ] = array(
			0 => '', // Unused. Messages start at index 1.
			1 => sprintf( __('Reward updated.', Prelaunchr()->get_plugin_name() ), esc_url( get_permalink($post->ID) ) ),
			2 => __('Custom field updated.', Prelaunchr()->get_plugin_name() ),
			3 => __('Custom field deleted.', Prelaunchr()->get_plugin_name() ),
			4 => __('Reward updated.', Prelaunchr()->get_plugin_name() ),
			/* translators: %s: date and time of the revision */
			5 => isset($_GET['revision']) ? sprintf( __('Reward restored to revision from %s', Prelaunchr()->get_plugin_name() ), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
			6 => sprintf( __('Reward published.', Prelaunchr()->get_plugin_name() ), esc_url( get_permalink($post->ID) ) ),
			7 => __('Reward saved.', Prelaunchr()->get_plugin_name() ),
			8 => sprintf( __('Reward submitted.', Prelaunchr()->get_plugin_name() ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
			9 => sprintf( __('Reward scheduled for: <strong>%1$s</strong>.', Prelaunchr()->get_plugin_name() ),
			  // translators: Publish box date format, see http://php.net/date
			  date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post->ID) ) ),
			10 => sprintf( __('Reward draft updated.', Prelaunchr()->get_plugin_name() ), esc_url( add_query_arg( 'preview', 'true', get_permalink($post->ID) ) ) ),
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

		$message = '';

		if ('delete' === $Prelaunchr_List_Table->current_action() ) {
			$message = '<div class="updated below-h2" id="message"><p>' . sprintf(__('Items deleted: %d', 'custom_table_example'), count( $_GET['did'] ) ) . '</p></div>';
		}
		
		?>
		<div class="wrap">

			<h2><?php _e( 'Prelaunchr Entries', Prelaunchr()->get_plugin_name() ); ?></h2>

			<?php echo $message; ?>
			
			<form id="prelaunchr-filter" method="get">
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php $Prelaunchr_List_Table->display() ?>
			</form>

		</div>
		<?php
	}

	/**
	 * Build and render the prelaunchr export page / menu item
	 */
	public function render_export_page(){
		?>
		<div class="wrap">

			<h2><?php _e( 'Export to CSV', Prelaunchr()->get_plugin_name() ); ?></h2>

			<p><?php _e( 'The Download button below generates a CSV file containing the email addresses, number of referrals, time of signup etc. which an be used to import into a mailing system of your choice.', Prelaunchr()->get_plugin_name() ); ?></p>

			<form method="post" action="">
				<p class="submit">
					<input type="submit" name="prelaunchr_download_csv" id="prelaunchr_download_csv" class="button" value="<?php _e( 'Download CSV File &raquo;', Prelaunchr()->get_plugin_name() ); ?>"  />
				</p>
				<input type="hidden" name="page" value="<?php echo $_REQUEST['page'] ?>" />
				<?php wp_nonce_field( basename( __FILE__ ), 'prelaunchr-download' ); ?>
			</form>

		</div>
		<?php
	}

	/**
	 * Build and render the prelaunchr settings page / menu item
	 */
	public function render_settings_page() {
		?>
		<div class="wrap">

			<h2><?php _e( 'Settings', Prelaunchr()->get_plugin_name() ); ?></h2>

			<form method="POST" action="options.php">
				<?php settings_fields( 'prelaunchr-settings' ); ?>
				<?php do_settings_sections( 'prelaunchr-settings' );?>
				<?php submit_button(); ?>
			</form>

		</div>
		<?php
	}

	/**
	 * Setup prelaunchr default settings and setting sections
	 */
	public function setup_prelaunchr_settings() {

		/**
		 * Register Settings
		 */
		register_setting( 'prelaunchr-settings', 'my-setting' );

		/**
		 * Add sections and fields
		 */
		add_settings_section(
			'section-one', 
			__( 'Section One', Prelaunchr()->get_plugin_name() ), 
			array( $this , 'section_one_callback' ), 
			'prelaunchr-settings'
		);

		add_settings_field(
			'field-one',
			__( 'Field One', Prelaunchr()->get_plugin_name() ),
			array( $this , 'field_one_callback'),
			'prelaunchr-settings',
			'section-one'
		);
	
	}

	/**
	 * Describe the section
	 */
	public function section_one_callback() {
		_e( 'Section description', Prelaunchr()->get_plugin_name() );
	}

	/**
	 * Output the setting field
	 */
	public function field_one_callback() {
		$setting = esc_attr( get_option( 'my-setting' ) );
		echo "<input type='text' name='my-setting' value='$setting' />";
	}

	/**
	 * Add the reward meta box
	 */
	public function add_meta_box() {
		add_meta_box( 
			'reward_meta', 
			__( 'Additional Information', Prelaunchr()->get_plugin_name() ), 
			array(  $this , 'do_reward_meta_box' ), 
			'reward' , 
			'normal', 
			'high' );
	}

	/**
	 * Output the reward meta box HTML
	 *
	 * @param WP_Post $object Current post object
	 * @param array $box Metabox information
	 */
	public function do_reward_meta_box( $object, $box ) {
	
		wp_nonce_field( basename( __FILE__ ), 'prelaunchr-reward' );

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
		if ( !isset( $_POST['prelaunchr-reward'] ) || !wp_verify_nonce( $_POST['prelaunchr-reward'], basename( __FILE__ ) ) )
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

		if ( is_admin() && ! empty( $_POST['prelaunchr_download_csv'] ) && check_admin_referer( basename( __FILE__ ), 'prelaunchr-download' ) && current_user_can('manage_options') ) {

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