
        <aside id="leftCol">
            <header id="topo">
                <h1><a id="logo" href="<?php echo site_url(); ?>/">Intranet MicrocampSP</a></h1>
            </header>
            <nav id="dpMenu">
                <!--
                <h4>Categorias</h4>
                <ul class="navUl">
                    <?php 
                        $navArgs = array(
                            'container'     =>  false,
                            'theme_location'    =>  'departamentos',
                            'menu'              =>  'departamentos',
                            'items_wrap'        =>  '%3$s'
                            );
                        wp_nav_menu( $navArgs );
                    ?> 
                </ul>
                -->
            </nav>
            <nav>
                <h4>Links Úteis</h4>
                <ul class="navUl">
                    <?php 
                        $navArgs = array(
                            'container'     =>  false,
                            'theme_location'    =>  'links',
                            'menu'              =>  'links-uteis',
                            'items_wrap'        =>  '%3$s'
                            );
                        wp_nav_menu( $navArgs );
                    ?> 
                </ul>
            </nav>
            <nav>
                <h4>Buscar</h4>
                <?php get_search_form( $echo = true ); ?>
            </nav>
            <nav>
                <?php if(is_user_logged_in()): ?>
                    <h4>Logado</h4>
                    <a class="button" href="<?php echo wp_logout_url(site_url()); ?>">Sair</a>
                <?php else: ?>
                    <h4>Login</h4>
                    <?php setcom_login_form(); ?>
                <?php endif; ?>
            </nav>
            <div class="bg"></div>
        </aside>