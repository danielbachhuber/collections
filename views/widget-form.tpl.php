<div class="collection-widget">

	<p><label for="<?php echo esc_attr( $title_field_id ); ?>"><?php _e( 'Title', 'collections' ); ?>:</label>
		<input class="widefat" id="<?php echo esc_attr( $title_field_id ); ?>" name="<?php echo esc_attr( $title_field_name ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

	<p><a href="#" class="add-post button"><?php _e( 'Add Posts', 'collections' ); ?></a></p>

	<input type="hidden" id="<?php echo esc_attr( $collection_items_field_id ); ?>" name="<?php echo esc_attr( $collection_items_field_name ); ?>" class="collection-widget-item-ids" value="<?php echo esc_attr( implode( ',', $collection_item_ids ) ); ?>" />

	<ul class="collection-items">

	</ul>

	<?php if ( false === stripos( $widget_id, '__i__' ) ) : ?>
		<span id="collection-widget-instance-<?php echo $widget_instance_id; ?>"></span>
	<script>

		jQuery('<?php echo "#collection-widget-instance-" . $widget_instance_id; ?>').ready(function($){
			var parent = $('<?php echo "#collection-widget-instance-" . $widget_instance_id; ?>').closest('.widget');
			var widget = $.extend({}, collectionWidget );
			widget.init( parent );
			<?php if ( ! empty( $collection_items ) ) : ?>
				widget.selectPosts( $.parseJSON( '<?php echo json_encode( $collection_items ); ?>' ) );
			<?php endif; ?>

		});

	</script>
	<?php endif; ?>

</div>