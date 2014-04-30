(function( $ ){

	collectionManager = {

		addPostModalWrap: $( '#collections-add-post-modal-wrap' ),
		addPostModalBackdrop: $( '#collections-add-post-modal-backdrop' ),

		/**
		 * Initialize the collectionManager
		 */
		init: function( el ) {

			this.el = $( el );

			return this;
		},

		/**
		 * Open the modal to add a post to a collection
		 */
		openAddPostModal: function() {

			// Pre-populate the results
			this.searchPosts();

			this.addPostModalBackdrop.show();
			this.addPostModalWrap.show();

			this.bindAddPostModalEvents();

		},

		/**
		 * Bind events for the AddPostModal
		 */
		bindAddPostModalEvents: function() {

			var liveSearch;

			// Live-ish search results
			$( '#collections-add-post-search', this.addPostModalWrap ).on( 'keydown.collections-add-post-search', $.proxy( function( e ) {
				// Don't allow the input to be submitted
				if ( e.keyCode == 13 ) {
					e.preventDefault();
					return;
				}

				// Clear the previous search request
				if ( liveSearch ) {
					clearTimeout( liveSearch );
				}

				liveSearch = setTimeout( $.proxy( this.searchPosts, this ), 400 );

			}, this ) );

			// Submitting the form adds the posts to the collection
			$( 'form', this.addPostModalWrap ).on( 'submit.collections-add-post-submit', $.proxy( function( e ) {
				e.preventDefault();

			}, this ) );

			// Two forms of canceling out of the modal
			$( '.collections-add-post-cancel', this.addPostModalWrap ).on( 'click.collections-cancel-button', $.proxy( function( e ){

				e.preventDefault();

				this.closeAddPostModal();

			}, this ) );
			$('body').on( 'keydown.collections-add-post-escape', $.proxy( function( e ) {

				if ( e.keyCode == 27 ) {
					this.closeAddPostModal();
				}

			}, this ) );

		},

		/**
		 * Unbind events for the AddPostModal
		 */
		unbindAddPostModalEvents: function() {

			$( '.collections-add-post-cancel', this.addPostModalWrap ).off( 'click.collections-cancel-button' );
			$( 'body' ).off( 'keydown.collections-add-post-escape' );

		},

		/**
		 * Close the "Add Post to Collection" modal
		 * without performing any actions
		 */
		closeAddPostModal: function() {

			this.unbindAddPostModalEvents();

			this.addPostModalBackdrop.hide();
			this.addPostModalWrap.hide();
			$( '#collections-add-post-search', this.addPostModalWrap ).val('');

		},

		/**
		 * Search for posts
		 */
		searchPosts: function() {

			var data = {
				action:   'collections_add_post_search',
				s:        $( '#collections-add-post-search', this.addPostModalWrap ).val(),
				nonce:    $( '#collections-add-post-search-nonce', this.addPostModalWrap ).val()
			};

			$.get( ajaxurl, data, $.proxy( function( response ) {

				if ( response.status == 'success' ) {

					var searchResults = $('#collections-add-post-search-results', this.addPostModalWrap );
					searchResults.empty();

					var resultTemplate = wp.template( 'collections-add-post-search-result' );
					$.each( response.data.posts, $.proxy( function( index, post ) {

						var classes = '';
						console.log( index );
						if ( ! index || index % 2 === 0 ) {
							classes += 'alternate';
						}

						var data = {
							classes: classes,
							post: post
						};
						searchResults.append( resultTemplate( data ) );
					}, this ) );

				} else if ( response.status == 'error' ) {
					alert( response.message );
				}

			}, this ) );

		}

	};

})( jQuery );