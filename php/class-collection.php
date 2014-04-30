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

}
