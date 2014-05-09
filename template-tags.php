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

	if ( Collections()->is_customizer_preview() ) {

		// Reset collections on initial page load
		if ( ! empty( $_POST['customize_messenger_channel'] )
			&& 'preview-0' === $_POST['customize_messenger_channel']
			&& $collection->get_published_item_ids() != $collection->get_customizer_item_ids() ) {
			$collection->set_customizer_item_ids( $collection->get_published_item_ids() );
		}

		return $collection->get_customizer_items();
	} else {
		return $collection->get_published_items();
	}
}

/**
 * Get the IDs for posts in a collections
 *
 * @param string $name
 * @param array $args
 * @return array
 */
function collections_get_post_ids( $name, $args = array() ) {

	do_action( 'collections_get_posts', $name );

	$collection = Post_Collection::get_by_name( $name );
	if ( ! $collection ) {
		return array();
	}

	if ( Collections()->is_customizer_preview() ) {

		// Reset collections on initial page load
		if ( ! empty( $_POST['customize_messenger_channel'] )
			&& 'preview-0' === $_POST['customize_messenger_channel']
			&& $collection->get_published_item_ids() != $collection->get_customizer_item_ids() ) {
			$collection->set_customizer_item_ids( $collection->get_published_item_ids() );
		}

		return $collection->get_customizer_item_ids();
	} else {
		return $collection->get_published_item_ids();
	}
}
