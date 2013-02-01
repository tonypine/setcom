<?php 

	$loadFile = "../../../wp-load.php";
	if (file_exists($loadFile))
	    require_once($loadFile);

	import_request_variables('p');

	// set menu name
	$menu_name = $menuName;
	$menu_name;

	$menu = wp_get_nav_menu_object( $menu_name );
	$args = array(
			'order'			=>	"ASC",
			'orderby'		=>	"menu_order",
		);
	$menu_itens = wp_get_nav_menu_items($menu->term_id, $args);
	
	echo json_encode($menu_itens);
		

?>