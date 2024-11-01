<?php
/*
	Plugin Name: WooCommerce Hide Password Protected Products
	Plugin URI: http://www.woothemes.com/
	Description: Hide password protected products from appearing in the loops.
	Version: 1.0
	Author: Barry Kooij - WooThemes
	Author URI: http://www.barrykooij.com/
	License: GPL v3

	This program is free software: you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation, either version 3 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

class WooCommerce_Hide_Password_Protected_Products {

	/**
	 * The Constructor
	 */
	public function __construct() {
		add_action( 'pre_get_posts', array( $this, 'alter_product_query' ), 11 );
	}

	/**
	 * Alter the WooCommerce product query
	 *
	 * @param $q
	 *
	 * @since  1.0.0
	 * @access public
	 *
	 */
	public function alter_product_query( $q ) {

		if ( ! is_admin() && ! is_single() && isset( $q->query ) && isset( $q->query['post_type'] ) && 'product' == $q->query['post_type'] ) {
			add_filter( 'posts_where', array( $this, 'exclude_protected_products' ) );
		}
	}

	/**
	 * Prevent password protected products appearing in the loops
	 *
	 * @param  string $where
	 *
	 * @return string
	 */
	public function exclude_protected_products( $where ) {
		global $wpdb;
		$where .= " AND {$wpdb->posts}.post_password = ''";

		return $where;
	}
}

// Bootstrap function
function __woocommerce_hide_password_protected_products_main() {
	new  WooCommerce_Hide_Password_Protected_Products();
}

// Load on plugin_loaded
add_action( 'plugins_loaded', '__woocommerce_hide_password_protected_products_main', 11 );