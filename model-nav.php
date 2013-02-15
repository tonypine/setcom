
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
            <nav id="searchNav">
                <h4>Buscar</h4>
                <form role="search" method="get" id="searchform" action="http://localhost/menusetcom/">
                    <div>
                        <label class="screen-reader-text" for="s">Pesquisar por:</label>
                        <input type="text" value="" name="s" id="s">
                        <input type="submit" id="searchsubmit" value="Pesquisar">
                    </div>
                </form>
            </nav>
            <section id="login">
                <?php 
                    $loggedin = '';
                    $notLogged = '';
                    if(is_user_logged_in()): 
                        $loggedin = 'class="hidden"';
                    else:
                        $notLogged = 'class="hidden"';
                    endif;
                ?>
                <div id="logged" <?php echo $notLogged; ?>>
                    <h4>Logado</h4>
                    <a id="btnLogout" class="button" href="<?php echo wp_logout_url(site_url()); ?>">Sair</a>
                </div>
                <div id="notLogged" <?php echo $loggedin; ?>>
                    <h4>Login</h4>
                    <form name="loginform" id="loginform" action="http://localhost/menusetcom/wp-login.php" method="post">
                        <p class="login-username">
                            <label for="user_login">Nome de usuário</label>
                            <input type="text" name="log" id="user_login" class="input" value="" size="20">
                        </p>
                        <p class="login-password">
                            <label for="user_pass">Senha</label>
                            <input type="password" name="pwd" id="user_pass" class="input" value="" size="20">
                        </p>
                        
                        <p class="login-submit">
                            <input type="submit" name="wp-submit" id="wp-submit" class="button-primary" value="Login">
                            <input type="hidden" name="redirect_to" value="http://localhost/menusetcom/">
                        </p>
                        <p class="login-remember"><label><input name="rememberme" type="checkbox" id="rememberme" value="forever"> Lembrar</label></p>
                        <p id="loginMsg"></p>
                    </form>
                </div>
            </section>
            <div class="bg"></div>
        </aside>