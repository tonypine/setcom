<?php 

	$loadFile = "../../../wp-load.php";
	if (file_exists($loadFile))
	    require_once($loadFile);

	import_request_variables('gp');
	$page = (object) $page;
	$queryVars = array();

	switch ($page->type) {
		case 'busca':
			$args = array(
					'post_type' => 'post',
					's' => $page->slug
				);
			$queryVars['s'] = $page->slug;
			break;
		case 'categoria':
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

	$queryVars["numPages"]		=	$query->max_num_pages;
	$queryVars["foundPosts"]	=	$query->found_posts;
	$queryVars["posts"]			=	array();

	$posts = array();
	while ($query->have_posts()): $query->the_post();
		
        $c = get_the_category();
        $cat = $c[0]->cat_name;
        $catLink = $c[0]->slug;

        // if hasThumbnail set img html attribute, if not set to 0
        $thumb = 0;
        if ( has_post_thumbnail() )
        	$thumb = get_the_post_thumbnail( get_the_ID(), 'excerpt-thumb' );

		$p = array(
			'title' 	=>	get_the_title(),
			'link'		=>	get_permalink(),
			'excerpt'	=>	get_the_excerpt(),
			'date'		=>	get_the_date(),
			'thumbnail'	=>	$thumb,
			'cat'		=>	$cat,
			'catLink'	=>	$catLink
		);
		$queryVars['posts'][] = $p;

	endwhile;
	
	$queryVars = (object) $queryVars;

	header("Cache-Control: max-age=".strtotime('-1 hours'));
    header("Expires: " . gmdate('D, d M Y H:i:s', strtotime('-1 hours')));
	header('Content-type: application/json');
	echo json_encode($queryVars);
?>