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
		wp_enqueue_script( 'collection-widget', Collections()->get_url( 'js/collection-widget.js' ), array( 'jquery' ) );

		$vars = array(
			'title_field_id'              => $this->get_field_id( 'title' ),
			'title_field_name'            => $this->get_field_name( 'title' ),
			'title'                       => ! empty( $instance['title'] ) ? $instance['title'] : '',
			'collection_items_field_name' => $this->get_field_name( 'collection_items' ),
			);
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

		$instance = array();
		$instance['title'] = ! empty( $new_instance['title'] ) ? sanitize_text_field( $new_instance['title'] ) : '';

		return $instance;
	}

}
