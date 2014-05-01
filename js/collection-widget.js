(function( $ ){

	collectionWidget = {

		/**
		 * Initialize the collectionWidget interface
		 */
		init: function( el ) {

			this.el = el;

			this.bindEvents();

		},

		bindEvents: function() {

			if ( this.el.hasClass( 'bound-events' ) ) {
				return;
			}

			this.el.on( 'click.collection-add-post', '.collection-widget a.add-post', $.proxy( function( e ) {

				e.preventDefault();

				var modal = collectionAddPostModal.init( this );
				modal.open();

			}, this ) );

			this.el.on( 'click.collection-remove-item', '.collection-widget a.collection-item-remove-action', $.proxy( function( e ) {

				e.preventDefault();
				$( e.currentTarget ).closest( 'li.collection-item' ).remove();

			}, this ) );

			this.el.addClass( 'bound-events' );

		},

		selectPosts: function( posts ) {

			var template = wp.template( 'collection-item' );
			$.each( posts, $.proxy( function( index, post ) {
				var data = {
					post: post
				};
				this.el.find('.collection-items').prepend( template( data ) );

				var collection_name = this.el.find('.collection-items').data('collection-item-field-name');
				this.el.find('.collection-item input').each( function( index, value ){
					if ( ! $( value ).attr( 'name' ) ) {
						$( value ).attr( 'name', collection_name );
					}
				});
			}, this ) );

		}

	};

})( jQuery );