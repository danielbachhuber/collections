<?php

class Customize_Collection_Control extends WP_Customize_Control {

	protected function render_content() {
		?>
		<div class="collection-control">

			<label>
				<input type="hidden" class="collection-item-ids" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
			</label>

			<p><a href="#" class="add-post button"><?php _e( 'Add Posts', 'collections' ); ?></a></p>

			<ul class="collection-items">

			</ul>

		</div>

		<?php
	}

}
