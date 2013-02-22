<?php
    /** Sets up the WordPress Environment. */
    $loadFile = "../../../wp-config.php";
    if (file_exists($loadFile))
        require_once($loadFile);

    require_once 'config.php';

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
    <form name="loginform" id="loginform" action="<?php echo $siteUrl; ?>/wp-login.php" method="post">
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