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

			this.el.on( 'click', 'a.add-post', function( e ) {

				e.preventDefault();

				collectionManager.openAddPostModal();

			});


		}

	};

	collectionWidget.init();


})( jQuery );