<?php 
    get_header();
?>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <?php get_template_part('model-nav'); ?>

    <!-- Post Content -->
    <section id="contentCol">

        <?php 
            echo '<pre>';
            //var_dump($wp_query);
            echo '</pre>';
        ?>
        <!-- =================================== -->
        <!-- If have posts -->
        <?php if (have_posts()) : ?> 

            <!-- postlist -->
            <section id="articleLoop"></section>
            <!-- navigation -->
            <nav id="postNav" class="navigation"></nav>
            
        <?php endif; // if have_posts() ?>
        
        <div class="bg"></div>
    </section>
    <?php 
    get_template_part( "model-right-nav");
    get_footer(); ?>
