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

        <!-- postlist -->
        <section id="articleLoop"></section>
        <!-- navigation -->
        <nav id="postNav" class="navigation"></nav>
            
        <div id="contentLoading"><h1>Loading...</h1></div>
        <div class="bg"></div>
    </section>
    <?php 
    get_template_part( "model-right-nav");
    get_footer(); ?>
