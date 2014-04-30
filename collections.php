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
			self::$instance->load();
		}

		return self::$instance;
	}

	/**
	 * Load plugin components
	 */
	private function load() {

		$this->require_files();
		$this->setup_actions();

	}

	/**
	 * Require plugin files
	 */
	private function require_files() {

		require_once dirname( __FILE__ ) . '/php/class-collection.php';
		require_once dirname( __FILE__ ) . '/php/class-collection-widget.php';

	}

	/**
	 * Set up plugin actions
	 */
	private function setup_actions() {

		add_action( 'widgets_init', array( $this, 'action_widgets_init' ) );

	}

	/**
	 * Register the widgets
	 */
	public function action_widgets_init() {

		register_widget( 'Collection_Widget' );

	}

}

/**
 * Load the plugin
 */
function Collections() {
	return Collections::get_instance();
}
add_action( 'plugins_loaded', 'Collections' );
