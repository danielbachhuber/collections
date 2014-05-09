<?php

/**
 * Manages Customizer settings and controls
 */
class Collection_Customizer {

	private static $instance;

	private $rendered_collections = array();

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
		add_action( 'collections_get_posts', array( $this, 'action_collections_get_posts' ) );

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

		add_action( 'wp_enqueue_scripts', array( $this, 'action_wp_enqueue_scripts' ) );
		add_action( 'wp_footer', array( $this, 'action_wp_footer' ) );
	}

	/**
	 * Enqueue any scripts or stylesheets to be used in the Customizer
	 */
	public function action_customize_controls_enqueue_scripts() {

		wp_enqueue_script( 'collection-customizer', Collections()->get_url( 'js/collection-customizer.js' ), array( 'jquery', 'customize-controls' ) );

	}

	/**
	 * Track which Collections are rendered on a page
	 *
	 * @param string $name
	 */
	public function action_collections_get_posts( $name ) {

		if ( in_array( $name, $this->rendered_collections ) ) {
			return;
		}

		$this->rendered_collections[] = $name;

	}

	/**
	 * Enqueue scripts and styles to be used in the Customizer Preview
	 */
	public function action_wp_enqueue_scripts() {

		wp_enqueue_script( 'collection-customizer-preview', Collections()->get_url( 'js/collection-customizer-preview.js' ), array( 'jquery', 'customize-preview' ) );

	}


	/**
	 * Tell the parent about which Collections were used on this page
	 */
	public function action_wp_footer() {

		$settings = array(
			'renderedCollections'   => $this->rendered_collections,
		);

		?>
		<script type="text/javascript">
			var _wpCollectionsCustomizerPreviewSettings = <?php echo json_encode( $settings ); ?>;
		</script>
		<?php

	}


}