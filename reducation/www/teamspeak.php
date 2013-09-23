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

    page_header('Teamspeak');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
?>

	<h2><a href='http://www.teamspeak.com/?page=downloads '>Download TeamSpeak 2 Client here</a></h2><br />
	Teamspeak offers multiple quality settings. Higher settings require higher bandwidth, play around with different settings to find what suits you best.<br />
<br />
Join the server by entering reclan.com into the server address!<br /><br />
<br />

<div id=teamspeak >
<?php

	require_once("c:/root/reclan/cache/cache.inc.php");
	$cacheF=new cacheFast();
	echo $cacheF->load("ts");
?>
</div>
<?php


$template->assign_vars(array(
	'HEADERTEXT'		=> "Teamspeak",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
	?> 