<?php
require '../haiku_script/classes/config.php';

$usr=$_GET['user'];
$userL=$loader->loadUser($usr);
if(sizeof($userL)!=1){
	header("HTTP/1.1 404 Not Found"); 	
	header("Location: /404error.php"); /* Redirect browser */
	return;
}
$uid=$userL[0]['id'];

$page=isset($_GET['page'])?$_GET['page']:1;

$smarty->assign("title", " | User | $usr");

$smarty->assign("category",-2);

$smarty->assign("uinfo",$userL[0]);

$smarty->assign("poems",$loader->loadUserPoems($uid), ($page-1)*DEFAULTCOUNT);

$ps=$loader->loadUserPoemCount($uid);

$pgs=ceil($ps[0]['count']/DEFAULTCOUNT);
if($pgs==0)$pgs=1;
$smarty->assign("pages", $loader->makePageArray("/user/$usr?page=", $pgs, $pg));

$smarty->display('user.tpl');

?>
