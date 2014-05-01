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

						this.triggerDirtyWidget();

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

			}, this ) );

			this.el.addClass( 'bound-events' );

		},

		triggerDirtyWidget: function() {

			if ( typeof wp.customize.Widgets == 'undefined' ) {
				return;
			}

			if ( typeof this.control == 'undefined' ) {
				var parent = this.el.closest('li');
				var control_id = parent.attr('id').replace(/customize-control-widget_/, '');
				this.control = wp.customize.Widgets.getWidgetFormControlForWidget( control_id );
			}

			var settings = this.control.setting();
			settings.collection_items = [];
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

				var collection_name = this.el.find('.collection-items').data('collection-item-field-name');
				this.el.find('.collection-item input').each( function( index, value ){
					if ( ! $( value ).attr( 'name' ) ) {
						$( value ).attr( 'name', collection_name );
					}
				});
			}, this ) );

		}

	};

})( jQuery, wp );