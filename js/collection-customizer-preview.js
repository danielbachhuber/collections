(function( wp, $ ){

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize,
			OldPreview;

	/**
	 * wp.customize.collectionCustomizerPreview
	 *
	 * Sends captured collections to the Customizer UI
	 */
	api.collectionCustomizerPreview = {

		renderedCollections: {},

		init: function() {

			this.preview.bind( 'active', $.proxy( function() {

				this.preview.send( 'rendered-collections', this.renderedCollections );

			}, this ) );

		}

	};

	/**
	 * Capture the instance of the Preview since it is private
	 */
	OldPreview = api.Preview;
	api.Preview = OldPreview.extend( {
		initialize: function( params, options ) {
			api.collectionCustomizerPreview.preview = this;
			OldPreview.prototype.initialize.call( this, params, options );
		}
	} );

	/**
	 * Release the kraken!
	 */
	$(function () {
		var settings = window._wpCollectionsCustomizerPreviewSettings;
		if ( ! settings ) {
			return;
		}

		$.extend( api.collectionCustomizerPreview, settings );

		api.collectionCustomizerPreview.init();
	});

})( window.wp, jQuery );