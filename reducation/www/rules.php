<?php
    define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Rules');

    $template->set_filenames(array(
        'body' => 'main/rules.html',
    ));

    make_jumpbox(append_sid("{$phpbb_root_path}viewforum.$phpEx"));
    page_footer();
?>