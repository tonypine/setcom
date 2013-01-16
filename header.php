<?php 
    $u = get_bloginfo('template_url');
    $class = '';
    if(is_single()):
        $class = ' single';
    endif;
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?php echo $u; ?>/css/style.css">
        <script src="<?php echo $u; ?>/js/vendor/modernizr-2.6.2.min.js"></script>

        <?php wp_head() ?>
    </head>
    <body class="<?php echo $class; ?>">

        <header id="topo">
            <div>
                <h1><a id="logo" href="<?php echo site_url(); ?>">Intranet MicrocampSP</a></h1>
            </div>
        </header>
        <div id="wrapper">