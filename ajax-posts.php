<?php 

	/** Sets up the WordPress Environment. */
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
		case 'post':
			$args = array(
					'post_type' => 'post',
					'name' => $page->slug
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

	//echo json_encode($query->get_post());

	$queryVars["numPages"]		=	$query->max_num_pages;
	$queryVars["foundPosts"]	=	$query->found_posts;
	$queryVars["type"]			=	$page->type;
	$queryVars["slug"]			=	$page->slug;
	$queryVars["posts"]			=	array();

	$posts = array();
	while ($query->have_posts()): $query->the_post();
		
        $c = get_the_category();
        $c = array_slice( $c, 0, 5 );
        $cat = $c[0]->cat_name;
        $catLink = $c[0]->slug;

        // if hasThumbnail set img html attribute, if not set to 0
        $thumb = 0;
        if ( has_post_thumbnail() )
        	$thumb = get_the_post_thumbnail( get_the_ID(), 'excerpt-thumb' );

		$p = array(
			'id'		=>	get_the_ID(),
			'title' 	=>	get_the_title(),
			'link'		=>	get_permalink(),
			'slug'		=>	basename(get_permalink()),
			'date'		=>	get_the_date(),
			'thumbnail'	=>	$thumb,
			'cat'		=>	$c,
			'catLink'	=>	$catLink
		);
		if($_GET['page']['type'] != 'post'):
			$p['excerpt']	=	get_the_excerpt();
		else:
			$p['content']	=	apply_filters('the_content', get_the_content());
		endif;
		$queryVars['posts'][] = $p;

	endwhile;
	
	$queryVars = (object) $queryVars;
	

	header("Cache-Control: max-age=".strtotime('-170 minutes'));
    header("Date: " . gmdate('D, d M Y H:i:s', strtotime('-3 hours')));
    header("Expires: " . gmdate('D, d M Y H:i:s', strtotime('-179 minutes')));
	header('Content-type: application/json');
	echo json_encode($queryVars);
?>