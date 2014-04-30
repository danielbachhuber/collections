<?php
/*
Plugin Name: Collections
Version: 0.1-alpha
Description: Create and use collections of WordPress posts.
Author: Daniel Bachhuber
Author URI: http://danielbachhuber.com
Plugin URI: https://github.com/danielbachhuber/collections
Text Domain: collections
Domain Path: /languages
*/

class Collections {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Collections;
		}

		return self::$instance;
	}

}

/**
 * Load the plugin
 */
function Collections() {
	return Collections::get_instance();
}
add_action( 'plugins_loaded', 'Collections' );
