<?php // custom functions.php template @ digwp.com

show_admin_bar(false);

add_theme_support( 'post-thumbnails' );
add_image_size( 'excerpt-thumb', 559, 330);

register_nav_menus( array(
	'departamentos' => 'Departamentos',
	'links' => 'Links Úteis'
) );

// add feed links to header
if (function_exists('automatic_feed_links')) {
	automatic_feed_links();
} else {
	return;
}


// smart jquery inclusion
if (!is_admin()) {
	wp_deregister_script('jquery');
	//wp_register_script('jquery', ("http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"), false, '1.3.2');
	//wp_enqueue_script('jquery');
}


// enable threaded comments
function enable_threaded_comments(){
	if (!is_admin()) {
		if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1))
			wp_enqueue_script('comment-reply');
		}
}
add_action('get_header', 'enable_threaded_comments');


// remove junk from head
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wp_generator');
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'index_rel_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'start_post_rel_link', 10, 0);
remove_action('wp_head', 'parent_post_rel_link', 10, 0);
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0);


/******************************************************************************
* @Author: Boutros AbiChedid 
* @Date:   June 20, 2011
* @Websites: http://bacsoftwareconsulting.com/ ; http://blueoliveonline.com/
* @Description: Preserves HTML formating to the automatically generated Excerpt.
* Also Code modifies the default excerpt_length and excerpt_more filters.
* @Tested: Up to WordPress version 3.1.3
*******************************************************************************/
function setcom_wp_trim_excerpt($text) {
	$raw_excerpt = $text;
	if ( '' == $text ) {
	    //Retrieve the post content. 
	    $text = get_the_content('');
	 
	    //Delete all shortcode tags from the content. 
	    $text = strip_shortcodes( $text );
	 
	    $text = apply_filters('the_content', $text);
	    $text = str_replace(']]>', ']]&gt;', $text);
	     
	    $allowed_tags = '<a>,<p>,<em>,<strong>'; /*** MODIFY THIS. Add the allowed HTML tags separated by a comma.***/
	    $text = strip_tags($text, $allowed_tags);
	     
	    $excerpt_word_count = 50; /*** MODIFY THIS. change the excerpt word count to any integer you like.***/
	    $excerpt_length = apply_filters('excerpt_length', $excerpt_word_count); 
	     
	    $excerpt_end = '[...]'; /*** MODIFY THIS. change the excerpt endind to something else.***/
	    $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);
	     
	    $words = preg_split("/[\n\r\t ]+/", $text, $excerpt_length + 1, PREG_SPLIT_NO_EMPTY);
	    if ( count($words) > $excerpt_length ) {
	        array_pop($words);
	        $text = implode(' ', $words);
	        $text = $text . $excerpt_more;
	    } else {
	        $text = implode(' ', $words);
	    }
	}
	return apply_filters('wp_trim_excerpt', $text, $raw_excerpt);
}
remove_filter('get_the_excerpt', 'wp_trim_excerpt');
add_filter('get_the_excerpt', 'setcom_wp_trim_excerpt', 5);

// custom excerpt length
function custom_excerpt_length($length) {
	return 50;
}
add_filter('excerpt_length', 'custom_excerpt_length');

// no more jumping for read more link
function no_more_jumping($post) {
	return ' </a><span>[...]</span>';
}
add_filter('excerpt_more', 'no_more_jumping');

// add a favicon for your admin
function admin_favicon() {
	echo '<link rel="Shortcut Icon" type="image/x-icon" href="'.get_bloginfo('stylesheet_directory').'/images/favicon.png" />';
}
add_action('admin_head', 'admin_favicon');


// custom admin login logo
function custom_login_logo() {
	echo '<style type="text/css">
	h1 a { background-image: url('.get_bloginfo('template_directory').'/images/custom-login-logo.png) !important; }
	</style>';
}
add_action('login_head', 'custom_login_logo');


// disable all widget areas
function disable_all_widgets($sidebars_widgets) {
	//if (is_home())
		$sidebars_widgets = array(false);
	return $sidebars_widgets;
}
add_filter('sidebars_widgets', 'disable_all_widgets');


// kill the admin nag
if (!current_user_can('edit_users')) {
	add_action('init', create_function('$a', "remove_action('init', 'wp_version_check');"), 2);
	add_filter('pre_option_update_core', create_function('$a', "return null;"));
}

