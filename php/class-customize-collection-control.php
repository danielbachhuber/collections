<?php

class Customize_Collection_Control extends WP_Customize_Control {

	protected function render_content() {
		?>
		<label>
			<input type="hidden" value="<?php echo esc_attr( $this->value() ); ?>" <?php $this->link(); ?> />
		</label>

		<ul class="collection-items">

		</ul>

		<?php
	}

}
