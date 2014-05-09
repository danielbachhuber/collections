<?php

/**
 * Get the posts in a Collection.
 *
 * @param string $name
 * @param array $args
 * @return array
 */
function collections_get_posts( $name, $args = array() ) {

	do_action( 'collections_get_posts', $name );

	$collection = Post_Collection::get_by_name( $name );
	if ( ! $collection ) {
		return array();
	}

	return $collection->get_published_items();
}