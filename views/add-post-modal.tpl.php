<div id="collections-add-post-modal-backdrop" style="display:none;"></div>
<div id="collections-add-post-modal-wrap" style="display:none;">

	<div id="collections-add-post-modal-title">
		<?php _e( 'Add Post to Collection', 'collections' ); ?>
		<div id="collections-close-add-post-modal" class="collections-add-post-cancel"></div>
	</div>

	<form id="collections-add-post">

		<div id="collections-add-post-search-wrap">
			<input type="search" id="collections-add-post-search" placeholder="<?php esc_attr_e( 'Search', 'collections' ); ?>" />
		</div>

		<div id="collections-add-post-search-results-wrap">

			<script type="text/html" id="tmpl-collections-add-post-search-result">
				<li class="{{ data.classes }}"><label><input type="checkbox" name="collections-add-post[]" value="{{ data.post.ID }}" />{{ data.post.post_title }}</label></li>
			</script>

			<ul id="collections-add-post-search-results"></ul>

		</div>

		<div class="submitbox">
			<div id="collections-add-post-add-wrap">
				<?php submit_button( __( 'Add Post', 'collections' ), 'primary', 'collections-add-post-button', false ); ?>
			</div>

			<div id="collections-add-post-cancel-wrap">
				<a class="submitdelete deletion collections-add-post-cancel" href="#"><?php _e( 'Cancel', 'collections' ); ?></a>
			</div>
		</div>

		<?php wp_nonce_field( 'collections-add-post-search', 'collections-add-post-search-nonce' ); ?>

	</form>

</div>