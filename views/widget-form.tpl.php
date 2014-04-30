<div class="collection-widget">

	<p><label for="<?php echo esc_attr( $title_field_id ); ?>"><?php _e( 'Title', 'collections' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $title_field_id ); ?>" name="<?php echo esc_attr( $title_field_name ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

	<p><a href="#" class="add-post button"><?php _e( 'Add Post', 'collections' ); ?></a></p>

	<ul class="collection-items">

	</ul>

</div>