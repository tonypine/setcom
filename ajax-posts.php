<?php 

	$loadFile = "../../../wp-load.php";
	if (file_exists($loadFile))
	    require_once($loadFile);

	import_request_variables('gp');
	$page = (object) $page;
	//var_dump($page->type);
	//exit;
	// var_dump($_POST['page']);
	// exit;
	switch ($page->type) {
		case 'index':
			$args = array(
					'post_type' => 'post'
				);
			break;
		case 'archive':
			$args = array(
					'category_name' => $page->slug
				);
			break;
		default:
			$args = array(
					'post_type' => 'post'
				);
			break;
	}
	$args['paged'] = $page->page;

	$query = new WP_Query( $args );
	//var_dump($query);
	//exit;
	//echo json_encode($query->get_post());

	$queryVars = array(
		"numPages"		=>	$query->max_num_pages,
		"posts"			=>	array()
		);
	$posts = array();
	while ($query->have_posts()): $query->the_post();
		
        $c = get_the_category();
        $cat = $c[0]->cat_name;
        $catLink = $c[0]->slug;
		$p = array(
			'title' 	=>	get_the_title(),
			'excerpt'	=>	get_the_excerpt(),
			'date'		=>	get_the_date(),
			'cat'		=>	$cat,
			'catLink'	=>	$catLink,
			'link'		=>	get_permalink()
		);
		$queryVars['posts'][] = $p;

	endwhile;
	
	$queryVars = (object) $queryVars;

	header("Cache-Control: max-age=".strtotime('-1 hours'));
    header("Expires: " . gmdate('D, d M Y H:i:s', strtotime('-1 hours')));
	header('Content-type: application/json');
	echo json_encode($queryVars);
?>