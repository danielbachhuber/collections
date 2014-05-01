<?php

/**
 * A collection of WordPress posts.
 */
class Collection {

	private $post;

	public static $post_type = 'collection';

	public function __construct( $post ) {

		if ( is_numeric( $post ) ) {
			$post = get_post( $post );
		}

		$this->post = $post;
	}

	/**
	 * Get a collection by its name
	 *
	 * @param string $name
	 * @return Collection|false
	 */
	public static function get_by_name( $name ) {
		global $wpdb;

		$post_id = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_name=%s AND post_type=%s", $name, self::$post_type ) );
		if ( ! $post_id ) {
			return false;
		}

		return new Collection( $post_id );
	}

	/**
	 * Create a new collection
	 *
	 * @param string $name
	 * @return Collection|WP_Error
	 */
	public static function create( $name ) {

		if ( self::get_by_name( $name ) ) {
			return new WP_Error( 'collection-create-error', __( 'Collection already exists by that name.', 'collections' ) );
		}

		$post_data = array(
			'post_title'    => $name,
			'post_name'     => $name,
			'post_type'     => self::$post_type,
			'post_status'   => 'publish',
			);
		$post_id = wp_insert_post( $post_data, true );
		if ( is_wp_error( $post_id ) ) {
			return $post_id;
		}

		return new Collection( $post_id );
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
	 * Get the IDs of posts for the staged version of this collection.
	 *
	 * @return array
	 */
	public function get_staged_item_ids() {

		if ( $item_ids = $this->get_meta( 'staged_item_ids' ) ) {
			return $item_ids;
		} else {
			return array();
		}

	}

	/**
	 * Set thes IDs of posts for the staged version of this collection.
	 *
	 * @param array
	 */
	public function set_staged_item_ids( $item_ids ) {
		$this->set_meta( 'staged_item_ids', $item_ids );
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
