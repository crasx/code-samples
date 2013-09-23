<?php
require_once("login.php");
if(!$userInfo['valid'])echo "<form action=index.php method=post >Username:<input type=text name=loginusername ><br />
Password:<input type=password name=loginpassword ><br />
<input type=submit value=Login ></form>";
else{
	
		if(!IPHONE){
		if($_GET['page']=="admin"){
			require('adm/functions-admin.php');
		}else
		if($_GET['page']=="style"){
			require('adm/functions-style.php');
		}else
		if($_GET['page']=="config"){
			require('adm/functions-config.php');
		}else
		if($_GET['page']=="backup"){
			require('adm/functions-backup.php');
		}else
		if($_GET['page']=="reg"){
			require('adm/functions-reg.php');
		}else
		if($_GET['page']=="mc"){
			require('adm/functions-mc.php');
		}else
		if($_GET['page']=="judge"){
			require('adm/functions-judge.php');
		}else echo "<h1>Welcome to Paradise</h1><h2>Use the menu to your left to begin.</h2>";
		}else if($_GET['page']=="mc"){
			require('adm/iphone/functions-mc.php');
		}
	if(IPHONE)$body="<h2><a href='javascript:getHttp(\"index.php\",\"page\")'>Menu</a></h2>".$body."<h2><a href='javascript:getHttp(\"index.php\",\"page\")'>Menu</a></h2>";
}
echo $body;
?>