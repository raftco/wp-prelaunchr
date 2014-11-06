<?php
/*
Plugin Name: Prelaunchr for WordPress
Plugin URI: http://raft.co
Description: Useful plugin for pre launching sites. Based on https://github.com/harrystech/prelaunchr
Version: 1.0
Author: Raft Co
Author URI: http://raft.co
License: GPL2
Text Domain: prelaunchr
*/
/*
Copyright 2014 Raft Co (email : plugins@raft.co)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  1-1301  USA
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

/**
 * Require classes responsible for activation & deactivation tasks
 */
require_once plugin_dir_path( __FILE__ ) . 'includes/class-prelaunchr-activator.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/class-prelaunchr-deactivator.php';

/**
 * Hook activation & deactivate tasks
 */
register_activation_hook( __FILE__ , array( 'Prelaunchr_Activator', 'activate' ) );
register_deactivation_hook( __FILE__ , array( 'Prelaunchr_Deactivator', 'deactivate' ) );

if ( ! class_exists( 'Prelaunchr' ) ) :

	/**
	 * Plugin Main Class.
	 */
	class Prelaunchr {

		/**
		 * Plugin version
		 */
		public $version = '1.0.0';

		/**
		 * Plugin name / textdomain
		 */
		public $plugin_name = 'prelaunchr';

		/**
		 * The single instance of the main plugin class
		 */
		protected static $_instance = null;

		/**
		 * Switch for determining whether to enqueue prelaunchr css and js
		 */
		static $add_scripts = false;

		/**
		 * ID of the post displaying the [prelaunchr] shortcode
		 */
		static $shortcode_post_id = 0;

		/**
		 * Main plugin instance
		 */
		public static function instance() {

			if ( is_null( self::$_instance ) ) {
				self::$_instance = new self();
			}

			return self::$_instance;

		}

		/**
		 * Initialise
		 */
		public function __construct() {

			/**
			 * Define constants
			 */
			$this->define_constants();

			/**
			 * Include Prelaunchr classes
			 */
			$this->load_dependencies();

			/**
			 * Set Prelaunchr locale
			 */
			$this->set_locale();

			/**
			 * Setup Prelaunchr admin
			 */
			$this->setup_admin();

			/**
			 * Hooks on plugins_loaded
			 */
			add_action( 'plugins_loaded', array( $this, 'setup_hooks' ) );

		}

		/**
		 * Define Prelaunchr constants
		 */
		public function define_constants() {

			define( 'PRELAUNCHR_VERSION', $this->get_version() );
			define( 'PRELAUNCHR_PLUGIN_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
			define( 'PRELAUNCHR_PLUGIN_URL', untrailingslashit( plugin_dir_url( __FILE__ ) ) );
			define( 'PRELAUNCHR_TEMPLATE_PATH', PRELAUNCHR_PLUGIN_PATH . '/templates/' );
			define( 'PRELAUNCHR_PLUGIN_FILE', __FILE__ );
			define( 'PRELAUNCHR_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

		}

		/**
		 * Load includes for this plugin.
		 *
		 * Include the following files that make up the plugin:
		 *
		 * - Plugin_Name_Loader. Orchestrates the hooks of the plugin.
		 * - Plugin_Name_i18n. Defines internationalization functionality.
		 *
		 */
		public function load_dependencies() {

			/**
			 * The class responsible for defining internationalization functionality
			 * of the plugin.
			 */
			require_once PRELAUNCHR_PLUGIN_PATH . '/includes/class-prelaunchr-i18n.php';

			/**
			 * The class responsible for defining the admin list table functionality
			 */
			require_once PRELAUNCHR_PLUGIN_PATH . '/includes/class-prelaunchr-list-table.php';

			/**
			 * The class responsible for defining admin functionality
			 */
			require_once PRELAUNCHR_PLUGIN_PATH . '/includes/class-prelaunchr-admin.php';

		}

		/**
		 * Define the locale for this plugin for internationalization.
		 *
		 * Uses the Prelaunchr_i18n class in order to set the domain and to register the hook
		 * with WordPress.
		 */
		public function set_locale() {

			$plugin_i18n = new Prelaunchr_i18n();

			$plugin_i18n->set_domain( $this->get_plugin_name() );

			add_action( 'plugins_loaded', array( $plugin_i18n, 'load_plugin_textdomain' ) );

		}

		/**
		 * Setup admin
		 */
		public function setup_admin() {

			$prelaunchr_admin = new Prelaunchr_Admin();

			/**
			 * Add Prelaunchr menu item
			 */
			add_action( 'admin_menu', array( $prelaunchr_admin, 'add_menu_items' ) );

			/**
			 * Create our reward group cpt
			 */
			add_action( 'init', array( $prelaunchr_admin, 'register_reward_cpt' ) );

			/**
			 * Update admin messages/text for the reward cpt
			 */
			add_filter( 'post_updated_messages', array( $prelaunchr_admin, 'reward_cpt_messages' ) );

			/**
			 * Add custom meta box for the reward CPT
			 */
			add_action( 'add_meta_boxes', array( $prelaunchr_admin, 'add_meta_box' ) );
			
			/**
			 * Save reward cpt meta box data
			 */
			add_action( 'save_post', array( $prelaunchr_admin, 'save_meta' ), 10, 1 );

		}


		/**
		 * Include shortcode functions
		 */
		public function setup_hooks() {

			/**
			 * Add the [prelaunchr] shortcode
			 */
			add_shortcode( 'prelaunchr', array( $this, 'prelaunchr_shortcode' ) );

			/**
			 * Checks if a queried post contains the [prelaunchr] shortcode
			 * needs to happen after the "template_redirect"
			 */
			add_action( 'the_posts', array( $this, 'check_for_shortcode' ) );

			/**
			 * Add our CSS and JS only if the [prelaunchr] shortcode is present
			 */
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );


			/**
			 * Handle Form Submission
			 */
			add_action( 'wp_ajax_nopriv_prelaunchr-submit', array( $this, 'record_submission' ) );
			add_action( 'wp_ajax_prelaunchr-submit', array( $this, 'record_submission' ) );

			/**
			 * URL / Rewrite rules
			 */
			add_action( 'generate_rewrite_rules', array( $this, 'add_rewrite_rules' ) );
			add_filter( 'query_vars', array( $this, 'add_query_vars' ) );
			add_action( 'admin_init', array( $this, 'flush_rewrite_rules' ) );

			/**
			 * Add a meta robot noindex tag to our referrer urls above
			 */
			add_action( 'pre_get_posts', array( $this, 'add_noindex' ) );

		}

		/**
		 * Prelaunchr Shortcode
		 */
		public function prelaunchr_shortcode() {

			return $this->display( true );

		}

		/**
		 * Check theme for templates overwise use default plugin templates
		 */
		public function prelaunchr_get_template_part( $slug , $name = null ) {

			if ( empty( $slug ) ) {
				return;
			}

			$name = (string) $name;

			if ( '' !== $name ) {

				$template = "{$slug}-{$name}.php";

			} else {

				$template = "{$slug}.php";

			}

			/**
			 * locate_template() returns path to file
			 *
			 * if either the child theme or the parent theme have overridden the template
			 * otherwise we load the template from the plugin 'templates' sub-directory
			 */
			if ( $overridden_template = locate_template( $template ) ) {

				load_template( $overridden_template );

			} else {

				load_template( PRELAUNCHR_TEMPLATE_PATH . $template );

			}

		}

		/**
		 * Checks if any post about to be displayed contains the [prelaunchr] shortcode.
		 *
		 * We need to set @see self::$add_scripts here rather than in the shortcode so we can conditionally
		 * add scripts
		 *
		 */
		public function check_for_shortcode( $posts ) {

			if ( empty( $posts ) )
				return $posts;

			foreach ( $posts as $post ) {

				if ( false !== stripos( $post->post_content, '[prelaunchr' ) ) {
					self::$add_scripts = true;
					self::$shortcode_post_id = $post->ID;
					break;
				}
			}

			return $posts;
		}

		/**
		 * Enqueue Prelaunchr front end scripts with the shortcode is present.
		 */
		public function enqueue_scripts() {

			if ( self::$add_scripts ) {

				global $post;

				$suffix      = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

				$assets_path = str_replace( array( 'http:', 'https:' ), '', PRELAUNCHR_PLUGIN_URL . '/assets' );

				wp_enqueue_script("jquery");

				wp_enqueue_style( 'prelaunchr', $assets_path . '/css/prelaunchr.css', array(), $this->get_version() );

				wp_enqueue_script( 'uuid', $assets_path . '/js/uuid' . $suffix . '.js', array(), '1.4.1', true );

				wp_enqueue_script( 'jquery-cookie', $assets_path . '/js/jquery.cookie' . $suffix . '.js', array('jquery'), '1.4.1', true );

				wp_enqueue_script( 'share', $assets_path . '/js/share' . $suffix . '.js', array(), '0.5.0', true );

				wp_enqueue_script( 'prelaunchr', $assets_path . '/js/prelaunchr' . $suffix . '.js', array('jquery','uuid','jquery-cookie','share'), $this->get_version(), true );

				wp_localize_script( 'prelaunchr', 'PrelaunchrSubmit', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'return' => get_page_uri( $post->ID )
					)
				);

			}

		}

		/**
		 * Validate an email http://stackoverflow.com/questions/5855811/how-to-validate-an-email-in-php
		 *
		 * Require php 5.2.X and above
		 */
		public function isValidEmail($email) {

			//return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);

			return filter_var($email, FILTER_VALIDATE_EMAIL);

		}

		public function record_submission() {

			$data = array();
			$format = array();

			/**
			 * Email
			 */
			$email = $this->isValidEmail( mysql_real_escape_string( stripslashes( $_POST['email'] ) ) );

			if (  ! $email ) {
				wp_send_json_error( __( 'Invalid Email', Prelaunchr()->get_plugin_name() ) );
			}

			if ( $this->akismet_available() ) {

				$request =  'blog='. urlencode( wp_unslash( (string) site_url() ) ) .
							'&user_ip='. urlencode( wp_unslash( (string) $this->get_ip_address() ) ) .
							'&user_agent='. urlencode( wp_unslash( (string) $this->get_user_agent() ) ) . 
							'&referrer='. urlencode( wp_unslash( (string) $this->get_referer() ) ) .
							'&comment_type='. urlencode( 'email' ) . 
							'&comment_author_email='. urlencode( wp_unslash( (string) $email ) );

				if ( $this->akismet_check( $request ) ) {
					wp_send_json_error( __( 'Spam detected - If this is an error please contact us directly', Prelaunchr()->get_plugin_name() ) );
				}

			}


			if ( $this->email_exists( $email ) ) {
				wp_send_json_error( sprintf( __( 'Thanks we have already recorded your interest. Check your referrals <a href="%s">here</a>.', Prelaunchr()->get_plugin_name() ), esc_url( $this->get_pid_from_email( $email ) ) ) );
			}

			$data['email'] = $email;
			$format[] = '%s';

			/**
			 * Time
			 */
			$data['time'] = time();
			$format[] = '%d';

			/**
			 * PID - Inidividual Prelaunchr ID for each email
			 */
			if ( isset( $_POST['pid'] ) ) {
				$data['pid'] = mysql_real_escape_string( stripslashes( $_POST['pid'] ) );
			} else {
				$data['pid'] = 0;
			}
			$format[] = '%s';

			/**
			 * RID - Referrer ID
			 */
			if ( isset( $_POST['rid'] ) ) {
				$data['rid'] = $this->get_referrer_id( mysql_real_escape_string( stripslashes( $_POST['rid'] ) ) );
			} else {
				$data['rid'] = 0;
			}
			$format[] = '%s';

			/**
			 * Insert submission into database.
			 */
			global $wpdb;

			$wpdb->insert( $wpdb->prefix . "prelaunchr" , $data, $format );

			wp_send_json_success( $data );

		}

		/**
		 * Creates the rewrite rules for our pids
		 *
		 * Concept based on http://stackoverflow.com/questions/13140182/wordpress-wp-rewrite-rules
		 */
		public function add_rewrite_rules( $wp_rewrite ) {

			if ( $id = $this->get_post_with_shortcode() ) {

				$path = get_page_uri( $id );

				$new_rules = array (
					'(' . $path . ')/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})/page/?([0-9]{1,})/?$' => 'index.php?pagename=' . $wp_rewrite->preg_index(1).'&pid=' . $wp_rewrite->preg_index(2) . '&page=' . $wp_rewrite->preg_index(3) ,
					'(' . $path . ')/([0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12})/?$' => 'index.php?pagename=' . $wp_rewrite->preg_index(1) . '&pid=' . $wp_rewrite->preg_index(2)
				);

				// Always add your rules to the top, to make sure your rules have priority
				$wp_rewrite->rules = $new_rules + $wp_rewrite->rules;

			}

		}


		/**
		 * Adds the filter query parameters to the WP_Query object.
		 */
		public function add_query_vars( $query_vars ) {

			$query_vars[] = 'pid';

			return $query_vars;
		}

		/**
		 * Definitely not ideal (i know i know) - but we need to flush rewrite rules on something other than 
		 * plugin activation so we can check if the shortcode has been used
		 */
		public function flush_rewrite_rules() {

			flush_rewrite_rules();

		}

		/**
		 * Find the post with the our [prelaunchr] shortcode
		 */
		public function get_post_with_shortcode() {

			$args = array( 'posts_per_page' => -1, 'post_type'=> 'any' );	
			$posts = get_posts( $args );
			$pattern = get_shortcode_regex();

			foreach ($posts as $post){
				if (   preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches )
					&& array_key_exists( 2, $matches )
					&& in_array( 'prelaunchr', $matches[2] ) )
				{
					return $post->ID;
				}    
			}

			return false;

		}

		/**
		 * The name of the plugin used to uniquely identify it within the context of
		 * WordPress and to define internationalization functionality.
		 */
		public function get_plugin_name() {

			return $this->plugin_name;

		}

		/**
		 * Retrieve the version number of the plugin.
		 */
		public function get_version() {

			return $this->version;

		}

		/**
		 * Get the a pid from an email address
		 */
		public function get_pid_from_email( $email ) {

			global $wpdb;

			$table_name = $wpdb->prefix . "prelaunchr";

			$pid = $wpdb->get_var( $wpdb->prepare( "SELECT pid FROM $table_name WHERE email = '%s'", $email ) );

			if ( ! empty( $pid ) ) {
				return $pid;
			} else {
				return false;
			}

		}

		/**
		 * Get the referrers ID
		 */
		public function get_referrer_id( $pid ) {

			global $wpdb;

			$table_name = $wpdb->prefix . "prelaunchr";

			$rid = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name WHERE pid = '%s'", $pid ) );

			if ( ! empty( $rid ) ) {
				return $rid;
			} else {
				return false;
			}

		}

		/**
		 * Get number of referrals from an email
		 */
		public function get_referral_count_from_email( $email ) {

			global $wpdb;

			$table_name = $wpdb->prefix . "prelaunchr";

			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(rid) FROM $table_name WHERE rid = ( SELECT id FROM $table_name WHERE email = '%s' LIMIT 1)", $email ) );

			if ( ! empty( $count ) ) {
				return $count;
			} else {
				return false;
			}

		}

		/**
		 * Get number of referrals from an email
		 */
		public function get_referral_count_from_pid( $pid ) {

			global $wpdb;

			$table_name = $wpdb->prefix . "prelaunchr";

			$count = $wpdb->get_var( $wpdb->prepare( "SELECT COUNT(rid) FROM $table_name WHERE rid = ( SELECT id FROM $table_name WHERE pid = '%s' LIMIT 1)", $pid ) );

			if ( ! empty( $count ) ) {
				return $count;
			} else {
				return false;
			}

		}

		/**
		 * Get the highest number of referrals needed for a prize
		 */
		public function get_max_reward_referral_count() {

			$args = array(
				'post_type' 		=> 'reward',
				'meta_key' 			=> '_prelaunchr-referrals-needed',
				'order'				=> 'DESC',
				'orderby'			=> 'meta_value_num',
				'posts_per_page'	=> '1'
				);

			$reward = get_posts( $args );

			return get_post_meta( $reward[0]->ID, '_prelaunchr-referrals-needed', true );

		}

		/**
		 * Get the highest number of referrals needed for a prize
		 */
		public function get_rewards( $order = 'ASC' ) {

			$args = array(
				'post_type' 		=> 'reward',
				'meta_key' 			=> '_prelaunchr-referrals-needed',
				'order'				=> $order,
				'orderby'			=> 'meta_value_num',
				'posts_per_page'	=> '500',
				'no_found_rows' 	=> true,
				);

			$rewards = get_posts( $args );

			return $rewards;

		}

		/**
		 * Check if an email address already exists in the database
		 * @param  var $email email address to be checked
		 * @return bool        returns true if email already exists
		 */
		public function email_exists( $email ) {

			global $wpdb;

			$table_name = $wpdb->prefix . "prelaunchr";

			$test = $wpdb->get_var( $wpdb->prepare( "SELECT id FROM $table_name WHERE email = '%s'", $email ) );

			if ( $test ) {
				return true;
			} else {
				return false;
			}

		}

		/**
		 * Return the appropriate prelaunchr template
		 */
		public function display( $return = false ) {

			$pid = get_query_var('pid');

			if ( $return ) {
				ob_start();
			}

			if ( empty ( $pid ) ) {

				$this->prelaunchr_get_template_part( 'prelaunchr', 'form' );

			} else {

				$this->prelaunchr_get_template_part( 'prelaunchr', 'thankyou' );

			}

			if ( $return ) {
				return ob_get_clean();
			}

		}

		/**
		 * Check if akismet is available to use
		 */
		public function akismet_available() {

			if ( is_callable( array( 'Akismet', 'get_api_key' ) ) ) { // Akismet v3.0+
				return (bool) Akismet::get_api_key();
			}

			if ( function_exists( 'akismet_get_key' ) ) {
				return (bool) akismet_get_key();
			}

			return false;

		}

		/**
		 * Check submission against Akismet
		 *
		 * Passes back true (it's spam) or false (it's ham)
		 */
		public function akismet_check( $query_string ) {

			global $akismet_api_host, $akismet_api_port;

			$spam = false;

			if ( is_callable( array( 'Akismet', 'http_post' ) ) ) { // Akismet v3.0+
				$response = Akismet::http_post( $query_string, 'comment-check' );
			} else {
				$response = akismet_http_post( $query_string, $akismet_api_host, '/1.1/comment-check', $akismet_api_port );
			}	

			if ( 'true' == $response[1] ) {
				$spam = true;
			}

			return $spam;

		}

		/**
		* Use core wp function to add noindex to page head
		*/
		public function add_noindex() {

			$pid = get_query_var('pid');

			if ( empty ( $pid ) ) {
				return;
			}

			add_action( 'wp_head', 'wp_no_robots' );

		}

		/**
		 * Get User IP (the one that is reported anyway)
		 */
		public function get_ip_address() {
			return isset( $_SERVER['REMOTE_ADDR'] ) ? $_SERVER['REMOTE_ADDR'] : null;
		}

		/**
		 * Get User Browser Agent (the one that is reported anyway)
		 */
		public function get_user_agent() {
			return isset( $_SERVER['HTTP_USER_AGENT'] ) ? $_SERVER['HTTP_USER_AGENT'] : null;
		}

		/**
		 * Get User Referrer
		 */
		public function get_referer() {
			return isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : null;
		}

	}

endif;

function Prelaunchr() {
	return Prelaunchr::instance();
}

$Prelaunchr = Prelaunchr();