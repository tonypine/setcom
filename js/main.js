$(document).ready(function(){
	$('a').click(function(){
		var href = $(this).attr('href');
		var patt = /#/g;
		if(patt.test(href)){
			return
		}
		$('body').addClass('wait');
	});

	/* load main post list */

		//dump(page);

		var baseUrl = url+"/";

		// Change underscore syntax to feel like Mustache templating syntax
		_.templateSettings = {
		  evaluate:    /\{\{#([\s\S]+?)\}\}/g,            // {{# console.log("blah") }}
		  interpolate: /\{\{[^#\{]([\s\S]+?)[^\}]\}\}/g,  // {{ title }}
		  escape:      /\{\{\{([\s\S]+?)\}\}\}/g          // {{{ title }}}
		};

		// TIMESTAMP COOKIE ========================

		// var timestamp = getCookie('timestamp');
		// if(timestamp != null && timestamp != "") {
		// 	setCookie( 'timestamp', 'cached2', 100 );
		// } else {
		// 	var date = new Date();
		// }
		//setCookie( 'timestamp', 'cached', 160 );



		// VIEW ---------------
		window._postList = Backbone.View.extend({
			data: {
				type: 'index',
				slug: ''
			},
			html: '',
			initialize: function( op ) {
				$postList = this;
				$.extend( this.data, op.page );
				this.getPosts();
			},
			getPosts: function () {
				$postList.$el.stop().animate({
					opacity: 0.2
				}, 0);
				$('body').addClass('wait');

				var data = {
					page: $postList.data
				};
				if(logged) data.logged = 1;

				// ajax
				$.ajax({
					type: 'GET',
					url: baseUrl+"ajax-posts.php",
					context: $postList,
					dataType: 'json',
					data: data,
					success: function ( response ) {
						$postList.html = response;
						$postList.render();
						$('body').removeClass('wait');
						$postList.$el.stop().animate({
							opacity: 1
						});
					}
				});
			},
			render: function() {
				var template = _.template( $("#postList").html() );
				this.$el.html( template( { posts: this.html } ) );
			}
		});

		var postList = new _postList({
			el: $("#articleLoop"),
			page: page
		});


		// ====================================================
		// Nav Menu
		// ====================================================

		window._navModel = Backbone.Model.extend({
			el: undefined,
			initialize: function ( settings ) {
				// body...
			}
		});

		window._navCollection = Backbone.Collection.extend({
			model: _navModel,
			initialize: function() {
				$navCollection = this;
			},
			getNav: function () {
				var data = {
					menuName: "Departamentos"
				};
				if(logged) data.logged = 1;

				$.ajax({
					type: 'GET',
					url: baseUrl+"get_navMenuItens.php",
					context: this,
					dataType: 'json',
					data: data
				}).done(function ( response ) {
					$navCollection.add( response );
				});
			},
			setModelEl: function() {
				$.each( $navCollection.models, function(index, m) {
					m.el = $("#"+m.attributes.id);
				});
			}
		});

		window._navView = Backbone.View.extend({
			template: $("#menuItem").html(),
			initialize: function(){
				$navView = this;
				$navView.$el.html("loading...").css({
					display: 'block'
				});
				this.collection = new _navCollection;
				this.collection.bind('add', function(){ 
					$navView.render();
					$navCollection.setModelEl();
				});
				this.collection.getNav();
			},
			events: {
				"click a": "click"//$postList.getPosts()
			},
			click: function(e) {
				var link = $(e.target);
				$postList.data = {
					type: 'archive',
					slug: link.attr('href')
				};
				$postList.getPosts();
				return false;
			},
			render: function() {
				var template = _.template( this.template );
				this.$el.html( template( { 
					menuItens: this.collection.models 
				} ) );
				this.$el.stop().css("display","none").slideDown(800);
				return this;
			}
		});

		navView = new _navView({ el: $("#dpMenu") }); 	// view
		//window.navCollection = new _navCollection; 		// collection

});
