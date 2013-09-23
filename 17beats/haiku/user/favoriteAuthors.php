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

$smarty->assign("title", " | Favorite authors of $usr");

$smarty->assign("category",-2);


$smarty->assign("founduser",$userL[0]['username']);
$smarty->assign("foundid",$userL[0]['id']);


$smarty->assign("poems",$loader->loadFavoriteAuthorsPoems($uid), ($page-1)*DEFAULTCOUNT);

$smarty->assign("authors",$loader->loadFavoriteAuthors($uid));

$ps=$loader->loadFavoriteAuthorsPoemCount($uid);

$pgs=ceil($ps[0]['count']/DEFAULTCOUNT);
if($pgs==0)$pgs=1;
$smarty->assign("pages", $loader->makePageArray("/favorite/authors/$usr?page=", $pgs, $pg));

$smarty->display('favorite_author.tpl');

?>
