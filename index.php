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
            <?php   while (have_posts()) : the_post();
                        get_template_part("model-article");
                    endwhile; ?>
        </section>
    <?php endif; ?>

        <aside id="rightCol">
            <h3>DESIGNER VS. PROGRAMADOR</h3>
            <span class="date">16 de janeiro de 2013</span>
            <p>O ser humano nem sempre faz escolhas racionais. A prova disso está em um estudo que descobriu algo inusitado: pessoas com olhos castanhos parecem mais confiáveis do que as de olhos azuis. Será mesmo? A pesquisa foi feita por cientistas da Universidade Carolina de Praga, na República Tcheca. Para descobrir</p>
                <ul class="navUl">
                    <li><a href="#">Marketing</a></li>
                    <li><a href="#">TI</a></li>
                    <li><a href="#">Administração</a></li>
                    <li><a href="#">Recursos Humanos</a></li>
                </ul>
        </aside>

<?php get_footer(); ?>
