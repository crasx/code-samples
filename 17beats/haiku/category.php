<?php
require '../haiku_script/classes/config.php';
$cat=strtolower($_GET['category']);
$catUp=$_GET['category'];
$categories=$loader->loadCategories();
$catid=-1;
foreach($categories as $c){
	if(strtolower($c['name'])==$cat){
		$catid=$c['id'];
		$cat=$c['name'];
		break;
	}
}
if($catid==-1){
	header("HTTP/1.1 404 Not Found"); 	
	header("Location: /404error.php"); /* Redirect browser */

	return;
}

$page=isset($_GET['page'])?$_GET['page']:1;

$smarty->assign("title", " | Category | $catUp");
$smarty->assign("category",$catid);

$smarty->assign("categoryname",$catUp);
$smarty->assign("poems",$loader->loadCategoryPoems($catid), ($page-1)*DEFAULTCOUNT);
$ps=$loader->loadCategoryPoemCount($catid);
$pgs=ceil($ps[0]['count']/DEFAULTCOUNT);
if($pgs==0)$pgs=1;
$smarty->assign("pages", $loader->makePageArray("/category/$cat?page=", $pgs, $pg));
$smarty->display('category.tpl');

?>
