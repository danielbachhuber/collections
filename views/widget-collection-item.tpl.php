<script type="text/html" id="tmpl-collection-item">
	<li class="collection-item">
		<input type="hidden" value="{{ data.post.ID }}" />
		<h5>{{ data.post.post_title }}</h5>
		<div class="collection-item-actions">
			<# if ( data.post.user_can_edit ) { #>
				<a href="{{ data.post.edit_link }}"><?php _e( 'Edit', 'collections' ); ?></a> | 
			<# } #>
			<a href="#" class="collection-item-remove-action delete"><?php _e( 'Remove', 'collections' ); ?></a>
		</div>
	</li>
</script>