// category id in body and post class
function category_id_class($classes) {
	global $post;
	foreach((get_the_category($post->ID)) as $category)
		$classes [] = 'cat-' . $category->cat_ID . '-id';
		return $classes;
}
add_filter('post_class', 'category_id_class');
add_filter('body_class', 'category_id_class');

// get the first category id
function get_first_category_ID() {
	$category = get_the_category();
	return $category[0]->cat_ID;
}

function url($echo = true){
	if (!$echo) {
		return get_bloginfo('template_directory');
	}
	echo get_bloginfo('template_directory');
}

// add galery rel
function custom_gallery_image($html, $id, $caption){
	$rel = "lightbox[gal] ";
	$customLink = preg_replace("/(rel=\")/", "$1$rel", $html);
	$url = wp_get_attachment_image_src($id, 'large');
	$url = $url[0];
	//(href=\")([\w\:\/\.\-]*)(\")
	$customLink = preg_replace("/(href=\")([\w:\/\?=]*)/", "$1$url", $customLink);
	$customLink = preg_replace("/(<a)/", "$1 class='imgAnchor' title=\"$caption\" ", $customLink);
	return $customLink;
}
//add_filter( "image_send_to_editor", "custom_gallery_image", $priority = 10, $accepted_args = 10 );

// ====================================================
// add rel lightbox

