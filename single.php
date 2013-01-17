<?php 
    get_header();
?>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <!-- Add your site or application content here -->
        <?php get_template_part('model-nav'); ?>

    <!-- Post Content -->
        
    <?php if (have_posts()) : ?> 
        <section id="contentCol">
            <?php while (have_posts()) : the_post(); ?>
            <article>
                <h3><?php the_title(); ?></h3>
                <span class="date"><?php echo get_the_date() ?></span>
                <?php the_content(); ?>
            </article>
            <?php endwhile; ?>
        </section>
    <?php endif; ?>

        <aside id="rightCol">
            
        </aside>

<?php get_footer(); ?>
