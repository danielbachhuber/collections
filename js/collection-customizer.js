(function( wp, $ ){

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize;

	/**
	 * wp.customize.collectionCustomizer
	 */
	api.collectionCustomizer = {

		/**
		 * Initialize the Collection Customizer
		 */
		init: function() {

			this.bindEvents();

		},

		/**
		 * Bind the events we want to pay attention to
		 */
		bindEvents: function() {

			this.previewer.bind( 'rendered-collections', $.proxy( function( renderedCollections ) {

				// Render the collection controls

			}, this ) );

		}

	};

	/**
	 * Capture the instance of the Previewer since it is private
	 */
	OldPreviewer = api.Previewer;
	api.Previewer = OldPreviewer.extend({
		initialize: function( params, options ) {
			api.collectionCustomizer.previewer = this;
			OldPreviewer.prototype.initialize.call( this, params, options );
			this.bind( 'refresh', this.refresh );
		}
	} );

	/**
	 * Init Collections Customizer
	 */
	api.bind( 'ready', function() {

		api.collectionCustomizer.init();

	} );

})( window.wp, jQuery );