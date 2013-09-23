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

    page_header('Request score transfer');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
	
		if(!empty($user->data['is_registered'])&&empty($user->data['is_bot'])){
		global $db;
		$appV = $db->sql_query("select n1.name, n2.name, n.status, n.id from halo_phpbb.name_request n, halo_leaderboard.names n1, alo_leaderboard.names n2  where n1.id=n.from and n2.id=n.to n.account=".$user->data['user_id']);
		echo "<table broder=1><tr><td>From
		while($r = $db->sql_fetchrow($appV))
		{
			echo "
			
		}
	}

$template->assign_vars(array(
	'HEADERTEXT'		=> "Apply",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?>
