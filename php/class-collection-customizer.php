<?php

/**
 * Manages Customizer settings and controls
 */
class Collection_Customizer {

	private static $instance;

	public static function get_instance() {

		if ( ! isset( self::$instance ) ) {
			self::$instance = new Collection_Customizer;
			self::$instance->load();
		}
		return self::$instance;

	}

	/**
	 * Load the dependencies for the instance
	 */
	private function load() {

		$this->setup_actions();

	}

	/**
	 * Set up actions we're using in the Customizer
	 */
	private function setup_actions() {

		add_action( 'customize_register', array( $this, 'action_customize_register' ) );
		add_action( 'customize_preview_init', array( $this, 'action_customize_preview_init' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'action_customize_controls_enqueue_scripts' ) );

	}

	/**
	 * Register our settings and controls used in the Customizer
	 */
	public function action_customize_register() {
		
	}

	/**
	 * Actions called when the iframed preview is loaded
	 */
	public function action_customize_preview_init() {

	}

	/**
	 * Enqueue any scripts or stylesheets
	 */
	public function action_customize_controls_enqueue_scripts() {

	}


}