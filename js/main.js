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
				alert('oi');
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
		//section.render();

		model_excerpt = Backbone.Model.extend({
			initialize: function() {
				alert('suck')
				// body...
			}
		});

		//excerpt = new model_excerpt;

});
