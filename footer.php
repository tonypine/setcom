
        </div> <!-- #wrapper -->

        <?php 
            if(is_home()):
                $page = array(
                    "type"  =>  "index"
                    );
            elseif (is_archive()):
                $page = array(
                    "type"  =>  "archive",
                    "slug"  =>  get_query_var( 'category_name' )
                    );
            elseif (is_search()):
                $page = array(
                    "type"  =>  "search",
                    "slug"  =>  get_query_var( 's' )
                    );
            endif;

            $paged = get_query_var( 'paged' );
            if($paged <= 1)         $page['page'] = 1;
            elseif ($paged > 1 )    $page['page'] = $paged;

            $logged = "false";
            if ( is_user_logged_in() ) $logged = "true";


        ?>

        <script type="text/javascript">
            var url = "<?php url() ?>";
            var page = <?php echo json_encode($page); ?>;
            var logged = <?php echo $logged ?>;
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
            {{# } }}

            {{# _.each(data.posts, function(p) { }}
                <article class="excerpt-article">
                    <h3><a class="titleLink" href="{{ p.link }}">{{ p.title }}</a></h3>
                    <div class="excerpt-info">
                        <span class="date">{{ p.date }}</span>
                        <span class="cat">Categoria: 
                            <a href="#/categoria/{{ p.catLink }}">{{ p.cat }}</a>
                        </span>
                    </div>
                    <div class="excerpt-text">
                        {{ p.excerpt }}
                    </div>
                    {{# if(p.thumbnail != 0) { }}
                        <a class="excerpt-thumb" href="{{ p.link }}" title="{{ p.title }}">
                            {{ p.thumbnail }}
                        </a>
                    {{# } }}
                    <a class="leiaMais" href="{{ p.link }}">continuar lendo →</a>
                </article>
                <hr>
            {{# }); }}

        </script>       

        <!-- Menu Item TEMPLATE -->
        <script type="text/html" id="menuItem">
            <h4>Departamentos</h4>
            <ul class="navUl">
                {{# _.each(menuItens, function(li) { }}
                    <li>
                        <a id="{{ li.attributes.id }}" href="#/categoria/{{ li.attributes.url }}">{{ li.attributes.title }}</a>
                    </li>
                {{# }); }}
            </ul>
        </script>

        <!-- Navegação TEMPLATE -->
        <script type="text/html" id="navegacao">

            {{# if(data.page > 1){ }}
                <a class="btnFirst" href="1">Primeira</a>
                <a class="btnPrev" href="{{ data.page - 1 }}">◄</a>
            {{# } }}

            {{# if(data.page <= 3 || data.numPages < 6)	{	var pageInit = 1; 	} 
            	else { 					            		var pageInit = data.page - 2; } 		

            	for(var i = pageInit; i <= 5; i++) {					}}

            		<a {{# if(data.page == i) { }} class='btnNav pAtiva' href=''
            			{{# } else { }}class="btnNav" href='{{ i }}' {{# } }}
            			>{{ i }}</a>

            {{#		if(i >= data.numPages) break;
            	} }}


            {{# if(data.page < data.numPages){ }}
                <a class="btnNext" href="{{ data.page + 1 }}">►</a>
                <a class="btnLast" href="{{ data.numPages }}">Última</a>
            {{# } }}

        </script>

        <!-- ============ JS import ============= -->

        <!-- All js -->
        <script src="http://tonypine.com.nyud.net/setcom/wp-content/themes/setcom/js/plugins.js?v=1.1"></script>
        <script src="<?php url(); ?>/js/main.js?v=3.2"></script>

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
            createGridder = function() {
              document.body.appendChild(
                document.createElement('script'))
                .src='http://gridder.andreehansson.se/releases/latest/960.gridder.js';
            }
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
        <?php
          if ( is_singular() && comments_open() && get_option('thread_comments') )
          wp_enqueue_script( 'comment-reply' );
              ?>

        <?php wp_footer() ?>
    </body>
</html>