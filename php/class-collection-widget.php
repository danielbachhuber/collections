<?php

/**
 * Assign a list of posts to a widget slot in a sidebar
 */
class Collection_Widget extends WP_Widget {

	public function __construct() {

		parent::__construct(
			'collection_widget',
			__( 'Collection', 'collections' ),
			array(
				'description' => __( 'A collection of posts.', 'collections' ),
			)
		);

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
	 * Output a collection of posts
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {

	}

	/**
	 * Output the collection management view
	 *
	 * @param array $instance
	 */
	public function form( $instance ) {

		Collections()->enqueue_assets();

		wp_enqueue_style( 'collection-widget', Collections()->get_url( 'css/collection-widget.css' ) );
		wp_enqueue_script( 'collection-widget', Collections()->get_url( 'js/collection-widget.js' ), array( 'jquery', 'jquery-ui-sortable', 'collections' ) );

		$vars = array(
			'title_field_id'              => $this->get_field_id( 'title' ),
			'title_field_name'            => $this->get_field_name( 'title' ),
			'title'                       => ! empty( $instance['title'] ) ? $instance['title'] : '',
			'collection_items_field_name' => $this->get_field_name( 'collection_items' ),
			// See wp_list_widget_controls_dynamic_sidebar() for details on this mess
			'widget_instance_id'          => md5( rand( 0, 10000 ) . time() ),
			);

		$vars[ 'collection_items' ] = array();
		if ( $collection = Collection::get_by_name( $this->get_collection_name() ) ) {
			foreach( $collection->get_published_item_ids() as $post_id ) {
				$vars[ 'collection_items' ][] = Collections()->get_post_for_json( $post_id );
			}
		}

		echo Collections()->get_view( 'widget-form', $vars );

		// Only add the collection item script template once
		if ( ! has_action( 'admin_footer', array( $this, 'render_widget_collection_item' ) ) ) {
			add_action( 'admin_footer', array( $this, 'render_widget_collection_item' ) );
		}

	}

	/**
	 * Update the posts associated with widget
	 *
	 * @param array $new_instance
	 * @param array $old_instance
	 * @return array $instance
	 */
	public function update( $new_instance, $old_instance ) {

		$collection = Collection::get_by_name( $this->get_collection_name() );
		if ( ! $collection ) {
			$collection = Collection::create( $this->get_collection_name() );
		}

		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';
		if ( ! is_wp_error( $collection ) ) {
			if ( is_array( $new_instance['collection_items'] ) ) {
				$collection->set_published_item_ids( array_map( 'absint', $new_instance['collection_items'] ) );
			} else {
				$collection->set_published_item_ids( array() );
			}
		}

		return $instance;
	}

	/**
	 * Render script templates only once
	 */
	public function render_widget_collection_item() {
		echo Collections()->get_view( 'widget-collection-item' );
	}

}
