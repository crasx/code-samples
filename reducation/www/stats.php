<?php
ob_start();

define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Player history');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
	
	
	$cacheF=new cacheFast();
	echo  "<table cellpadding=5px ><tr><td>".$cacheF->load("gt")."</td><td>";
	echo  $cacheF->load("gt2")."</td></tr><tr><td colspan=2 >";
	echo  $cacheF->load("gt3")."</td></tr></table>";
	
$template->assign_vars(array(
	'HEADERTEXT'		=> "Stats",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?>