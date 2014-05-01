(function( $ ){

	collectionAddPostModal = {

		wrap: $( '#collections-add-post-modal-wrap' ),
		backdrop: $( '#collections-add-post-modal-backdrop' ),
		resultPosts: [],
		selectedPosts: [],

		/**
		 * Initialize the collectionAddPostModal
		 */
		init: function( context ) {

			this.context = context;

			return this;
		},

		/**
		 * Open the modal to add a post to a collection
		 */
		open: function() {

			// Pre-populate the results
			this.searchPosts();

			this.backdrop.show();
			this.wrap.show();

			this.bindEvents();

		},

		/**
		 * Bind events for the AddPostModal
		 */
		bindEvents: function() {

			var liveSearch;

			// Live-ish search results
			$( '#collections-add-post-search', this.wrap ).on( 'keydown.collections-add-post-search', $.proxy( function( e ) {
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
			$( 'form', this.wrap ).on( 'submit.collections-add-post-submit', $.proxy( function( e ) {
				e.preventDefault();

				$( 'form input[name="collections-add-post[]"]:checked', this.wrap ).each( $.proxy( function( index, value ){
					var post_id = $( value ).val();
					var post = $.grep( this.resultPosts, function( e ){ return e.ID == post_id; } );
					this.selectedPosts.push( post[0] );
				}, this ) );

				if ( typeof this.context.selectPosts == 'function' ) {
					this.context.selectPosts( this.selectedPosts );
				}

				this.close();

			}, this ) );

			// Two forms of canceling out of the modal
			$( '.collections-add-post-cancel', this.wrap ).on( 'click.collections-cancel-button', $.proxy( function( e ){

				e.preventDefault();

				this.close();

			}, this ) );
			$('body').on( 'keydown.collections-add-post-escape', $.proxy( function( e ) {

				if ( e.keyCode == 27 ) {
					this.close();
				}

			}, this ) );

		},

		/**
		 * Unbind events for the AddPostModal
		 */
		unbindEvents: function() {

			$( '.collections-add-post-cancel', this.wrap ).off( 'click.collections-cancel-button' );
			$( 'body' ).off( 'keydown.collections-add-post-escape' );

			$( 'form', this.wrap ).off( 'submit.collections-add-post-submit' );

		},

		/**
		 * Close the "Add Post to Collection" modal
		 * without performing any actions
		 */
		close: function() {

			this.unbindEvents();

			this.backdrop.hide();
			this.wrap.hide();
			$( '#collections-add-post-search', this.wrap ).val('');

		},

		/**
		 * Search for posts
		 */
		searchPosts: function() {

			var data = {
				action:   'collections_add_post_search',
				s:        $( '#collections-add-post-search', this.wrap ).val(),
				nonce:    $( '#collections-add-post-search-nonce', this.wrap ).val()
			};

			$.get( ajaxurl, data, $.proxy( function( response ) {

				if ( response.status == 'success' ) {

					var searchResults = $('#collections-add-post-search-results', this.wrap );
					searchResults.empty();
					this.resultPosts = response.data.posts;
					this.selectedPosts = [];

					var resultTemplate = wp.template( 'collections-add-post-search-result' );
					var i = 0;
					$.each( response.data.posts, $.proxy( function( index, post ) {

						var classes = '';
						if ( ! i || i % 2 === 0 ) {
							classes += 'alternate';
						}
						i++;

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