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

		var baseUrl = "//localhost/menusetcom/wp-content/themes/setcom/";

		// Change underscore syntax to feel like Mustache templating syntax
		_.templateSettings = {
		  evaluate:    /\{\{#([\s\S]+?)\}\}/g,            // {{# console.log("blah") }}
		  interpolate: /\{\{[^#\{]([\s\S]+?)[^\}]\}\}/g,  // {{ title }}
		  escape:      /\{\{\{([\s\S]+?)\}\}\}/g,         // {{{ title }}}
		}

		// VIEW ---------------
		postList = Backbone.View.extend({
			data: {
				type: 'index',
				slug: ''
			},
			html: '',
			initialize: function( op ) {
				$.extend( this.data, op.page );
				this.getPosts();
				$(this.data).bind('change', this.getPosts, this);
			},
			getPosts: function () {
				this.$el.html('<article><h1>loading...</h2><article>');
				$.ajax({
					type: 'POST',
					url: baseUrl+"ajax-posts.php",
					context: this,
					dataType: 'json',
					data: { page: this.data }
				}).done(function ( response ) {
					//alert(response);
					this.html = response;
					this.render();
				});
			},
			render: function() {
				var template = _.template( $("#postList").html() );
				this.$el.html( template( { posts: this.html } ) );
			}
		});

		section = new postList({
			el: $("#articleLoop"),
			page: page
		});


		// Nav Menu
		navMenuItem = Backbone.Model.extend({
			idAttribute: '_id',
			el: 0,
			url: '#',
			template: $("#menuItem"),
			initialize: function () {
				// body...
			}
		});

		navMenu = Backbone.Collection.extend({
			model: navMenuItem,
			data: '',
			initialize: function() {
				this.getNav();
			},
			getNav: function () {
				$.ajax({
					type: 'POST',
					url: baseUrl+"get_navMenuItens.php",
					context: this,
					//dataType: 'json',
					data: { menuName: "Departamentos" }
				}).done(function ( response ) {
					alert(response);
					this.data = response;
					this.render();
				});
			},
			render: function() {
				var template = _.template( $("#menuItem").html() );
				this.$el.html( template( { menuItens: this.data } ) );
			}
		});

		var nav = new navMenu({
			el: $("#dpMenu")
		})


		model_excerpt = Backbone.Model.extend({
			initialize: function() {
				alert('suck')
				// body...
			}
		});

		//excerpt = new model_excerpt;

});