add_filter('the_content', 'my_addlightboxrel');
function my_addlightboxrel($content) {
       global $post;
       $pattern ="/<a(.*?)href=('|\")(.*?).(bmp|gif|jpeg|jpg|png)('|\")(.*?)>/i";
       $replacement = '<a$1href=$2$3.$4$5 rel="lightbox[gal]" class="imgAnchor" title="'.$post->post_title.'"$6>';
       $content = preg_replace($pattern, $replacement, $content);
       return $content;
}

	// comment template
	function mytheme_comment($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		extract($args, EXTR_SKIP);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
		<?php if ( 'div' != $args['style'] ) : ?>
		<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>
		<div class="comment-author vcard">
			<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			<?php printf(__('<cite class="fn">%s</cite>'), get_comment_author_link()) ?>
			<div class="date"><a id="comment-<?php echo $comment->comment_ID; ?>" class="commentLink" href="javascript:void(0);">
				<?php
				/* translators: 1: date, 2: time */
				$date = preg_replace("/^([0-9]+)/", "<strong>$1</strong>", get_comment_date());
				printf( __('%1$s at %2$s'), $date,  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
				?>
			</div>
		</div>
		<?php if ($comment->comment_approved == '0') : ?>
		<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
		<br />
		<?php endif; ?>


		<?php echo "<p class='commentText'>".get_comment_text()."</p>"; ?>

		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
		</div>
		<?php if ( 'div' != $args['style'] ) : ?>
		</div>
		<?php endif; ?>
		<?php
	}

	// custom comment form function
	function setcom_comment_form( $args = array(), $post_id = null ) {
	        global $id;
	
	        if ( null === $post_id )
	                $post_id = $id;
	        else
	                $id = $post_id;
	
	        $commenter = wp_get_current_commenter();
	        $user = wp_get_current_user();
	        $user_identity = $user->exists() ? $user->display_name : '';
	
	        $req = get_option( 'require_name_email' );
	        $aria_req = ( $req ? " aria-required='true'" : '' );
	        $fields =  array(
	                'author' => '<p class="comment-form-author">' . '<label for="author">' . __( 'Name' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
	                            '<input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30"' . $aria_req . ' /></p>',
	                'email'  => '<p class="comment-form-email"><label for="email">' . __( 'Email' ) . ( $req ? ' <span class="required">*</span>' : '' ) . '</label> ' .
	                            '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30"' . $aria_req . ' /></p>',
	                'url'    => '<p class="comment-form-url"><label for="url">' . __( 'Website' ) . '</label>' .
	                            '<input id="url" name="url" type="text" value="' . esc_attr( $commenter['comment_author_url'] ) . '" size="30" /></p>',
	        );
	
	        $required_text = sprintf( ' ' . __('Required fields are marked %s'), '<span class="required">*</span>' );
	        $defaults = array(
	                'fields'               => apply_filters( 'comment_form_default_fields', $fields ),
	                'comment_field'        => '<p class="comment-form-comment"><label for="comment">' . _x( 'Comment', 'noun' ) . '</label><textarea id="comment" name="comment" cols="45" rows="8" aria-required="true"></textarea></p>',
	                'must_log_in'          => '<p class="must-log-in">' . sprintf( __( 'You must be <a href="%s">logged in</a> to post a comment.' ), wp_login_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
	                'logged_in_as'         => '<p class="logged-in-as">' . sprintf( __( 'Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">Log out?</a>' ), get_edit_user_link(), $user_identity, wp_logout_url( apply_filters( 'the_permalink', get_permalink( $post_id ) ) ) ) . '</p>',
	                'comment_notes_before' => '<p class="comment-notes">' . __( 'Your email address will not be published.' ) . ( $req ? $required_text : '' ) . '</p>',
	                'comment_notes_after'  => '<p class="form-allowed-tags">' . sprintf( __( 'You may use these <abbr title="HyperText Markup Language">HTML</abbr> tags and attributes: %s' ), ' <code>' . allowed_tags() . '</code>' ) . '</p>',
	                'id_form'              => 'commentform',
	                'id_submit'            => 'submit',
	                'title_reply'          => __( 'Leave a Reply' ),
	                'title_reply_to'       => __( 'Leave a Reply to %s' ),
	                'cancel_reply_link'    => __( 'Cancel reply' ),
	                'label_submit'         => __( 'Post Comment' ),
	        );
	
	        $args = wp_parse_args( $args, apply_filters( 'comment_form_defaults', $defaults ) );
	
	        ?>
	                <?php if ( comments_open( $post_id ) ) : ?>
	                        <?php do_action( 'comment_form_before' ); ?>
	                        <div id="respond">
	                                <h3 id="reply-title"><?php comment_form_title( $args['title_reply'], $args['title_reply_to'] ); ?></h3>
	                                <?php if ( get_option( 'comment_registration' ) && !is_user_logged_in() ) : ?>
	                                        <?php echo $args['must_log_in']; ?>
	                                        <?php do_action( 'comment_form_must_log_in_after' ); ?>
	                                <?php else : ?>
	                                        <form action="<?php echo site_url( '/wp-comments-post.php' ); ?>" method="post" id="<?php echo esc_attr( $args['id_form'] ); ?>">
	                                                <?php do_action( 'comment_form_top' ); ?>
	                                                <?php if ( is_user_logged_in() ) : ?>
	                                                		<?php 	$args['logged_in_as'] = '<p class="logged-in-as">' . sprintf( __( 'Conectado como <a href="%1$s">%2$s</a>. <a id="commentLogout" href="%3$s" title="Desconectar">Desconectar?</a>' ), admin_url( 'profile.php' ), $user_identity, wp_logout_url( get_permalink() ) ) . '</p>';
	                                                        		echo apply_filters( 'comment_form_logged_in', $args['logged_in_as'], $commenter, $user_identity ); ?>
	                                                        <?php do_action( 'comment_form_logged_in_after', $commenter, $user_identity ); ?>
	                                                <?php else : ?>
	                                                        <?php echo $args['comment_notes_before']; ?>
	                                                        <?php
	                                                        do_action( 'comment_form_before_fields' );
	                                                        foreach ( (array) $args['fields'] as $name => $field ) {
	                                                                echo apply_filters( "comment_form_field_{$name}", $field ) . "\n";
	                                                        }
	                                                        do_action( 'comment_form_after_fields' );
	                                                        ?>
	                                                <?php endif; ?>
	                                                <?php echo apply_filters( 'comment_form_field_comment', $args['comment_field'] ); ?>
	                                                <?php echo $args['comment_notes_after']; ?>
	                                                <p class="form-submit">
	                                                        <input name="submit" type="submit" id="<?php echo esc_attr( $args['id_submit'] ); ?>" value="<?php echo esc_attr( $args['label_submit'] ); ?>" />
	                                                        <?php cancel_comment_reply_link( $args['cancel_reply_link'] ); ?>
	                                                        <?php comment_id_fields( $post_id ); ?>
	                                                </p>
	                                                <?php do_action( 'comment_form', $post_id ); ?>
	                                        </form>
	                                <?php endif; ?>
	                        </div><!-- #respond -->
	                        <?php do_action( 'comment_form_after' ); ?>
	                <?php else : ?>
	                        <?php do_action( 'comment_form_comments_closed' ); ?>
	                <?php endif; ?>
	        <?php
	}

	/**
	 * Provides a simple login form for use anywhere within WordPress. By default, it echoes
	 * the HTML immediately. Pass array('echo'=>false) to return the string instead.
	 *
	 * @since 3.0.0
	 * @param array $args Configuration options to modify the form output.
	 * @return string|null String when retrieving, null when displaying.
	 */
	function setcom_login_form( $args = array() ) {
	        $defaults = array( 'echo' => true,
	                            'redirect' => ( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'], // Default redirect is back to the current page
	                            'form_id' => 'loginform',
	                            'label_username' => __( 'Username' ),
	                            'label_password' => __( 'Password' ),
	                            'label_remember' => __( 'Remember Me' ),
	                            'label_log_in' => __( 'Log In' ),
	                            'id_username' => 'user_login',
	                            'id_password' => 'user_pass',
	                            'id_remember' => 'rememberme',
	                            'id_submit' => 'wp-submit',
	                            'remember' => true,
	                            'value_username' => '',
	                            'value_remember' => false, // Set this to true to default the "Remember me" checkbox to checked
	                                        );
	        $args = wp_parse_args( $args, apply_filters( 'login_form_defaults', $defaults ) );
	
	        $form = '
	                <form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php', 'login_post' ) ) . '" method="post">
	                        ' . apply_filters( 'login_form_top', '', $args ) . '
	                        <p class="login-username">
	                                <label for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
	                                <input type="text" name="log" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
	                        </p>
	                        <p class="login-password">
	                                <label for="' . esc_attr( $args['id_password'] ) . '">' . esc_html( $args['label_password'] ) . '</label>
	                                <input type="password" name="pwd" id="' . esc_attr( $args['id_password'] ) . '" class="input" value="" size="20" />
	                        </p>
	                        ' . apply_filters( 'login_form_middle', '', $args ) . '
	                        <p class="login-submit">
	                                <input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button-primary" value="' . esc_attr( $args['label_log_in'] ) . '" />
	                                <input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
	                        </p>
	                        ' . ( $args['remember'] ? '<p class="login-remember"><label><input name="rememberme" type="checkbox" id="' . esc_attr( $args['id_remember'] ) . '" value="forever"' . ( $args['value_remember'] ? ' checked="checked"' : '' ) . ' /> ' . esc_html( $args['label_remember'] ) . '</label></p>' : '' ) . '
	                        ' . apply_filters( 'login_form_bottom', '', $args ) . '
	                </form>';
	
	        if ( $args['echo'] )
	                echo $form;
	        else
	                return $form;
	}

	// ======================================
	// Include private posts in search query
	function include_private_posts_in_search( $query ) {
		if ( is_search() && is_user_logged_in() )
			$query->set( 'post_status', array ( 'publish', 'private' ) );
	}
	add_action( 'pre_get_posts', 'include_private_posts_in_search' );

	// =============================================
	// get xml
	function getFeed($feed_url) {
		$content = file_get_contents($feed_url);
		$x = new SimpleXmlElement($content);
		echo "<ul class='list'>";
		foreach($x->channel->item as $entry) {
			// echo "<pre>";
			// var_dump($entry);
			// echo "</pre>";
			echo "<li><a href='$entry->link' title='$entry->title'>" . $entry->title . "</a></li>";
		}
		echo "</ul>";
	}

	// =====================================================
	// custom format get_the_date()
	function setcom_get_the_date($the_date) {
		$date = preg_replace("/^([0-9]+)/", "<strong>$1</strong>", $the_date);
		return $date;
	}
	add_filter( "get_the_date", "setcom_get_the_date", $priority = 10, $accepted_args = 2 );

	// ======================================================
	// playing with main query
	function remove_main_query($query){
		if($query->is_home() && $query->is_main_query()):
			echo '<pre>';
			var_dump($query);
			echo '</pre>';
		endif;
	}
	//add_action( 'pre_get_posts', 'remove_main_query', $priority = 10, $accepted_args = 5 )

?>