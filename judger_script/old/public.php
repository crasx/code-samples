<?php
require("adm/cfg.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="stylesheet" href="<?php if(IPHONE)echo "css/iphone.php"; else echo "css/css.php";?>" />
<link rel="apple-touch-icon" href="img/iphone.png"/>
<?php echo "<script type='text/javascript' src='".(IPHONE?"js/publiciphone.js":"js/public.js")."' ></script>"; ?>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;"/>
<title>Paradise tattoo competition</title>
</head>
<body <?php if(IPHONE) echo "onload=crefresh()";?> >
<table width="100%" >
<?php if(!IPHONE)echo '<tr><td>&nbsp;</td><td  align="center" ><img src="img/logo.gif" /></td></tr>'; 
else echo "<form name=refreshForm ><input type=hidden value='' name='check' /></form>";?>
<?PHP 
$public=true;
global $userInfo;
$body="";
		if(!IPHONE){
		echo "<tr><td valign=top ' id=menu background='img/trans.png' >";
		include('public-ajax-php-menu.php');
		echo "<br /></td><td align=center valign=top background='img/trans.png' ><div id='page'>";
	
		include('public-ajax-php.php');
		// please dont remove this...
		echo "</div></td></tr><tr><td align=center colspan=2 >&copy;<a href='http:/.crasxit.net'>Matthew Ramir</a> for use in <a href='Http://hellcity.com'>HellCity tattoo fest.</a></td></tr>";
		}else{
			//echo "<tr><td class='mtop'>HellCity Tattoo Fest</td></tr><tr><td valign=top ' id=page >";
			echo "<tr><td valign=top ' id=page >";
			include('public-ajax-php-menu.php');
			echo "</td></tr>";
		}
	
?>
</table>
</body>
