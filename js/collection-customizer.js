(function( wp, $ ){

	if ( ! wp || ! wp.customize ) {
		return;
	}

	var api = wp.customize;

	/**
	 * wp.customize.collectionCustomizer
	 */
	api.collectionCustomizer = {

		renderedCollections: {},
		prototypeControl: false,

		/**
		 * Initialize the Collection Customizer
		 */
		init: function() {

			this.prototypeControl = $( '#customize-controls #accordion-section-collection_section___prototype__' );

			this.bindEvents();

		},

		/**
		 * Bind the events we want to pay attention to
		 */
		bindEvents: function() {

			this.previewer.bind( 'rendered-collections', $.proxy( function( renderedCollections ) {

				$.each( renderedCollections, $.proxy( function( name, items ) {

					if ( $( '#customize-controls #accordion-section-collection_section_' + name ).length ) {
						return;
					}

					this.renderCollectionControl( name, items );

				}, this ) );

			}, this ) );

		},

		/**
		 * Render a control for a Collection
		 */
		renderCollectionControl: function( name, items ) {

			var clone = this.prototypeControl.clone();
			var collectionControlHTML = clone.wrap('<div>').parent().html().replace(/__prototype__/, name );
			var collectionControl = $( collectionControlHTML );
			collectionControl.find( 'h3' ).append( name );

			this.prototypeControl.after( collectionControl );

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