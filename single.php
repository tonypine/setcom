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
            <article class="content">
                <h1><?php the_title(); ?></h1>
                <span class="date"><?php echo get_the_date() ?></span>
                <?php 
                    the_content(); 

                    //get comments
                    $comments = get_comments(array(
                        'post_id' => get_the_ID(),
                        'status' => 'approve' //Change this to the type of comments to be displayed
                    ));

                    //if has comments
                    if(sizeof($comments) > 0):
                        echo "<hr>";
                        echo "<h3>Comentários</h3>";
                        echo "<ul class='commentlist'>";
                        //Display the list of comments
                        wp_list_comments(array(
                            'per_page' => 10, //Allow comment pagination
                            'reverse_top_level' => false, //Show the latest comments at the top of the list
                            'type' => 'comment',
                            'callback' => 'mytheme_comment',
                            'avatar_size' => 44
                        ), $comments);
                        echo "</ul>";
                    endif;
                    setcom_comment_form(array(
                            'title_reply' => 'Deixe um comentário',
                            'title_reply_to' => 'Deixe uma resposta'
                        ));
                ?>
                <!--
                <hr/>
                <h3>Comentários</h3>
                <?php //comments_template(); ?>
                -->

            </article>
            <?php endwhile; ?>
        </section>
    <?php endif; 
    get_template_part( "model-right-nav");
    get_footer(); ?>
