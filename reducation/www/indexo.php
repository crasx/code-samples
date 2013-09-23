<?php
    define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Home');
	
	/*
	SELECT po.post_text, po.topic_id, po.post_subject, po.post_id, u.username, po.topic_id, po.post_time, (select count(*)-1 from posts pi where pi.topic_id=po.topic_id) comments
FROM posts po, users u
where u.user_id = po.poster_id
and po.forum_id=34
group by po.topic_id
ORDER BY po.post_time DESC
*/

    $template->set_filenames(array(
        'body' => 'main/index.html',
    ));

    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>