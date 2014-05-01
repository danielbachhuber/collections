(function( $, wp ){

	$(document).on( 'widget-added', function( e, data ) {

		collectionWidget.init( data );
	});

	collectionWidget = {

		/**
		 * Initialize the collectionWidget interface
		 */
		init: function( el ) {

			this.el = el;

			this.bindEvents();

		},

		/**
		 * Bind events that this object should pay attention to
		 */
		bindEvents: function() {

			// Make the collection sortable each time
			$( 'ul.collection-items', this.el ).sortable({
					stop: $.proxy( function( event, ui ) {

						this.updateCollectionItemsList();

					}, this )
				});

			if ( this.el.hasClass( 'bound-events' ) ) {
				return;
			}

			// Instantiates the modal to add a new post to the collection
			this.el.on( 'click.collection-add-post', '.collection-widget a.add-post', $.proxy( function( e ) {

				e.preventDefault();

				var modal = collectionAddPostModal.init( this );
				modal.open();

			}, this ) );

			// Removes an item from the collection
			this.el.on( 'click.collection-remove-item', '.collection-widget a.collection-item-remove-action', $.proxy( function( e ) {

				e.preventDefault();
				$( e.currentTarget ).closest( 'li.collection-item' ).remove();
				this.updateCollectionItemsList();

			}, this ) );

			this.el.addClass( 'bound-events' );

		},

		updateCollectionItemsList: function() {

			var item_ids = [];
			$( 'li.collection-item', this.el ).each( function( e ){
				item_ids.push( $( this ).data( 'post-id' ) );
			});

			$( 'input.collection-widget-item-ids', this.el ).val( item_ids.join( ',' ) );
			$( 'input.collection-widget-item-ids', this.el ).trigger( 'change' );
		},

		selectPosts: function( posts ) {

			// Reverse order to apply in proper direction
			posts.reverse();

			var template = wp.template( 'collection-item' );
			$.each( posts, $.proxy( function( index, post ) {
				var data = {
					post: post
				};
				this.el.find('.collection-items').prepend( template( data ) );

				this.updateCollectionItemsList();

			}, this ) );

		}

	};

})( jQuery, wp );