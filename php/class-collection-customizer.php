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
		add_action( 'customize_save_widget_post_collection_widget', array( $this, 'action_customize_save' ) );
		add_action( 'customize_controls_enqueue_scripts', array( $this, 'action_customize_controls_enqueue_scripts' ) );
		add_action( 'collections_get_posts', array( $this, 'action_collections_get_posts' ) );

	}

	/**
	 * Register our settings and controls used in the Customizer
	 */
	public function action_customize_register() {
		global $wp_customize;

		$this->require_files();

		$wp_customize->add_section( 'collection_section___prototype__', array(
			'title'              => __( 'Collection:', 'collections' ),
			'priority'           => 40,
			) );

		$wp_customize->add_setting( 'collection_setting___prototype__', array(
			'type'               => 'collection',
			'sanitize'           => 'sanitize_text_field',
			) );

		$wp_customize->add_control( new Customize_Collection_Control( $wp_customize, 'collection_control___prototype__', array(
			'label'              => false,
			'section'            => 'collection_section___prototype__',
			'settings'           => 'collection_setting___prototype__',
			'type'               => 'text'
			) ) );

		// Dynamically register controls as settings
		if ( ! empty( $_POST['customized'] ) ) {
			$posted_items = json_decode( wp_unslash( $_POST['customized'] ), true );
			foreach( $posted_items as $key => $values ) {

				if ( false === strpos( $key, 'collection_setting_' ) || 'collection_setting___prototype__' === $key ) {
					continue;
				}


				$wp_customize->add_setting( $key, array(
					'type'               => $key,
					'sanitize'           => 'sanitize_text_field',
					) );

				add_action( 'customize_preview_' . $key, array( $this, 'action_customize_save_collection' ) );
				add_action( 'customize_update_' . $key, array( $this, 'action_customize_save_collection' ) );

			}

		}

	}

	/**
	 * Allow previewing and updating of a standalone collection
	 */
	public function action_customize_save_collection() {

		// current_filter() is for backwards compat even though these are actions
		if ( 0 === strpos( current_filter(), 'customize_update_' ) ) {
			$action = 'update';
			$collection_name = str_replace( 'customize_update_collection_setting_', '', current_filter() );
		} else if ( 0 === strpos( current_filter(), 'customize_preview_' ) ) {
			$action = 'preview';
			$collection_name = str_replace( 'customize_preview_collection_setting_', '', current_filter() );
		} else {
			return;
		}

		$collection = Post_Collection::get_by_name( $collection_name );
		if ( ! $collection ) {
			$collection = Post_Collection::create( $collection_name );
		}

		$posted_items = json_decode( wp_unslash( $_POST['customized'] ), true );
		$key = 'collection_setting_' . $collection_name;
		$values = ! empty( $posted_items[ $key ] ) ? array_map( 'intval', $posted_items[ $key ] ) : array();
		if ( 'update' == $action ) {
			$collection->set_customizer_item_ids( $values );
			$collection->set_published_item_ids( $values );
		} else if ( 'preview' == $action ) {
			$collection->set_customizer_item_ids( $values );
		}

	}

	/**
	 * Customizer Widget doesn't trigger update() on save, so we need to manually
	 * find our values and save them
	 */
	public function action_customize_save() {
		global $wp_customize;

		$posted_items = json_decode( wp_unslash( $_POST['customized'] ), true );
		foreach( $posted_items as $key => $values ) {

			if ( 0 === strpos( $key, 'widget_post_collection_widget' ) ) {

				$parts = explode( '[', rtrim( $key, ']') );
				$name = 'widget-post_collection_widget-' . (int) $parts[1];
				$decoded = maybe_unserialize( base64_decode( $values['encoded_serialized_instance'], true ) );

				$collection = Post_Collection::get_by_name( $name );
				if ( $collection && isset( $decoded['collection_items_stash'] ) ) {
					$collection->set_published_item_ids( array_map( 'absint', $decoded['collection_items_stash'] ) );
				}

			}

		}

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
		wp_enqueue_style( 'collection-customizer', Collections()->get_url( 'css/collection-customizer.css' ) );

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

		$collection = Post_Collection::get_by_name( $name );
		$data = array(
			'slug'         => sanitize_title( $name ),
			'items'        => array(),
			);
		if ( $collection ) {
			foreach( $collection->get_customizer_items() as $post ) {
				$data['items'][] = Collections()->get_post_for_json( $post );
			}
		}

		$this->rendered_collections[ $name ] = $data;

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