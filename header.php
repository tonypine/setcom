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
<!--[if IE 9]>         <html class="no-js ie9">    <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">      

        <!-- title -->
        <title><?php wp_title(); ?></title>
        
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->

        <link rel="stylesheet" href="<?php echo $u; ?>/css/style.css?v=1.1">
        <link rel="shortcut icon" href="<?php url(); ?>/favicon.ico">

        <!--[if lt IE 8]>
        <script src="http://ie7-js.googlecode.com/svn/version/2.1(beta4)/IE8.js"></script>
        <![endif]-->

        <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <script src="<?php url(); ?>/js/respond.js"></script>
        <![endif]-->

        <!--[if (gte IE 6)&(lte IE 8)]>
          <script type="text/javascript" src="<?php url(); ?>/js/selectivizr.js"></script>
        <![endif]-->

        <?php wp_head() ?>
    </head>
    <body class="<?php echo $class; ?>">
        <div id="wrapper">