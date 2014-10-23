<?php
/*
Plugin Name: Prelaunchr for WordPress
Plugin URI: http://raft.co
Description: Useful plugin for pre launching sites. Based on https://github.com/harrystech/prelaunchr
Version: 1.0
Author: Raft Co
Author URI: http://raft.co
License: GPL2
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
		 * Plugin version
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
			 * Hooks on plugins_loaded
			 */
			add_action( 'plugins_loaded', array( $this, 'setup_hooks' ) );

			/**
			 * Activation tasks
			 */
			register_activation_hook( PRELAUNCHR_PLUGIN_FILE, array( $this, 'activate' ) );

			/**
			 * Deactivation tasks
			 */
			register_deactivation_hook( PRELAUNCHR_PLUGIN_FILE, array( $this, 'deactivate' ) );

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

		}

		/**
		 * Prelaunchr Shortcode
		 */
		public function prelaunchr_shortcode() {

			ob_start();

			$this->prelaunchr_get_template_part( 'prelaunchr', 'form' );

			return ob_get_clean();

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
		 * Checks if any post about to be displayed contains the one page checkout shortcode.
		 *
		 * We need to set @see self::$add_scripts here rather than in the shortcode so we can conditionally
		 * add the locale to the WooCommerce core script done in @see self::localize_script() hooked to
		 * 'woocommerce_params' which is run on 'wp_enqueue_script' (i.e. before the shortcode is evaluated).
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

				wp_enqueue_script( 'prelaunchr', $assets_path . '/js/prelaunchr' . $suffix . '.js', array('jquery','uuid','jquery-cookie'), $this->get_version(), true );

				wp_localize_script( 'prelaunchr', 'PrelaunchrSubmit', array( 
					'ajaxurl' => admin_url( 'admin-ajax.php' ),
					'return' => get_page_uri( $post->ID )
					)
				);

			}

		}

		public function record_submission() {

			$data = array();
			$format = array();

			/**
			 * Time
			 */
			$data['time'] = time();
			$format[] = '%d';

			/**
			 * UUID
			 */
			if ( isset( $_REQUEST['pid'] ) ) {
				$data['pid'] = mysql_real_escape_string( stripslashes( $_REQUEST['pid'] ) );
			} else {
				$data['pid'] = 0;
			}
			$format[] = '%s';

			/**
			 * Email
			 */
			$data['email'] = mysql_real_escape_string( stripslashes( $_REQUEST['email'] ) );
			$format[] = '%s';

			/**
			 * Insert submission into database.
			 */
			global $wpdb;

			//$wpdb->insert( $wpdb->prefix . "prelaunchr" , $data, $format );

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
		 * Do stuff on activation
		 */
		public function activate() {

			flush_rewrite_rules();

		}

		/**
		 * Do stuff on deactivation
		 */
		public function deactivate() {

			flush_rewrite_rules();

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

	}

endif;

Prelaunchr::instance();
