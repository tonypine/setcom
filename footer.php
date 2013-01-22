
        </div> <!-- #wrapper -->
        <script type="text/javascript">
            var url = "<?php url() ?>";
        </script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?php url(); ?>/js/vendor/jquery-1.8.3.min.js"><\/script>')</script>
        <script src="<?php url(); ?>/js/plugins.js"></script>
        <script src="<?php url(); ?>/js/main.js"></script>

        <script type="text/javascript">
            var gOverride = {
              //urlBase: 'http://gridder.andreehansson.se/releases/latest/',
              gColor: '#bbb',
              gColumns: 12,
              gOpacity: 0.35,
              gWidth: 10,
              pColor: '#888',
              pHeight: 22,
              pOffset: 0,
              pOpacity: 0.6,
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
                .src='<?php url(); ?>/js/960gridder.js';
            }
        </script>

        <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
        <script>
            var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
            (function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
            g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
            s.parentNode.insertBefore(g,s)}(document,'script'));
        </script>

        <?php wp_footer() ?>
    </body>
</html>