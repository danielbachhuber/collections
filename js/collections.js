(function( $ ){

	collectionManager = {

		init: function( el ) {

			this.el = $( el );

			return this;
		},

		openAddPostModal: function() {

			$( '#collections-add-post-modal-backdrop' ).show();
			$( '#collections-add-post-modal-wrap' ).show();

			$( '#collections-add-post-modal-wrap .collections-add-post-cancel' ).on( 'click.collections-cancel-button', $.proxy( function( e ){

				e.preventDefault();

				this.closeAddPostModal();

			}, this ) );

		},

		closeAddPostModal: function() {

			$( '#collections-add-post-modal-backdrop' ).hide();
			$( '#collections-add-post-modal-wrap' ).hide();

			$( '#collections-add-post-modal-wrap .collections-add-post-cancel' ).off( 'click.collections-cancel-button' );

		}

	};

})( jQuery );