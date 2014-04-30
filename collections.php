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

	private $did_register_assets = false;

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

		add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue_scripts_register' ) );
		add_action( 'widgets_init', array( $this, 'action_widgets_init' ) );

	}

	/**
	 * Register our scripts and styles
	 */
	public function action_enqueue_scripts_register() {

		wp_register_script( 'collections', $this->get_url( 'js/collections.js' ), array( 'jquery' ) );
		wp_register_style( 'collections', $this->get_url( 'css/collections.css' ) );

		$this->did_register_assets = true;

	}

	/**
	 * Register the widgets
	 */
	public function action_widgets_init() {

		register_widget( 'Collection_Widget' );

	}

	/**
	 * Enqueue all of the necessary assets for the plugin
	 */
	public function enqueue_assets() {

		if ( ! $this->did_register_assets ) {
			$this->action_enqueue_scripts_register();
		}

		wp_enqueue_script( 'collections' );
		wp_enqueue_style( 'collections' );

		if ( is_admin() ) {
			add_action( 'admin_footer', array( $this, 'render_add_post_modal' ) );
		}

	}

	/**
	 * Render the HTML associated with the add post modal
	 */
	public function render_add_post_modal() {

		echo $this->get_view( 'add-post-modal' );

	}

	/**
	 * Get the URL for a plugin asset
	 *
	 * @param string $path
	 * @return string
	 */
	public function get_url( $path = '' ) {
		return plugins_url( $path, __FILE__ );
	}


	/**
	 * Get the rendering of a given view
	 *
	 * @param string $view
	 * @param array $vars
	 * @return string
	 */
	public function get_view( $view, $vars = array() ) {

		$file_path = dirname( __FILE__ ) . '/views/' . $view . '.tpl.php';
		if ( ! file_exists( $file_path ) ) {
			return '';
		}

		ob_start();
		extract( $vars );
		include $file_path;
		return ob_get_clean();
	}

}

/**
 * Load the plugin
 */
function Collections() {
	return Collections::get_instance();
}
add_action( 'plugins_loaded', 'Collections' );
