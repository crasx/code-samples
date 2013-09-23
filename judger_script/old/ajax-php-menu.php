<?php
require_once("login.php");
$NOCFG=1;
require_once("css/getcss.php");
global $userInfo;
if($userInfo['valid']){
	if(!IPHONE){
		if($userInfo['admin']){
			$menu.="Admin controls<ul>";
			//>
			$menu.= "<li><div onclick='javascript:getHttp(\"ajax-php.php?page=config\", \"page\");' class=menuadiv ><div class=aholder >Configuration</div></div></li>";
			
			$menu.= "<li><div onclick='javascript:getHttp(\"ajax-php.php?page=admin&do=edc\", \"page\");loadcal();' class=menuadiv ><div class=aholder >Edit competitions</div></div></li>";
			
			$menu.= "<li><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&userR=0\", \"page\")'><div class=aholder >Edit users</div></div></li>";

			$menu.= "<li><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=backup\", \"page\")'><div class=aholder >Backup/ Restore</div></div></li>";
			
			$menu.= "<li><div class=menuadiv onclick='javascript:createIframe2(\"skinner.php?page=style\", \"page\")'><div class=aholder >Edit style</div></div></li>";
			$menu.="</ul>";

		}
		if($userInfo['register']){		
			$menu.="Registration<ul>";
			$menu.= "<li><div class=menuadiv onclick='javascript:createIframe(\"registration.php?do=ec\")'><div class=aholder >Edit contestants</div></div></li></ul>";

		}
		if($userInfo['mc']){		
			$menu.="MC<ul>";
			$menu.= "<li><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=list\", \"page\")'><div class=aholder >List competitions</div></div></li>";
			$menu.= "<li><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=res\", \"page\")'><div class=aholder >Results</div></li></div>";
			$menu.="<li><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=report\", \"page\")'><div class=aholder >Report</div></div></li></ul>";
		}
		if($userInfo['judge']){
			$menu.="Judge<br /><ul>";
			$arr=mysql_query("Select id,  name  from ".T_COMPS." where day=curdate() and enabled=1 and finished=0");
			echo mysql_error();
			$co=0;
			if($arr)while($a=mysql_fetch_array($arr)){
				$menu.="<li><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=judge&c=".$a['id']."\", \"page\");loadslider();'><div class=aholder ><div class='menuitem' >".dsql($a['name'])."</div></div></div></li>";
				$co=1;
			}
			if(!$co)$menu.="<center>No current competitions</center>";
			$menu.="</ul>";
		}		
	}else{//iphone
	$menu.="<ul>";
			if($userInfo['mc']){		
			$menu.="<li class=mhead >MC</li>";
			$menu.= "<li class=mbod ><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=list\", \"page\")'>List competitions</div></li>";
			$menu.= "<li  class=mbod ><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=res\", \"page\")' >Results</div></li>";
			
			$menu.= "<li  class=mbod ><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=resv\", \"page\")' >Results (one page)</div></li>";
			$menu.= "<li  class=mbod ><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=report\", \"page\")' >Report</div></li>";
		}
/*		if($userInfo['judge']){
			$menu.="<li class=mhead >Judge</li>";
			$arr=mysql_query("Select id,  name  from ".T_COMPS." where day=curdate() and enabled=1");
			echo mysql_error();
			$co=0;
			if($arr)while($a=mysql_fetch_array($arr)){
				$menu.="<li  class=mbod ><div class=menuadiv onclick='javascript:getHttp(\"ajax-php.php?page=judge&c=".$a['id']."\", \"page\");loadslider();'><div class=aholder ><div class='menuitem' >".$a['name']."</div></div></div></li>";
				$co=1;
			}
			if(!$co)$menu.="<center>No current competitions</center>";*/
			$menu.="<li class=mnone >Current only supports MC functions on iphone/ipod </li>
			<li class=mbod ></ul>";
	}
}
if(IPHONE)$menu.="<a href='javascript:getHttp(\"ajax-php-menu.php\", \"menu\")'>Refresh</a><br /><a href='index.php?logout=1'>Logout</a>";
echo $menu;
?>