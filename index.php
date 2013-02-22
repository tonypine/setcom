<?php 

    /** Sets up the WordPress Environment. */
    $loadFile = "../../../wp-config.php";
    if (file_exists($loadFile))
        //require_once($loadFile);
    
    //get_header();
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if IE 9]>         <html class="no-js ie9">    <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">      

        <!-- title -->
        <title>Intranet MicrocampSP</title>
        
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <?php require_once 'config.php'; ?>

        <link rel="stylesheet" href="<?php echo $url; ?>/css/style.css?v=1.7" type="text/css">
        <link rel="shortcut icon" href="<?php echo $url; ?>/favicon.ico">

        <!--[if lt IE 9]>
        <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Yanone+Kaffeesatz:300,700|Open+Sans:400,700" type="text/css">
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script src="<?php echo $url; ?>/js/respond.js"></script>
        <![endif]-->

        <?php //wp_head() ?>
    </head>
    <body>
        <div id="hrtopo"></div>
        <div id="wrapper">
    <!--[if lt IE 7]>
        <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
    <![endif]-->

    <!-- =================================== -->
    <!-- LEFT COL -->
    <aside id="leftCol">
        <header id="topo">
            <h1><a id="logo" href="<?php echo $siteUrl; ?>/#/">Intranet MicrocampSP</a></h1>
        </header>
        <nav id="dpMenu"></nav>
        <nav id="linksUteis"></nav>
        <nav id="searchNav">
            <h4>Buscar</h4>
            <form role="search" method="get" id="searchform" action="http://localhost/menusetcom/">
                <div>
                    <label class="screen-reader-text" for="s">Pesquisar por:</label>
                    <input type="text" value="" name="s" id="s">
                    <input type="submit" id="searchsubmit" value="Pesquisar">
                </div>
            </form>
        </nav>
        <section id="login"></section>
        <?php //require_once 'login.php'; ?>
        <div class="bg"></div>
    </aside>

    <!-- =================================== -->
    <!-- Post Content -->
    <section id="contentCol">

        <!-- postlist -->
        <section id="articleLoop"></section>
        <!-- navigation -->
        <nav id="postNav" class="navigation"></nav>
            
        <div id="contentLoading"><h1>Loading...</h1></div>
        <div class="bg"></div>
    </section>


    <!-- =================================== -->
    <!-- right col -->
    <aside id="rightCol">
        <section class="feed">
            <div id="newsFeed"></div>
            <?php //getFeed("http://chocoladesign.com/feed"); ?>
        </section>
        <div class="bg"></di>
    </aside>

    <?php //get_footer(); ?>


        </div> <!-- #wrapper -->

        <?php 


        ?>

        <script type="text/javascript">
            var siteUrl = "<?php echo $siteUrl; ?>";
            var url = "<?php echo $url ?>";
        </script>

        <!-- ============ TEMPLATES ============= -->

        <!-- Post List TEMPLATE -->
        <script type="text/html" id="postList">
            {{# if(type == 'busca') { }}
                <header id="search-info">
                    <h1>Resultados da busca:</h1>
                    <p>
                        <strong class='destaque'>
                            {{ data.foundPosts }}
                        </strong> resultados encontrados para: <strong class='destaque'>{{ data.s }}</strong>
                    </p>
                </header>
                <hr>
            {{# } else if(type == 'categoria') { }}
                <header>
                    <h1>{{ data.slug }}</h1>
                    <p>
                        <strong class='destaque'>
                            {{ data.foundPosts }}
                        </strong> posts publicados.
                    </p>
                </header>
                <hr>
            {{# } }}
            {{# _.each(data.posts, function(p) { }}
                <article class="excerpt-article">
                    <h3><a class="titleLink" href="{{ siteUrl }}/#/post/{{ p.slug }}">{{ p.title }}</a></h3>
                    <div class="excerpt-info">
                        <span class="date">{{ p.date }}</span>
                        <span class="cat">Categoria: 
                            {{# var i = 0;
                                _.each(p.cat, function (c) { i++; }}
                                    <a href="{{ siteUrl }}/#/categoria/{{ c.slug }}">{{ c.cat_name }}</a>
                                    {{# if(i < p.cat.length) }},
                            {{# }); }}
                        </span>
                    </div>
                    <div class="excerpt-text">
                        {{ p.excerpt }}
                    </div>
                    {{# if(p.thumbnail != 0) { }}
                        <a class="excerpt-thumb" href="{{ siteUrl }}/#/post/{{ p.slug }}" title="{{ p.title }}">
                            {{ p.thumbnail }}
                        </a>
                    {{# } }}
                    <a class="leiaMais" href="{{ siteUrl }}/#/post/{{ p.slug }}">continuar lendo →</a>
                </article>
                <hr>
            {{# }); }}

        </script>

        <!-- Post TEMPLATE -->
        <script type="text/html" id="postTEMPLATE">

            {{# _.each(data.posts, function(p) { }}
                <article class="content">
                    <h1>{{ p.title }}</h1>
                    <div class="excerpt-info">
                        <span class="date">{{ p.date }}</span>
                        <span class="cat">Categoria: 
                            {{# var i = 0;
                                _.each(p.cat, function (c) { i++; }}
                                    <a href="{{ siteUrl }}/#/categoria/{{ c.slug }}">{{ c.cat_name }}</a>
                                    {{# if(i < p.cat.length) }},
                            {{# }); }}
                        </span>
                    </div>
                    <div class="excerpt-text">
                        {{ p.content }}
                    </div>
                    <div id="comments"></div>
                </article>
            {{# }); }}

        </script>       

        <!-- comments TEMPLATE -->
        <script type="text/html" id="commentsTEMPLATE">
            <hr>
            <h3>Comentários</h3>
            <ul class="commentlist">
                {{# _.each(data.comments, function(c) { }}
                    <li class="comment even thread-even depth-1" id="comment-{{ c.comment_ID }}">
                        <div id="div-comment-{{ c.comment_ID }}" class="comment-body">
                        <div class="comment-author vcard">
                            {{ c.avatar }}
                            <cite class="fn">{{ c.comment_author }}</cite>
                            <div class="date">
                                <a href="http://localhost/menusetcom/designer-vs-programador/#comment-{{ c.comment_ID }}">
                                    {{ c.comment_date }}
                                </a>
                            </div>
                        </div>

                        <p class="commentText">{{ c.comment_content }}</p>
                        <div class="reply">
                            <a class="comment-reply-link" 
                                href="{{ siteUrl + "/" + data.slug }}/?replytocom={{ c.comment_ID }}#respond" 
                                onclick="return addComment.moveForm(&quot;div-comment-8&quot;, &quot;8&quot;, &quot;respond&quot;, &quot;20&quot;)">
                                    Responder
                                </a>
                            </div>
                        </div>
                    </li>                
                {{# }); }}
            </ul>

        </script>       

        <!-- feed TEMPLATE -->
        <script type="text/html" id="feedTEMPLATE">
            <div class="feed">
                <h4>{{ feed.title }}</h4>
                <ul class='list'>
                    {{# _.each(feed.channel.item, function(f) { }}
                        <li><a href='{{ f.link }}' title='{{ f.title }}'>{{ f.title }}</a></li>
                    {{# }); }}
                </ul>
            </div>
        </script>

        <!-- Menu Item TEMPLATE -->
        <script type="text/html" id="menuItem">
            <h4>{{ title }}</h4>
            <ul class="navUl">
                {{# _.each(menuItens, function(li) { }}
                    <li>
                        <a id="{{ li.attributes.id }}" href="{{ siteUrl }}/#/categoria/{{ li.attributes.url }}">{{ li.attributes.title }}</a>
                    </li>
                {{# }); }}
            </ul>
        </script>

        <!-- Paginação TEMPLATE -->
        <script type="text/html" id="paginationTemplate">
            {{# if(data.page > 1){ }}
                <a class="btnFirst" href="1">Primeira</a>
                <a class="btnPrev" href="{{ data.page - 1 }}">◄</a>
            {{# } }}

            {{# if(data.page <= 3 || data.numPages < 6) {   var pageInit = 1;   } 
                else {                                      var pageInit = data.page - 2; }         

                for(var i = pageInit; i <= 5; i++) {                    }}

                    <a {{# if(data.page == i) { }} class='btnNav pAtiva' href=''
                        {{# } else { }}class="btnNav" href='{{ i }}' {{# } }}
                        >{{ i }}</a>

            {{#     if(i >= data.numPages) break;
                } }}

            {{# if(data.page < data.numPages){ }}
                <a class="btnNext" href="{{ data.page + 1 }}">►</a>
                <a class="btnLast" href="{{ data.numPages }}">Última</a>
            {{# } }}
        </script>

        <!-- ============ JS import ============= -->

        <!-- All js -->
        <script src="<?php echo $url; ?>/js/plugins.js?v=1.1"></script>

        <!-- >
          <script type="text/javascript" src="<?php echo $url; ?>/js/selectivizr.js"></script>
        <! -->

        <script type="text/javascript">
        
            // JavaScript
            function loadScript(src, callback) {
                var head = document.getElementsByTagName('head')[0],
                    script = document.createElement('script');
                done = false;
                script.setAttribute('src', src);
                script.setAttribute('type', 'text/javascript');
                script.setAttribute('charset', 'utf-8');
                script.onload = script.onreadstatechange = function() {
                    if (!done && (!this.readyState || this.readyState == 'loaded' || this.readyState == 'complete')) {
                        done = true;
                        script.onload = script.onreadystatechange = null;
                            if (callback) {
                                callback();
                            }
                        }
                }
                head.insertBefore(script, head.firstChild);
            }

            // load the my-script-file.js and display an alert dialog once the script has been loaded
            //loadScript('<?php echo $url; ?>/js/plugins.js?v=1.2', function() { console.log('loaded plugins'); });
            loadScript('<?php echo $url; ?>/js/main.js?v=4.6');
            loadScript('<?php echo $url; ?>/js/lightbox-min.js?v=1');

        </script>
        <!--
        <script src="<?php echo $url; ?>/js/main.js?v=4"></script>
        -->

        <script type="text/javascript">
            var gOverride = {
              urlBase: 'http://gridder.andreehansson.se/releases/latest/',
              gColor: '#EEEEEE',
              gColumns: 12,
              gOpacity: 0.35,
              gWidth: 10,
              pColor: '#C0C0C0',
              pHeight: 22,
              pOffset: 0,
              pOpacity: 0.55,
              center: true,
              gEnabled: false,
              pEnabled: true,
              setupEnabled: true,
              fixFlash: true,
              size: 960
            };
        </script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            /*
                var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
                (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
                g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
                s.parentNode.insertBefore(g,s)}(document,'script'));
            */
        </script>
    
        <?php //wp_footer() ?>
    </body>
</html>