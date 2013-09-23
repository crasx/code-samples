<?php
require(CLASSES."database.php");
require(CLASSES."main.class.php");
require(CLASSES."configure.class.php");
require(CLASSES."smarty/Smarty.class.php");

$uploaddir=$_SERVER['DOCUMENT_ROOT'].BASE."/uploads";//relative to httproot
$styleuploaddir=$_SERVER['DOCUMENT_ROOT'].BASE."/img";//relative to httproot
$sqltableprefix="";//prefix to put before each table ie if it is atr_ then the art table will be atr_art
$viewPerPage=50;//number of people to view per page


$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");


define("MAX_FILESIZE",5);
define("MAX_VIEW",$viewPerPage);
define("UPLOAD_DIR",$uploaddir);
define("STYLE_UPLOAD_DIR",$styleuploaddir);
define("SQL_PREFIX", $sqlprefix);

require_once(CLASSES."declaretables.php");


$db=new Database($sqlserver,$sqluser,$sqlpassword, $sqldb);
$db->Connect();
$config=new configure;

$config->checkCSS();

define ("IPHONE", $config->showIphone());
define("PUB", $config->hasPublic());
define("RSS", $config->hasRss());

$smarty=new smarty;
$smarty->left_delimiter = '{{';
$smarty->right_delimiter = '}}';
$smarty->debugging=false;
$smarty->template_dir = SCRIPT_ROOT.'templates';
$smarty->compile_dir = SCRIPT_ROOT.'templates_c';
$smarty->cache_dir = SCRIPT_ROOT.'cache';
$smarty->config_dir = SCRIPT_ROOT.'configs';
$smarty->assign("competitionTitle", $config->getInfo("name", "Competition"));
if(strcmp(strtolower(BASE), "/demo")==0){
$smarty->assign("demo", 1);
}

require(CLASSES."upgrade.php");
if(defined('INRSS')){	
if(!$config->hasRss()){
	$smarty->display('lockout.tpl');
	exit;
	
}

}elseif(defined('INPUBLIC')){
if(!$config->hasPublic()){
	$smarty->display('lockout.tpl');
	exit;
	
}
	$comps=$db->fetch_all_array("select c.* from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.public=1 or (g.public=1 and c.public=-1) order by c.`order`");
		$smarty->assign("competitions",$comps);

}elseif(JUSTCSS!=1){


require(CLASSES."user.class.php");

$user=new user();
$user->doLogin();

$smarty->assign("user",$user);

$smarty->assign("user_attempted",$_SESSION['attempt']);

if($user->isJudge()){
	global $comps;
	$comps=$db->fetch_all_array("select c.* from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.judging=1 or (g.judge=1 and c.judging=-1) order by c.`order`");
		$smarty->assign("competitions",$comps);
}
if($user->invalidCaptcha())
		$smarty->assign("user_loginerror","Invalid captcha");
if($user->attemptedLogin())
		$smarty->assign("user_loginerror","Invalid login");
if($user->requireCaptcha())
		$smarty->assign("user_requirecaptcha", true);
}else{
	require(CLASSES."loader.class.php");
	$loader=new loader;
	$smarty->assign("style", $loader->getCss());

}
?>