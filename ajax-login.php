<?php 

	require_once( "../../../wp-load.php" );
	if(is_user_logged_in()): 
		wp_logout();
		echo "logged out";
	else:
		$creds = array();
		$creds['user_login'] = 'example';
		$creds['user_password'] = 'plaintextpw';
		$creds['remember'] = true;
		$user = wp_signon( false );
		if ( is_wp_error($user) )
		   echo $user->get_error_message();
		else
			echo "logged in";
	endif;
?>