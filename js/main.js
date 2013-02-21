$(document).ready(function(){
	$('a').click(function(){
		var anchor = $(this);
		var href = anchor.prop('href');
		var comment = anchor.attr('class');
		
		var patt = /#/g;
		if(patt.test(href))
			return

		patt = /commentLink/g;
		if(patt.test(comment))
			return

		var rel = /lightbox/g;
		if(rel.test(anchor.attr('rel')))
			return

		$('body').addClass('wait');
	});

	var baseUrl = url+"/";

	var h = 246;

	/* =====================================================
		Get Feed
	======================================================= */

	(function ($) {

		var methods = {
			init: function (options) {
				return this.each(function() {
					this.el = $(this);
					this.el.data( $.extend({
						feeds: [],
						empty: 1
					}, options) );
					methods.getFeed.apply( this );
				});
			},
			getFeed: function ( args ) {
				var _this = this;
				var el = _this.el;
				$.each(this.el.data('feeds'), function (index, feed) {

					el.append($("<div>", {
						'id': 'feed' + index
					}).text("loading feed..."));
					
					$.ajax({
						type: 'GET',
						url: baseUrl + "/getFeed.php",
						dataType: 'json',
						data: { feedUrl: feed.url },
						context: _this,
						success: function ( response ) {

							var template = _.template( $("#feedTEMPLATE").html() );
							response.title = feed.title;
							el.find( '#feed' + index ).html( template( { feed: response } ) );
							el.data('empty', 0);
						} 
					});

				});
			}
		};

		$.fn.getFeed = function ( method ) {
			
			if (methods[method])
				return methods[method].apply( this, Array.prototype.slice.call( arguments, 1) );
			else if ( typeof method === 'object' || ! method )
				return methods['init'].apply( this, arguments );
			else
				$.error( 'Method ' + method + ' does not exist on this function' );

		};

	})(jQuery);

	$('#newsFeed').getFeed({
		feeds: [ 
			{	title: 'Web Design',	url: "http://feeds.feedburner.com/CursoWebDesignMicrocampSP"			},
			{	title: 'Informática',	url: "http://feeds.feedburner.com/CursoInformaticaMicrocampSP"			},
			{	title: 'TI',			url: "http://feeds.feedburner.com/CursoTIMicrocampSP"					},
			{	title: 'ABC',			url: "http://feeds.feedburner.com/CursoABCMicrocampSP"					},
			{	title: 'Hardware',		url: "http://feeds.feedburner.com/BlogDoCursoHardware-MicrocampSp"		}
		]
	});

	/* =====================================================
		Adjust vertical rhythm of images 
	======================================================= */

	(function ($) {

		var methods = {
			init: function (options) {
				return this.each(function() {
					$(this).data( $.extend({
						'vHeight': 22
					}, options) );
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
				var s = _this.data();
				var oldH = _this.height();
				var ratio = _this.width() / oldH;
				var newH = ( (_this.height()/s.vHeight) >> 0 ) * s.vHeight;
				var newW = Math.round( newH * ratio );
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

	$(".attachment-excerpt-thumb, .imgAnchor img").load(function(){
		$(this).adjustVRhythm();
	});

	/* ===============================
		Ajax Login 
	===================================== */

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

		window.logout = methods.logout;

	})();

	/* ====================================================
		Search
	==================================================== */

	(function () {

		var methods = {

			init: function( options ){

				return this.each( function() {
					var _this = $(this);
					_this.data( $.extend({
						"_input": $("#s"),
						"sVal": '',
						"lastSearch": '',
						"sTimeout": null
					}, options) );

					var s = _this.data();
					
					s._input.bind('keyup', $.proxy( methods.doSearch, this ));
					_this.bind('submit', $.proxy( methods.doSearch, this ) );
				});

			},
			doSearch: function() {

				var _this = $(this);
				var s = _this.data();

				nCatView.desativeAll();

				if(s.sVal == s._input.val())
					return false;

				$postList.loadState();
				s.sVal = s._input.val();

				clearTimeout(s.sTimeout);


				if(s.lastSearch == s.sVal) {
					$postList.undoLoadState();
				} else if(s.sVal != '') {
					s.lastSearch = s.sVal;
					s.sTimeout = setTimeout(function(){
						app_router.navigate("#/busca/" + s.sVal);
					}, 500);
				} else
					app_router.navigate("#/");

				return false;

			}
		};

		// do search
		window.search = function (method) {
			if ( methods[method] ) {
				return methods[method].apply( this, Array.prototype.slice.call( arguments, 1 ));
			} else if ( typeof method === 'object' || ! method ) {
				return methods.init.apply( this, arguments );
			} else {
				$.error( 'Method ' +  method + ' does not exist on jQuery.tooltip' );
			}    
		};

	})();

	// init search engine
	window.s = search.apply( $("#searchform"), [ { _input: $("#s") } ] );


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

		// Post List
		// ====================================================
		window._postList = Backbone.View.extend({
			data: {
				type: 'index',
				slug: '',
				page: 1
			},
			newComment: false,
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
				$('html, body').animate({scrollTop:0}, 700);
				$("#contentLoading").css("display",'block');
				$postList.$el.css('opacity', 0.2);
				return $postList;
			},
			undoLoadState: function () {
				// wait status off
				$postList.$el.stop().css('opacity', 1);
				$("#contentLoading").css("display",'none');
				$('body').removeClass('wait');
				return $postList;
			},
			getPosts: function () {
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
						$(".attachment-excerpt-thumb, .imgAnchor img").load( function(){
							$(this).adjustVRhythm();
							});
						$postList.undoLoadState();

					}
				});		
			},
			getComments: function (postID) {
				loadScript(siteUrl+"/wp-includes/js/comment-reply.min.js?ver=3.5");
				$.ajax({
					type: 'GET',
					url: baseUrl+"get-comments.php",
					context: $postList,
					cache: false, // for while cache is always disabled
					//dataType: 'json',
					data: { postID: postID },
					success: function ( response ) {

						var _p = $postList;

						$('#comments').html( response );

						if(_p.newComment) {
							var top = $("#div-comment-" + _p.commentID).offset();
							top = top.top;
							$("html, body").animate({
								scrollTop: top - 22
							}, 700);
							_p.newComment = false;
						}

						//binding
						$("#commentLogout").bind('click', logout);
						$("#commentform").bind('submit', _p.postComment);
						$(".commentLink").bind('click', _p.scrollToComment);
						return response;

						_p.cData = response;
						_p.renderComments();
					}
				});
				return $postList;
			},
			postComment: function(evt) {
				var _p = $postList;
				_p.loadState();
				$.ajax({
					type: 'POST',
					url: baseUrl+"post-comment.php",
					context: _p,
					cache: false, // for while cache is always disabled
					//dataType: 'json',
					data: $(this).serialize(),
					success: function ( response ) {
						// this is $postList
						this.newComment = true;
						this.commentID = response;
						this.undoLoadState().getComments( this.attr.posts[0].id );
					}
				});	
				return false;
			},
			scrollToComment: function(evt) {
				var topOffset = evt.target.offset().top;
				$("html, body").scrollTop( topOffset );
			},
			renderComments: function() {
				var template = _.template( $("#commentsTEMPLATE").html() );
				this.$el.find('.content').append( template( { data: $postList.cData } ) );
			},
			render: function() {
				if($postList.data.type == 'post') {
					var template = _.template( $("#postTEMPLATE").html() );
					$postList.getComments( $postList.attr.posts[0].id );
					this.$el.attr({
						'id': '',
						'class': 'content'
					});
					$('body').addClass('single');
				} else {
					var template = _.template( $("#postList").html() );
					this.$el.attr({
						'id': 'articleLoop',
						'class': ''
					});
					$('body').removeClass('single');
				}
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
				page = $.fn.basename( p );
				console.log($postList.data);

				var type, slug, page;
				type = typeof $postList.data.type === 'undefined' || $postList.data.type == 'index' ? 'page' : $postList.data.type; + "";
				slug = typeof $postList.data.slug === 'undefined' ? '' : "/" + $postList.data.slug;

				app_router.navigate("#/" + type + slug + "/" + page);
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
			menuName: '',
			initialize: function( args ) {
				$.extend( this, args );
				//$navCollection = this;
			},
			getNav: function () {

				var nCol = this;
				var data = {
					menuName: this.menuName
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
					nCol.add( response );
				});
				
			},	
			setModelEl: function() {
				$.each( this.models, function(index, m) {
					m.el = $("#"+m.attributes.id);
				});
			}
		});

		window._navView = Backbone.View.extend({
			template: $("#menuItem").html(),
			initialize: function( data ) {
				var nView = this;
				this.$el.html("loading...").css({
					display: 'block'
				});
				this.collection = new _navCollection({ 
					menuName: this.options.menuName 
				});
				this.collection.bind('add', function(){ 
					nView.render();
					nView.collection.setModelEl();
				});
				this.collection.getNav();
			},
			events: {
				"click a": "click"//$postList.getPosts()
			},
			desativeAll: function() {
				var nView = this;
				$.each(nView.collection.models, function(index, model) {
					model.el.removeClass('ativo');
				});
			},
			click: function(e) {
				this.desativeAll();
				var link = $(e.target);
				link.addClass('ativo');
			},
			render: function() {
				var template = _.template( this.template );
				this.$el.html( template( { 
					menuItens: this.collection.models,
					title: this.options.title
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
				"page(/:page)": "indexPage",
				"(:type)(/:slug)(/:page)": "default"
				//"*": "defaultRoute" // Backbone will try match the route above first
			}
		});
		// Instantiate the router
		var app_router = new AppRouter;

		app_router.on('route:default', function (type, slug, page) {
			// Note the variable in the route definition being passed in here
			var s = $(window.s).data();
			if(type != 'busca') {  	
				s.lastSearch = ''; 	
				s.sVal = '';
			} else {
				s._input.val(slug);
			}

			//alert( type + ' - ' + slug + ' - ' + page );

			if(type == undefined)  	type = 'index';
			if(page == undefined || page == '')  	page = 1;

			$postList.data['page'] = page;
			$postList.data['type'] = type;
			$postList.data['slug'] = slug;
			$postList.getPosts();
		});
		
		app_router.on('route:indexPage', function (page) {
			// Note the variable in the route definition being passed in here
			
			if(page == undefined || page == '')  	page = 1;

			$postList.data['type'] = 'index';
			$postList.data['slug'] = undefined;
			$postList.data['page'] = page;
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

		window.nCatView = new _navView({ 
			el: $("#dpMenu"), 
			title: 'Categorias',
			menuName: 'departamentos' 
		});
		nLinksView = new _navView({ 
			el: $("#linksUteis"), 
			title: 'Links úteis',
			menuName: 'links-uteis' 
		});


});