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
        <header>
            <h1>Resultados da busca:</h1>
            <p>
                <?php
                    $s = get_query_var( 's' );
                    $fp = $wp_query->found_posts;
                    echo "<strong class='destaque'>$fp</strong> resultados encontrados para: <strong class='destaque'>$s</strong>";
                    echo "<pre>";
                    //var_dump($wp_query);
                    echo "</pre>";
                ?>
            </p>
        </header>
        <!-- =================================== -->
        <!-- loop -->
        <?php if (have_posts()) : ?> 
            <section id="articleLoop">
            <?php   while (have_posts()) : the_post();
                        get_template_part("model-article");
                    endwhile; ?>
            </section>

        <!-- =================================== -->
        <!-- navigation -->
            <?php 
                $page = (get_query_var('paged')) ? get_query_var('paged') : 1; 
                if(is_null($page)) $page = 1;
                $maxPages = $wp_query->max_num_pages;

                if($maxPages > 1):
            ?>
                <nav class="navigation">
                    <?php if($page > 1): ?>
                        <a href="?paged=1&s=<?php echo $s; ?>">Primeira</a>
                        <a href="?paged=<?php echo ($page -1) . "&s=$s"; ?>">◄</a>
                    <?php endif; ?>
                    <?php 

                        if($page <= 3 || $maxPages < 6): 
                            $pagedInit = 1;
                        else: 
                            $pagedInit = $page - 2;
                        endif;

                        for($i = $pagedInit; $i <= 5; $i++): ?>
                            <a <?php 
                                if($page == $i || is_null($page) && $i == 1):
                                    echo "class='pAtiva' href='#'"; 
                                else: 
                                    echo "href='?paged=$i&s=$s'";
                                endif; ?>><?php echo $i; ?>
                            </a>
                        <?php 
                            if($i >= $maxPages) break;
                        endfor;
                    ?>
                    <?php if($page < $maxPages): ?>
                        <a href="?paged=<?php echo ($page +1) . "&s=$s"; ?>">►</a>
                        <a href="?paged=<?php echo $maxPages."&s=$s"; ?>">Última</a>
                    <?php endif ?>
                </nav>
            <?php endif; // if maxPages > 1
            endif; // if have_posts() ?>

    </section>
    <?php 
    get_template_part( "model-right-nav");
    get_footer(); ?>
