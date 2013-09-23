<?php
if(isset($_GET['sid']))
session_start($_GET['sid']);
else session_start();
require_once("adm/cfg.php");
$userInfo=array("login"=>"","password"=>"","name"=>"","id"=>-1,"valid"=>false,"attempt"=>0,"admin"=>false,"mc"=>false,"judge"=>false, "register"=>false, "sid"=>session_id());
if(isset($_POST['loginusername'])){
		setcookie("user", $_POST['loginusername']);
		$userInfo['login']=$_POST['loginusername'];
		setcookie("sid",md5($_POST['loginpassword']));
		$userInfo['attempt']=1;
		$userInfo['password']=md5($_POST['loginpassword']);
}else {
		$userInfo['login']=$_COOKIE['user'];
		$userInfo['password']=$_COOKIE['sid'];
	
}
if(isset($userInfo['login'])){
global $aesCrypt;
$rw=mysql_query("select password, username, name, id, privs from ".T_USERS." where lower(username)='".strtolower(addslashes($userInfo['login']))."'");
if(mysql_num_rows($rw)>0)if($r=mysql_fetch_array($rw)){
if($r['password']==$userInfo['password']){
$userInfo['valid']=true;
$userInfo['name']=$r['name'];
$userInfo['id']=$r['id'];
$prv=$r['privs'];
if($prv>=8){
 $userInfo['mc']=true;
 $prv-=8;
}
if($prv>=4){
 $userInfo['judge']=true;
 $prv-=4;
}
if($prv>=2){
 $userInfo['register']=true;
 $prv-=2;
}
if($prv==1){
 $userInfo['admin']=true;
}
}else {
$userInfo['valid']=false;
$userInfo['attempt']=1;
}
}else $userInfo['valid']=false;
else $userInfo['valid']=false;
}else $userInfo['valid']=false;
global $userInfo;
?>