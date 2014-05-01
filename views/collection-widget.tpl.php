<?php

	$title = ( ! empty( $instance['title'] ) ) ? $instance['title'] : '';

	$query = new WP_Query( array(
		'post__in'            => $collection_item_ids,
		'orderby'             => 'post__in',
		'no_found_rows'       => true,
	) );

	if ( $query->have_posts()) :
?>
		<?php echo $args['before_widget']; ?>
		<?php if ( $title ) {
			echo $args['before_title'] . $title . $args['after_title'];
		} ?>
		<ul>
		<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<li>
				<a href="<?php the_permalink(); ?>"><?php get_the_title() ? the_title() : the_ID(); ?></a>
			</li>
		<?php endwhile; ?>
		</ul>
		<?php echo $args['after_widget']; ?>
<?php
		wp_reset_postdata();

	endif;