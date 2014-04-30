(function( $ ){

	var collectionWidget = {

		el: $('.collection-widget'),

		/**
		 * Initialize the collectionWidget interface
		 */
		init: function() {

			this.bindEvents();

		},

		bindEvents: function() {

			this.el.on( 'click', 'a.add-post', $.proxy( function( e ) {

				e.preventDefault();

				var modal = collectionAddPostModal.init( this );
				modal.open();

			}, this ) );

		},

		selectPosts: function( posts ) {

			var template = wp.template( 'collection-item' );
			$.each( posts, $.proxy( function( index, post ) {
				var data = {
					post: post
				};
				this.el.find('.collection-items').prepend( template( data ) );
			}, this ) );

		}

	};

	collectionWidget.init();


})( jQuery );