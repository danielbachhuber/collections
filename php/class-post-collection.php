<?php

/**
 * A collection of WordPress posts.
 */
class Post_Collection extends Collection {

	/**
	 * Get the posts for the published version of this collection.
	 *
	 * @todo pre-fetch with WP_Query
	 *
	 * @return array
	 */
	public function get_published_items() {

		$posts = array();
		foreach( $this->get_published_item_ids() as $post_id ) {
			$posts[] = get_post( $post_id );
		}
		return $posts;
	}

	/**
	 * Get the posts for the staged version of this collection.
	 *
	 * @todo pre-fetch with WP_Query
	 *
	 * @return array
	 */
	public function get_staged_items() {

		$posts = array();
		foreach( $this->get_staged_item_ids() as $post_id ) {
			$posts[] = get_post( $post_id );
		}
		return $posts;


	}

}