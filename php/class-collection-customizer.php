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
	 * Require any necessary files
	 */
	private function require_files() {

		require_once dirname( __FILE__ ) . '/class-customize-collection-control.php';

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
		global $wp_customize;

		$this->require_files();

		$wp_customize->add_section( 'collection_section__prototype__', array(
			'title'              => __( 'Collection:', 'collections' ),
			'priority'           => 40,
			) );

		$wp_customize->add_setting( 'collection_setting__prototype__', array(
			'type'               => 'collection',
			'sanitize'           => 'sanitize_text_field',
			) );

		$wp_customize->add_control( new Customize_Collection_Control( $wp_customize, 'collection_control__prototype__', array(
			'label'              => false,
			'section'            => 'collection_section__prototype__',
			'settings'           => 'collection_setting__prototype__',
			'type'               => 'text'
			) ) );

	}

	/**
	 * Actions called when the iframed preview is loaded
	 */
	public function action_customize_preview_init() {

		add_action( 'wp_footer', array( $this, 'action_wp_footer' ) );
	}

	/**
	 * Enqueue any scripts or stylesheets
	 */
	public function action_customize_controls_enqueue_scripts() {

	}

	/**
	 * Tell the parent about which Collections were used on this page
	 */
	public function action_wp_footer() {

		
	}


}