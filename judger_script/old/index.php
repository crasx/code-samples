<?php
if(is_dir("install")){
//header("Location: install/");
//exit;
}
if(isset($_GET['logout'])){
		setcookie("user", '', time()-3600);
		setcookie("sid",'', time()-3600);
		
}require("login.php");?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="<?php if(IPHONE)echo "css/iphone.php"; else echo "css/css.php";?>" />
<link rel="apple-touch-icon" href="img/iphone.png"/>
<?php echo ($userInfo['valid']?"<script type='text/javascript' src='".(IPHONE?"js/iphone.js":"js/ajax.js")."' ></script>":""); ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<title>Paradise tattoo competition</title>
</head>
<body <?php if(IPHONE) echo "onload=crefresh()";?> >
<table width="100%" >
<?php 
$NOCFG=1;
require_once("css/getcss.php");
if(!IPHONE)echo '<tr><td>&nbsp;</td><td  align="center" ><img src="'.$styles['css']['logo']['file'].'" /></td></tr>'; 
else echo "<form name=refreshForm ><input type=hidden value='' name='check' /></form>";?>
<?PHP 

global $userInfo;
$body="";
	if($userInfo['valid']){	
		if(!IPHONE){
		echo "<tr><td valign=top ' id=menu  >";
		include('ajax-php-menu.php');
		echo "<br /></td><td align=center valign=top  ><div id='page'>";
	
		include('ajax-php.php');
		// please dont remove this...
		echo "</div></td></tr><tr><td align=center colspan=2 >&copy;<a href='http:/.crasxit.net'>Matthew Ramir</a> for use in <a href='Http://hellcity.com'>HellCity tattoo fest.</a></td></tr>";
		}else{
			//echo "<tr><td class='mtop'>HellCity Tattoo Fest</td></tr><tr><td valign=top ' id=page >";
			echo "<tr><td valign=top ' id=page >";
			include('ajax-php-menu.php');
			echo "</td></tr>";
		}
	}
	else echo "<tr><td colspan=2 align=center ><table>".($userInfo['attempt']?"<tr><td colspan=2 alihn=right >Invalid login.</td></tr>":"")."<tr><td><form action=index.php method=post >Username:</td><td><input type=text name=loginusername ></td></tr><tr><td>
Password:</td><td><input type=password name=loginpassword></td></tr><tr><td colspan=2 align=center  >
<input type=submit value=Login ></form></td></tr><tr><td colspan=2 ><h3><a href='public.php'>No login?</a></h3></td></tr></table></td></tr>";

?>
</table>
</body>
