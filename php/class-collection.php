<?php

/**
 * A collection of WordPress posts.
 */
class Collection {

	private $post;

	public function __construct( $post ) {

		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		$this->post = $post;
	}

	/**
	 * Get the ID for this collection
	 *
	 * @return int
	 */
	public function get_id() {
		return $this->post->ID;
	}

	/**
	 * Get the IDs of posts for the published version of this collection.
	 *
	 * @return array
	 */
	public function get_published_item_ids() {

		if ( $item_ids = $this->get_meta( 'published_item_ids' ) ) {
			return $item_ids;
		} else {
			return array();
		}

	}

	/**
	 * Set thes IDs of posts for the published version of this collection.
	 *
	 * @param array
	 */
	public function set_published_item_ids( $item_ids ) {
		$this->set_meta( 'published_item_ids', $item_ids );
	}

	/**
	 * Get a meta value for this collection
	 *
	 * @param string $key
	 * @return mixed
	 */
	protected function get_meta( $key ) {
		return get_post_meta( $this->get_id(), $key, true );
	}

	/**
	 * Set a meta value for this collection
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	protected function set_meta( $key, $value ) {
		update_post_meta( $this->get_id(), $key, $value );
	}

}
