$(document).ready(function(){
	$('a').click(function(){
		var anchor = $(this);
		var href = anchor.attr('href');
		var patt = /#/g;
		if(patt.test(href))
			return

		var rel = /lightbox/g;
		if(rel.test(anchor.attr('rel')))
			return

		$('body').addClass('wait');
	});

	var baseUrl = url+"/";

	var h = 246;

	/* =====================================================
	Adjust vertical rhythm of images 
	======================================================= */

	(function ($) {


		var methods = {
			init: function (options) {
				return this.each(function() {
					var s = $.extend({
						'vHeight': 22
					}, options);
					methods.adjust.apply(this);
					$(window).bind('resize', $.proxy( methods.adjust, this ));
				});
			},
			reset: function() {
				var _this = $( this );
				_this.css({
					height: 'auto',
					width: 'auto'
				});
			},
			adjust: function() {
				methods.reset.apply( this );
				var _this = $( this );
				var oldH = _this.height();
				var ratio = _this.width() / oldH;
				var newH = ( (_this.height()/22) >> 0 ) * 22;
				var newW = Math.round( newH * ratio );
				console.log( newH + "/" + newW );
				_this.height( newH );
				_this.width( newW );
			}
		};

		$.fn.adjustVRhythm = function(method) {
			if ( methods[method] ) {
				return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
			} else if ( typeof method === 'object' || ! method ) {
				return methods.init.apply( this, arguments );
			} else {
				$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
			}    
		};

	})(jQuery);

	$(".imgAnchor img").adjustVRhythm();

	/* ===============================
	Ajax Login ======================= */

	(function() {

		// variables
		var section = $("#login");
		var form = section.find("#loginform");
		var loginUrl = baseUrl+"ajax-login.php";

		var methods = {
			wait: function(){
				$('body').addClass('wait');
				section.css('opacity',0.2);
			},
			done: function() {
				$('body').removeClass('wait');
				section.css('opacity',1);
			},
			login: function() {
				methods.wait();
				$.ajax({
					type: 'POST',
					url: loginUrl,
					cache: false, // for while cache is always disabled
					dataType: 'html',
					data: form.serialize(),
					success: function ( response ) {
						methods.done();
						$("#notLogged").addClass('hidden');
						$("#logged").removeClass('hidden');
						$postList.data.logged = 1;
						$postList.getPosts();
						$("#loginMsg").html( response );

					}
				});
				return false;
			},
			logout: function() {
				methods.wait();
				$.ajax({
					type: 'POST',
					url: loginUrl,
					dataType: 'html',
					success: function( response ) {
						methods.done();
						$("#logged").addClass('hidden');
						$("#notLogged").removeClass('hidden');
						$postList.data.logged = 0;
						$postList.getPosts();
						$("#loginMsg").html( response );
					}
				});
				return false;
			}
		};

		// bindings
		form.bind('submit', methods.login );
		$("#btnLogout").bind('click', methods.logout );

	})();


	/* load main post list */

		//dump(page);


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


		// Search ---------------
		// ====================================================

		// vars
		var searchTimeout = null;
		var valSearch = '';
		var lastSearch = '';
		var searchInput = $("#s");

		// binding
		$("#searchform").bind('submit', search);
		$("#s").bind('keyup', search);

		// do search
		function search (val) {

			if(valSearch == searchInput.val())
				return false;

			$postList.loadState();
			valSearch = searchInput.val();
			clearTimeout(searchTimeout);

			if(lastSearch == valSearch)
				$postList.undoLoadState();
			else if(valSearch != '') {
				lastSearch = valSearch;
				app_router.navigate("#/busca/" + valSearch);
			} else
				app_router.navigate("#/");

			return false;
		}

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
			loadState: function () {
				// please wait
				$('body').addClass('wait');
				$postList.$el.css('opacity', 0.2);
			},
			undoLoadState: function () {
				// wait status off
				$postList.$el.stop().css('opacity', 1);
				$('body').removeClass('wait');
			},
			getPosts: function () {
				$('html, body').animate({scrollTop:0}, 300);

				$postList.loadState();

				var data = {
					page: $postList.data
				};

				// if search not cache
				var cache = true;
				if($postList.data.type === 'busca')
					cache = false;

				// ajax
				$.ajax({
					type: 'GET',
					url: baseUrl+"ajax-posts.php",
					context: $postList,
					cache: false, // for while cache is always disabled
					dataType: 'json',
					data: data,
					success: function ( response ) {

						$postList.attr = response;

						$postList.render();
						$postList.navigation.render();
						$(".attachment-excerpt-thumb").adjustVRhythm();
						$postList.undoLoadState();

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
			el: $("#postNav"),
			template: $("#paginationTemplate"),
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

			if(type != 'busca') {  	
				lastSearch = ''; 	
				valSearch = ''; 	
			} else {
				searchInput.val(slug);
			}

			if(type == undefined)  	type = 'index';
			if(page == undefined)  	page = 1;

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
