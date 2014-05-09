<?php

/**
 * Assign a list of posts to a widget slot in a sidebar
 */
class Post_Collection_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'post_collection_widget',
			__( 'Post Collection', 'collections' ),
			array(
				'description' => __( 'A collection of posts.', 'collections' ),
			)
		);

		add_action( 'customize_save_widget_post_collection_widget', array( $this, 'action_customize_save' ) );
	}

	/**
	 * Get this widget's corresponding collection name
	 *
	 * @return string
	 */
	public function get_collection_name() {
		return sanitize_title( 'widget-' . $this->id );
	}

	/**
	 * Determine if we're in the Customizer; if true, then the object cache gets
	 * suspended and widgets should check this to decide whether they should
	 * store anything persistently to the object cache, to transients, or
	 * anywhere else.
	 *
	 * Adds support for pre-WordPress 3.9
	 *
	 * @return bool True if Customizer is on, false if not.
	 */
	public function is_customizer() {
		global $wp_customize;
		return ( isset( $wp_customize ) && $wp_customize->is_preview() ) ;
	}

	/**
	 * Whether or not the Customizer is performing a widget preview update on us
	 *
	 * @return bool
	 */
	public function is_customizer_widget_update() {
		return ( isset( $_POST['wp_customize'], $_POST['action'] ) && 'on' === $_POST['wp_customize'] && 'update-widget' === $_POST['action'] );
	}

	/**
	 * Output a collection of posts
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

		if ( $collection = Post_Collection::get_by_name( $this->get_collection_name() ) ) {
			if ( $this->is_customizer() ) {
				$collection_item_ids = $collection->get_customizer_item_ids();
			} else {
				$collection_item_ids = $collection->get_published_item_ids();
			}
		} else {
			$collection_item_ids = array();
		}

		$vars = array(
			'args'                => $args,
			'instance'            => $instance,
			'collection_item_ids' => $collection_item_ids,
			);
		echo Collections()->get_view( 'collection-widget', $vars );

	}

	/**
	 * Output the collection management view
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {
		global $wp_customize;

		Collections()->enqueue_assets();

		$vars = array(
			'title_field_id'              => $this->get_field_id( 'title' ),
			'title_field_name'            => $this->get_field_name( 'title' ),
			'title'                       => ! empty( $instance['title'] ) ? $instance['title'] : '',
			'collection_items_field_id'   => $this->get_field_id( 'collection_items' ),
			'collection_items_field_name' => $this->get_field_name( 'collection_items' ),
			// See wp_list_widget_controls_dynamic_sidebar() for details on this mess
			'widget_id'                   => $this->id,
			'widget_instance_id'          => md5( rand( 0, 10000 ) . time() ),
			);

		$vars[ 'collection_items' ] = $vars[ 'collection_item_ids' ] = array();

		if ( $collection = Post_Collection::get_by_name( $this->get_collection_name() ) ) {

			// Show Customizer items in the form when doing an update
			if ( $this->is_customizer_widget_update() ) {
				$vars['collection_item_ids'] = $collection->get_customizer_item_ids();
			} else {
				$vars['collection_item_ids'] = $collection->get_published_item_ids();
			}

			// Reset the Customizer if needed, but only on initial page load
			if ( $this->is_customizer() && empty( $_POST['wp_customize'] ) ) {
				$collection->set_customizer_item_ids( $vars['collection_item_ids'] );
			}

			foreach( $vars['collection_item_ids'] as $post_id ) {
				$vars[ 'collection_items' ][] = Collections()->get_post_for_json( $post_id );
			}
		}

		echo Collections()->get_view( 'widget-form', $vars );

	}

	/**
	 * Update the posts associated with widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {

		$collection = Post_Collection::get_by_name( $this->get_collection_name() );
		if ( ! $collection ) {
			$collection = Post_Collection::create( $this->get_collection_name() );
			if ( is_wp_error( $collection ) ) {
				return $old_instance;
			}
		}

		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		if ( ! empty( $new_instance['collection_items'] ) ) {
			$instance_items = array_map( 'absint', explode( ',', $new_instance['collection_items'] ) );
		} else {
			$instance_items = array();
		}

		// Triggers cache bust in Customizer and also used for save
		$instance['collection_items_stash'] = $instance_items;

		// Doing a customizer preview should only modify Customizer collection items.
		if ( $this->is_customizer_widget_update() ) {
			$collection->set_customizer_item_ids( $instance_items );
		} else {
			$collection->set_published_item_ids( $instance_items );
		}

		return $instance;
	}

	/**
	 * Customizer Widget doesn't trigger update() on save, so we need to manually
	 * find our values and save them
	 */
	public function action_customize_save() {
		global $wp_customize;

		$posted_items = json_decode( wp_unslash( $_POST['customized'] ), true );
		foreach( $posted_items as $key => $values ) {

			if ( false === strpos( $key, 'widget_post_collection_widget' ) ) {
				continue;
			}

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
