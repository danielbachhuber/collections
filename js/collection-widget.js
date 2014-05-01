(function( $ ){

	var collectionWidgets = {

		/**
		 * Initialize the collectionWidget interface
		 */
		init: function() {

			this.bindEvents();

		},

		bindEvents: function() {

			$('body').on( 'click.collection-add-post', '.collection-widget a.add-post', $.proxy( function( e ) {

				e.preventDefault();

				this.currentWidget = $( e.currentTarget ).closest( '.collection-widget' );

				var modal = collectionAddPostModal.init( this );
				modal.open();

			}, this ) );

			$('body').on( 'click.collection-remove-item', '.collection-widget a.collection-item-remove-action', $.proxy( function( e ) {

				e.preventDefault();
				$( e.currentTarget ).closest( 'li.collection-item' ).remove();

			}, this ) );

		},

		selectPosts: function( posts ) {

			var template = wp.template( 'collection-item' );
			$.each( posts, $.proxy( function( index, post ) {
				var data = {
					post: post
				};
				this.currentWidget.find('.collection-items').prepend( template( data ) );
			}, this ) );

		}

	};

	collectionWidgets.init();


})( jQuery );