
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

        <!-- Jquery from Google CDN -->
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>

        <script type="text/html" id="postList">

            {{# _.each(posts, function(p) { }}
                <article>
                    <h3><a class="titleLink" href="{{ p.link }}">{{ p.title }}</a></h3>
                    <span class="date">{{ p.date }}</span><a class="cat" href="{{ p.catLink }}/">{{ p.cat }}</a>
                    {{ p.excerpt }}

                </article>
                <hr>
            {{# }); }}

        </script>       
        <script type="text/html" id="menuItem">
            <h4>Departamentos</h4>
            <ul class="navUl">
                {{# _.each(menuItens, function(li) { }}
                    <li>
                        <a id="{{ li.attributes.id }}" href="{{ li.attributes.url }}">{{ li.attributes.title }}</a>
                    </li>
                {{# }); }}
            </ul>
        </script>
        <script type="text/html" id="navegacao">

            {{# if(data.page > 1){ }}
                <a id="btnFirst" href="1">Primeira</a>
                <a id="btnPrev" href="{{ data.page - 1 }}">◄</a>
            {{# } }}

            {{# if(data.page <= 3 || data.numPages < 6)	{	var pageInit = 1; 	} 
            	else { 					            		var pageInit = data.page - 2; } 		

            	for(var i = pageInit; i <= 5; i++) {					}}

            		<a {{# if(data.page == i) { }} class='btnNav pAtiva' href='#'
            			{{# } else { }}class="btnNav" href='{{ i }}' {{# } }}
            			>{{ i }}</a>

            {{#		if(i >= data.numPages) break;
            	} }}


            {{# if(data.page < data.numPages){ }}
                <a id="btnNext" href="{{ data.page + 1 }}">►</a>
                <a id="btnLast" href="{{ data.numPages }}">Última</a>
            {{# } }}

        </script>

        <!-- Underscore from cdnjs CDN -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/underscore.js/1.4.4/underscore-min.js"></script>
        
        <!-- Backbone from cdnjs CDN -->
        <script src="//cdnjs.cloudflare.com/ajax/libs/backbone.js/0.9.9/backbone-min.js"></script>
        

        <!-- All js -->
        <script src="<?php url(); ?>/script.combined.js?v=1.4.1.6"></script>

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