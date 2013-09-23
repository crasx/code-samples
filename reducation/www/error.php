<?php
$err="";
    if(isset($_GET['e'])){
	switch($_GET['e']){
	case 401:$err= "<h1>401: Unauthorized</h1>";break;
		case 403:$err= "<h1>403: Forbidden</h1>";break;
		case 404:$err= "<h1>404: Not found</h1>";break;
		case 400:$err= "<h1>400: Bad request</h1>";break;
	
	}
	}
	    define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Oh crap');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
$template->assign_vars(array(
	'HEADERTEXT'		=> $err,
	'OUTPUT'				=> "oh, snap...",
));


    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
	

?>
