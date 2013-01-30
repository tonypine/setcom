
        </div> <!-- #wrapper -->
        <script type="text/javascript">
            var url = "<?php url() ?>";
        </script>

        <!-- All jss -->
        <script src="<?php url(); ?>/script.combined.js?v=1.1"></script>

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