<?php 
	$loadFile = "../../../wp-load.php";
	if (file_exists($loadFile))
	    require_once($loadFile);

	import_request_variables('gp');

	$comments = get_comments( array(
			'status'	=>	'approve',
			'post_id'	=>	$postID
		) );

    //if has comments
    echo "<hr>";
    if(sizeof($comments) > 0):
        echo "<h3>Comentários</h3>";
        echo "<ul class='commentlist'>";
        //Display the list of comments
        wp_list_comments(array(
            'per_page' => 10, //Allow comment pagination
            'reverse_top_level' => false, //Show the latest comments at the top of the list
            'type' => 'comment',
            'callback' => 'mytheme_comment',
            'avatar_size' => 44
        ), $comments);
        echo "</ul>";
    endif;
    setcom_comment_form(array(
	    'title_reply' => 'Deixe um comentário',
	    'title_reply_to' => 'Deixe uma resposta'
    ), $postID);

	/*
	$data = array();

	$data["numPages"]		= $query->max_num_pages;
	$data["foundPosts"]		= $query->found_posts;
	$data["comments"]		= $comments;


	foreach ($data["comments"] as $c) {
		$c->avatar = get_avatar( $c->comment_author_email, $size = '44', $default = '', $alt = false );
	}

	header('Content-type: application/json');
	echo json_encode($data);
	*/
?>