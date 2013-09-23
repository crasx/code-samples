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

    page_header('Members');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));

$leaders=array(" crasX", " HypnoZ");
$coleaders=array(" Kuks", " V1R5");
$members=array("Ø§¢Å®","¬Jeevo","£ordDino","~rigO'","~Galidor","ejected","Trace360","Ancient","FailMind","08973465"," ¥erõ"," victrix"," ]_[RIEL"," Shike"," Mega"," Lookout"," Duivel"," Death", "kmikasi", "carv", "island", "~ßád~");
sort($members);
echo "<h2>Leaders</h2>";
foreach($leaders as $l){
	echo "[R]".htmlentities($l)."<br />";
}echo "<h2>Co-Leaders</h2>";
foreach($coleaders as $l){
	echo "[R]".htmlentities($l)."<br />";
}echo "<h2>Members</h2>";
foreach($members as $l){
	echo "[R]".htmlentities($l)."<br />";
}

$template->assign_vars(array(
	'HEADERTEXT'		=> "Raven leaders",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();


?>