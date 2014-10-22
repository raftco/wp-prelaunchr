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
		 * Initialise
		 */
		public function Prelaunchr() {

			/**
			 * Do Stuff
			 */

		}

	}

endif;

/**
 * Initialise Prelaunchr after the plugins are loaded.
 */
function init_prelaunchr() {
	$Prelaunchr = new Prelaunchr();
}

add_action( 'plugins_loaded', 'init_prelaunchr');