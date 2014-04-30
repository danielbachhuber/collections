<div class="collection-widget">

	<p><label for="<?php echo esc_attr( $title_field_id ); ?>"><?php _e( 'Title', 'collections' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $title_field_id ); ?>" name="<?php echo esc_attr( $title_field_name ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

	<p><a href="#" class="add-post button"><?php _e( 'Add Posts', 'collections' ); ?></a></p>

	<script type="text/html" id="tmpl-collection-item">
		<li class="collection-item">
			<input type="hidden" name="<?php echo esc_attr( $collection_items_field_name ); ?>[]" value="{{ data.post.ID }}" />
			<h5>{{ data.post.post_title }}</h5>
			<div class="collection-item-actions">
				<# if ( data.post.user_can_edit ) { #>
					<a href="{{ data.post.edit_link }}"><?php _e( 'Edit', 'collections' ); ?></a> | 
				<# } #>
				<a href="#" class="collection-item-remove-action delete"><?php _e( 'Remove', 'collections' ); ?></a>
			</div>
		</li>
	</script>

	<ul class="collection-items">

	</ul>

</div>