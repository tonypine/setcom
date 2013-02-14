
        <aside id="leftCol">
            <header id="topo">
                <h1><a id="logo" href="<?php echo site_url(); ?>/#/">Intranet MicrocampSP</a></h1>
            </header>
            <nav id="dpMenu"></nav>
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
                <form role="search" method="get" id="searchform" action="http://localhost/menusetcom/">
                    <div>
                        <label class="screen-reader-text" for="s">Pesquisar por:</label>
                        <input type="text" value="" name="s" id="s">
                        <input type="submit" id="searchsubmit" value="Pesquisar">
                    </div>
                </form>
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