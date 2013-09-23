<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';

$usr=$_GET['user'];
$userL=$loader->loadUser($usr);
if(sizeof($userL)!=1){
	header("HTTP/1.1 404 Not Found"); 	
	header("Location: /404error.php"); /* Redirect browser */
	return;
}
$uid=$userL[0]['id'];

$page=isset($_GET['page'])?$_GET['page']:1;

$smarty->assign("title", " | Favorite haiku of $usr");

$smarty->assign("category",-2);


$smarty->assign("founduser",$userL[0]['username']);
$smarty->assign("foundid",$userL[0]['id']);


$smarty->assign("poems",$loader->loadFavoritePoems($uid), ($page-1)*DEFAULTCOUNT);

$ps=$loader->loadFavoritePoemCount($uid);

$pgs=ceil($ps[0]['count']/DEFAULTCOUNT);
if($pgs==0)$pgs=1;
$smarty->assign("pages", $loader->makePageArray("/favorite/haiku/$usr?page=", $pgs, $pg));

$smarty->display('favorite.tpl');

?>
