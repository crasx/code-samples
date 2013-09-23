<?php

//////////////////
//////////////////
define("PREFIX", $tableprefix);
define("CRYPTKEY", "5[$/2Ys");
define ("DEFAULTCOUNT", 25);
if(!defined('PATHTOROOT'))
define('PATHTOROOT',$_SERVER['DOCUMENT_ROOT']."/../haiku_script/");
//////////////////
//////////////////
require(PATHTOROOT."classes/database.class.php");
require(PATHTOROOT."classes/definetables.php");
require(PATHTOROOT."classes/user.class.php");
require(PATHTOROOT."classes/submit.class.php");
require(PATHTOROOT."classes/mail.php");
require(PATHTOROOT."classes/loader.class.php");

require("c:\\root\\recaptcha\\recaptchalib.php");

require PATHTOROOT.'classes/smarty/Smarty.class.php';


$smarty = new Smarty;

$smarty->left_delimiter = '{{{';
$smarty->right_delimiter = '}}}';
$smarty->debugging=false;
$smarty->template_dir = PATHTOROOT.'templates';
$smarty->compile_dir = PATHTOROOT.'templates_c';
$smarty->cache_dir = PATHTOROOT.'cache';
$smarty->config_dir = PATHTOROOT.'configs';

$db=new Database($server,$user,$pass, $db);
$db->Connect();

$user=new user();
$user->doLogin();

$submit=new submit();

$loader=new loader();
$smarty->assign("categories",$loader->loadCategories());
$smarty->assign("category",-1);
$smarty->assign("user",$user);
$smarty->assign("username_url",urlencode($user->getInfo("username")));
$smarty->assign("user_attempted",$_SESSION['attempt']);
if($user->invalidCaptcha())
		$smarty->assign("user_loginerror","Invalid captcha");
if($user->attemptedLogin())
		$smarty->assign("user_loginerror","Invalid login");



?>