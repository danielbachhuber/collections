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

		add_action( 'init', array( $this, 'action_init_register_post_type' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'action_enqueue_scripts_register' ) );
		add_action( 'wp_ajax_collections_add_post_search', array( $this, 'handle_ajax_add_post_search' ) );
		add_action( 'widgets_init', array( $this, 'action_widgets_init' ) );

	}

	/**
	 * Register the post type used to store collections behind the scenes
	 */
	public function action_init_register_post_type() {
			register_post_type( 'collection', array(
				'hierarchical'      => false,
				'public'            => false,
				'show_in_nav_menus' => false,
				'show_ui'           => false,
				'supports'          => array(),
				'has_archive'       => false,
				'query_var'         => false,
				'rewrite'           => false,
				'labels'            => array(
					'name'                => __( 'Collections', 'collections' ),
					'singular_name'       => __( 'Collection', 'collections' ),
					'all_items'           => __( 'Collections', 'collections' ),
					'new_item'            => __( 'New Collection', 'collections' ),
					'add_new'             => __( 'Add New', 'collections' ),
					'add_new_item'        => __( 'Add New Collection', 'collections' ),
					'edit_item'           => __( 'Edit Collection', 'collections' ),
					'view_item'           => __( 'View Collection', 'collections' ),
					'search_items'        => __( 'Search Collections', 'collections' ),
					'not_found'           => __( 'No Collections found', 'collections' ),
					'not_found_in_trash'  => __( 'No Collections found in trash', 'collections' ),
					'parent_item_colon'   => __( 'Parent Collection', 'collections' ),
					'menu_name'           => __( 'Collections', 'collections' ),
				),
			) );
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
	 * Handle a request to search for posts to add
	 */
	public function handle_ajax_add_post_search() {

		// @todo allow collection by collection permissions
		if ( ! current_user_can( 'edit_theme_options' )
			|| ! wp_verify_nonce( $_GET['nonce'], 'collections-add-post-search' ) ) {
			$this->send_json_error( __( "You probably shouldn't do this.", 'collections' ) );
		}

		$query_args = array(
			'post_type'    => 'post',
			'post_status'  => 'publish',
			);
		if ( ! empty( $_GET['s'] ) ) {
			$query_args['s'] = sanitize_text_field( $_GET['s'] );
		}

		$query = new WP_Query( $query_args );
		$posts = array();
		foreach( $query->posts as $post ) {
			$posts[ $post->ID ] = $this->get_post_for_json( $post );
		}

		$this->send_json_success( '', array( 'posts' => $posts ) );

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
	 * Get a post as its JSON representation
	 *
	 * @param mixed $post
	 */
	public function get_post_for_json( $post ) {

		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		return array(
			'ID'             => $post->ID,
			'post_title'     => $post->post_title,
			'permalink'      => get_permalink( $post->ID ),
			'edit_link'      => get_edit_post_link( $post->ID, 'json' ),
			'user_can_edit'  => current_user_can( 'edit_post', $post->ID ),
			);

	}

	/**
	 * Send a JSON success message
	 *
	 * @param string $message
	 * @param mixed $data
	 */
	private function send_json_success( $message = '', $data = array() ) {
		header( 'Content-Type: application/json' );
		echo json_encode( array( 'status' => 'success', 'message' => $message, 'data' => $data ) );
		exit;
	}

	/**
	 * Send a JSON error message
	 *
	 * @param string $message
	 */
	private function send_json_error( $message = '' ) {
		header( 'Content-Type: application/json' );
		echo json_encode( array( 'status' => 'error', 'message' => $message ) );
		exit;
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
