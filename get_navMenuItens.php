<?php 

	$loadFile = "../../../wp-load.php";
	if (file_exists($loadFile))
	    require_once($loadFile);

	import_request_variables('gp');

	// set menu name
	$menu_name = $menuName;
	$menu_name;

	$menu = wp_get_nav_menu_object( $menu_name );
	$args = array(
			'order'			=>	"ASC",
			'orderby'		=>	"menu_order",
		);
	$menu_itens = wp_get_nav_menu_items($menu->term_id, $args);
	$json = array();
	$i = 0;
	foreach ( (array) $menu_itens as $menu_item ) {
		$i = $i + 1;
		$item = array(
				'title'		=>	$menu_item->title,
				'url'		=>	basename($menu_item->url),
				'id'		=>	'navItem'.$i
			);
	    $json[] = $item;
	}
	//$json = (object) $json;	

	header("Cache-Control: max-age=".strtotime('-1 hours'));
    header("Expires: " . gmdate('D, d M Y H:i:s', strtotime('-1 hours')));
	header('Content-type: application/json');
	echo json_encode($json);

?>