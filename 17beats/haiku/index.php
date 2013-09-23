<?php
require '../haiku_script/classes/config.php';

if(isset($_GET['random'])){
	$randId=$loader->loadRandom();
	if(sizeof($rand)==1){
		header("location: /poems/".$rand[0]['id']);
		exit;
		return;
	}
}
$page=isset($_GET['page'])?$_GET['page']:1;

$smarty->assign("title", " | Home");
$smarty->assign("category",-1);
$smarty->assign("poems",$loader->loadPoems(($page-1)*DEFAULTCOUNT));
$ps=$loader->loadPoemCount();
$pgs=ceil($ps[0]['count']/DEFAULTCOUNT);
if($pgs==0)$pgs=1;
$smarty->assign("pages", $loader->makePageArray("/index.php?page=", $pgs, $pg));
$smarty->display('index.tpl');

?>
