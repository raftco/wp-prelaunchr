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
			 * Hooks
			 */
			add_action( 'plugins_loaded', array( $this, 'include_shortcode_functions' ) );

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
		public function include_shortcode_functions() {

			add_shortcode( 'prelaunchr', array( $this, 'prelaunchr_shortcode' ) );

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
