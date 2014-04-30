<div id="collections-add-post-modal-backdrop"></div>
<div id="collections-add-post-modal-wrap">

	<div id="collections-add-post-modal-title"><?php _e( 'Add Post to Collection', 'collections' ); ?><div id="collections-close-add-post-modal" class="collections-add-post-cancel"></div></div>

	<form id="collections-add-post">

		<div class="submitbox">
			<div id="collections-add-post-add-wrap">
				<?php submit_button( __( 'Add Post', 'collections' ), 'primary', 'collections-add-post-button', false ); ?>
			</div>

			<div id="collections-add-post-cancel-wrap">
				<a class="submitdelete deletion collections-add-post-cancel" href="#"><?php _e( 'Cancel', 'collections' ); ?></a>
			</div>
		</div>

	</form>

</div>