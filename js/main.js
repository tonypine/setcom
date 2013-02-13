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

		var ie = false;

		if (/MSIE (\d+\.\d+);/.test(navigator.userAgent)) {
		 var ieversion=new Number(RegExp.$1)
		 if (ieversion<=8)
		 	ie = true;
		}

		// VIEW ---------------
		// ====================================================

		$("#searchform").bind('submit',function ( evt ) {
			var val = $("#s").val();
			if(val != '')
				app_router.navigate("#/busca/" + val);
			else
				app_router.navigate("#/");
			return false;
		});
		$("#s").bind('keyup',function ( evt ) {
			var val = $(this).val();
			if(val != '')
				app_router.navigate("#/busca/" + val);
			else
				app_router.navigate("#/");
			return false;
		});

		// Post List
		// ====================================================
		window._postList = Backbone.View.extend({
			data: {
				type: 'index',
				slug: '',
				page: 1
			},
			html: '',
			initialize: function( op ) {
				$postList = this;
				$postList.navigation = new _navigation;
				$.extend( this.data, op.page );
				//this.getPosts();
			},
			getPosts: function () {
				$('body').addClass('wait');
				$('html, body').animate({scrollTop:0}, 300);

				if(!ie) {
					$postList.$el.stop().animate({
						opacity: 0.2
					}, 0);
				} else {
					$postList.$el.css('opacity', 0.2);
				}

				var data = {
					page: $postList.data
				};

				if(logged) 	data.logged = 1;
				else 		data.logged = 0;

				// if search not cache
				var cache = true;
				if($postList.data.type === 'busca')
					cache = false;

				// ajax
				$.ajax({
					type: 'GET',
					url: baseUrl+"ajax-posts.php",
					context: $postList,
					cache: cache,
					dataType: 'json',
					data: data,
					success: function ( response ) {
						$postList.attr = response;

						$postList.navigation.render();
						$postList.render();
						
						// wait status off
						$postList.$el.stop().css('opacity', 1);
						$('body').removeClass('wait');
					}
				});
			},
			render: function() {

				var template = _.template( $("#postList").html() );
				this.$el.html( template( { data: $postList.attr, type: $postList.data.type } ) );
			}
		});

		// ====================================================
		// Pagination
		// ====================================================

		window._navigation = Backbone.View.extend({
			el: $(".postNav"),
			template: $("#navegacao"),
			initialize: function() {
				$n = this;
			},
			events: {
				"click a"	: "goPage"
			},
			goPage: function ( evt ) {
				var p = $(evt.target).attr('href');
				app_router.navigate("#/" + $postList.data.type + "/" + $postList.data.slug + "/" + p);
				return false;
			},
			render: function() {
				if($postList.attr.numPages <= 1) {
					$n.$el.html( '' );
					return $n;
				}

				var data = {
					numPages: parseInt($postList.attr.numPages),
					page: parseInt($postList.data.page)
				};
				var template = _.template( $n.template.html() );
				$n.$el.html( template( { data: data } ) );

				return $n;
			}
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
				if(logged) 	data.logged = 1;
				else 		data.logged = 0;

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
				$.each($navCollection.models, function(index, model) {
					this.el.removeClass('ativo');
				});
				var link = $(e.target);
				link.addClass('ativo');
				var href = link.attr('href'); 
				//app_router.navigate(href);
				//return false;
			},
			render: function() {
				var template = _.template( this.template );
				this.$el.html( template( { 
					menuItens: this.collection.models 
				} ) );
				this.$el.stop().css("display","none").slideDown(800, function(){
					$(this).css('overflow','initial');
				});
				return this;
			}
		});

		var postList = new _postList({
			el: $("#articleLoop"),
			page: page
		});


		// ====================================================
		// Router
		// ====================================================
		var AppRouter = Backbone.Router.extend({
			initialize: function(op) {
			},
	        routes: {
	            "(:type)(/:slug)(/:page)": "default"
	            //"*": "defaultRoute" // Backbone will try match the route above first
	        }
	    });
	    // Instantiate the router
	    var app_router = new AppRouter;

	    app_router.on('route:default', function (type, slug, page) {
	    	//alert(type + '-' + slug + "-" + page);
	        // Note the variable in the route definition being passed in here
	        if(type == undefined)
	        	type = 'index';
	        
	        if(page == undefined)
	        	page = 1;

		    $postList.data['page'] = page;
	        $postList.data['type'] = type;
	        $postList.data['slug'] = slug;
	        $postList.getPosts();
	    });
	    app_router.on('route:defaultRoute', function (actions, page) {
	        if(page != undefined)
		        $postList.data['page'] = page;
	        $postList.data['type'] = 'index';
	        $postList.data['slug'] = '';
	        $postList.getPosts();
	    });


	    // Start Backbone history a necessary step for bookmarkable URL's
	    Backbone.history.start();	

		navView = new _navView({ el: $("#dpMenu") }); 		// view
		//window.navCollection = new _navCollection; 		// collection

});
