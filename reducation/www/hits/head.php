<?php 
include("hits/count.php");
$uri="";
if(strpos("AA".$_SERVER['HTTP_HOST'],"fast.crasxit.net")!=false)$uri=$_SERVER['REQUEST_URI'];
else if(strpos("AA".$_SERVER['HTTP_HOST'],"crasxit.net")!=false)$uri=substr($_SERVER['REQUEST_URI'],5);
if($uri!=""){
echo "This page must be viewed from www.fastclan.org, please update your bookmarks.. redirecting in 5 seconds...<br />
if not then click <a href='http://fastclan.org/$uri'>here</a>";
sleep(5);
echo "<script type='text/javascript'>
top.location='http://fastclan.org$uri';
</script>";

}

//print_r($_SERVER);
?><?php
define('IN_PHPBB', true);
$phpbb_root_path = "/home/crasxit0/public_html/fast/forum/";
$phpEx = substr(strrchr(__FILE__, '.'), 1);

     
include($phpbb_root_path . 'common.' . $phpEx);
include($phpbb_root_path . 'includes/functions_display.' . $phpEx);
include($phpbb_root_path . 'includes/bbcode.' . $phpEx);
  $user->session_begin();
    $auth->acl($user->data);
    $user->setup();
	$dbuser = 'crasxit0_halo1';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'crasxit0_haloStats';
if(!($conn = mysql_connect('127.0.0.1', $dbuser, $dbpass))){
echo "Error, can't connect right now because".mysql_error();
exit;
}
mysql_select_db($dbname);

	if(isset($_GET['addfriend'])){
	$f=$_GET['addfriend'];
	if(is_numeric($f)){
	  if($user->data['is_registered']){
	  	if(!$user->data['is_bot']){

$friendadded=false;
if(mysql_query("insert into friends (id, friend) values('".$user->data['user_id']."','".addslashes($f)."')"))
$friendadded=true;
		}
	  }
	}
	}
	else if(isset($_GET['rmfriend'])){
	$f=$_GET['rmfriend'];
if(is_numeric($f)){
	  if($user->data['is_registered']){
	  	if(!$user->data['is_bot']){
		$frienddeleted=false;
		if(mysql_query("delete from friends where id='".$user->data['user_id']."' and friend='".addslashes($f)."' limit 1"))
$frienddeleted=true;
		}
	  }
	}
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-gb" xml:lang="en-gb">
<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<meta http-equiv="content-language" content="en-gb" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="imagetoolbar" content="no" />
<meta name="resource-type" content="document" />
<meta name="distribution" content="global" />
<meta name="copyright" content="2002-2006 phpBB Group" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<title>The [FAST] clan - <?php echo $title; ?></title>

<link rel="stylesheet" href="./forum/styles/acidtechgreen/theme/stylesheet.css" type="text/css" />

<!--[if IE]>
<link rel="stylesheet" type="text/css" href="./forum/styles/acidtechgreen/theme/ie7.css" />
<![endif]-->
<script src="http://fastclan.org/ajax.js" type="text/javascript" >
</script>
</head>
<body class="ltr">

<a name="top"></a>

<table border="0" cellspacing="0" cellpadding="0" width="100%" id="maintable" align="center">
<tr>
	<td id="logorow" align="center">
	
<div id="top_logo" onclick="location.href='index.php';" style="cursor:pointer">
<div style="float:left; color:#0FF; background:#313131 url(images/cell.gif) repeat-x scroll left top;" id=stable1 >
</div>
<div style="float:right; color:#F20; background:#313131 url(images/cell.gif) repeat-x scroll left top;" id=stable2 >
</div>
</div>
</td>
</tr>
<tr>
	<td class="navrow">
	<?php 
		
	  	if(!$user->data['is_bot']){
	 if($user->data['is_registered']){
		 echo "<a href='http://fastclan.org/forum/ucp.php'>User control panel</a> &#8226; ";
		 echo "<a href='http://fastclan.org/forum/ucp.php?i=pm&folder=inbox'>".$user->data['user_new_privmsg']." new message".($user->data['user_new_privmsg']==1?"s":"").", ".$user->data['user_unread_privmsg']." unread message".($user->data['user_unread_privmsg']==1?"s":"")." &#8226; ";
	 }else{
		echo "<a href='http://fastclan.org/forum/ucp.php?mode=register'>Register</a> &#8226; ";
	 }
		}
	 echo '<a href="./faq.php">FAQ</a> &#8226; <a href="./search.php">Search</a> &#8226; <a href="./memberlist.php">Members</a> &#8226; ';
	 if(!$user->data['is_bot'])
	 if($user->data['is_registered'])echo '<a href="http://fastclan.org/forum/ucp.php?mode=logout&sid='.$user->data['session_id'].'">Logout ['.$user->data['username'].']';
		 else echo '<a href="http://fastclan.org/forum/ucp.php?mode=login">Login</a>';
	 
		
	 
		
		?>
    
    	</td>
</tr>
<tr>
	<td id="contentrow">


    <table width="100%" cellspacing="0">
    <tr>
        <td class="gensmall">
        <?php if($user->data['is_registered'])if(!$user->data['is_bot']){?>
            Last visit was: <?php echo date($user->data['user_dateformat'], $user->data['session_last_visit']);
		?> <br /><a href="./forum/search.php?search_id=unanswered">View unanswered posts</a> | <a href="./forum/search.php?search_id=active_topics">View active topics</a>        </td>
        <td class="gensmall" align="right">

            It is currently <?php echo date($user->data['user_dateformat']); ?>
            <br />
            <a href="./forum/search.php?search_id=newposts">View new posts</a> | <a href="./forum/search.php?search_id=egosearch">View your posts</a>       </td>
    </tr>
    </table>
    <?php }?> 

	<table class="tablebg breadcrumb" width="100%" cellspacing="0" cellpadding="0" style="margin-top: 5px;">

	<tr>
		<td class="row1">
			<p class="breadcrumbs"><a href="./forum/index.php">Board index</a></p>
			<p class="datetime">All times are <?php echo $user->lang['tz'][floor($user->data['user_timezone'])];
			if($user->data['user_dst'])echo ' [ <abbr title="Daylight Saving Time">DST </abbr> ]';?></p>
		</td>
	</tr>

	</table>
	<br />
<table width="100%"><tr>
	   <TD width="170" height="100%" valign="top" >
           <form action="http://translate.google.com/translate" method="get" >
           <center>
<input id="url" type="hidden" value="
" name="u"/>
<select name="tl" class="trans" style="width: auto;">
<option value="ar">Arabic</option>
<option value="bg">Bulgarian</option>

<option value="ca">Catalan</option>
<option value="zh-CN">Chinese (Simplified)</option>
<option value="zh-TW">Chinese (Traditional)</option>
<option value="hr">Croatian</option>
<option value="cs">Czech</option>
<option value="da">Danish</option>
<option value="nl">Dutch</option>
<option selected="selected" value="en">English</option>
<option value="tl">Filipino</option>

<option value="fi">Finnish</option>
<option value="fr">French</option>
<option value="de">German</option>
<option value="el">Greek</option>
<option value="iw">Hebrew</option>
<option value="hi">Hindi</option>
<option value="id">Indonesian</option>
<option value="it">Italian</option>
<option value="ja">Japanese</option>

<option value="ko">Korean</option>
<option value="lv">Latvian</option>
<option value="lt">Lithuanian</option>
<option value="no">Norwegian</option>
<option value="pl">Polish</option>
<option value="pt">Portuguese</option>
<option value="ro">Romanian</option>
<option value="ru">Russian</option>
<option value="sr">Serbian</option>

<option value="sk">Slovak</option>
<option value="sl">Slovenian</option>
<option value="es">Spanish</option>
<option value="sv">Swedish</option>
<option value="uk">Ukrainian</option>
<option value="vi">Vietnamese</option>
</select>
<input type="hidden" value="en" name="hl"/>
<input type="hidden" value="en" name="sl"/>
<input type="hidden" value="UTF-8" name="ie"/>
<input type="submit" class="trans" value="Translate" /></center>

</form>
		   	   <!-- Links and buttons here -->
  <TABLE border="0"  cellpadding="0" cellspacing="0">
	      <TR  align="top">
					  	  <TD width="170" height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">
                            <div class="cap-right">
                            <h4>

                            Menu
                            </h4>
                            </div>
                            </div>
                            </div>
						  </TD>
					  </TR>
			   		  <TR >
					  	  <TD  align="center" class="menubody">

        
<A class=menu href="http://fastclan.org">
	Home
  </A><br>
<A class=menu href="http://fastclan.org/rules.php">
	Rules
  </A><br>
<A class=menu href="http://fastclan.org/faq.php">
	F.A.Q
      </A><br>
<A class=menu href="http://fastclan.org/maps.php">
	Maps
      </A><br>
<script type="text/javascript">
function showmenu(){
	document.getElementById("leadmenu").className="leadmenus";
	
}
function showhidemenu(){
	cln=
	document.getElementById("leadmenu").className;
	if(cln=="leadmenu") 
	document.getElementById("leadmenu").className="leadmenus";
	else 
	document.getElementById("leadmenu").className="leadmenu";
}
mouseisoverdiv=false;
function waitmnrelease(){
	if(mouseisoverdiv){		
		setTimeout(waitmnrelease,500);
	}else{
	document.getElementById("leadmenu").className="leadmenu";		
	}
	}
	
</script>

<div class=menu onmouseover="javascript:showmenu();mouseisoverdiv=true;" onclick="javascript:showhidemenu()" onmouseout="javascript:mouseisoverdiv=false;setTimeout(waitmnrelease,500)">
	Leaderboard
      &gt;&gt; </div>
      <style type="text/css">
	  div.leadmenu{
		  visibility:hidden;		
		  position:absolute;
		  left:140px;		  
		  text-align:left;
	  }
	  	  div.leadmenus{
		  visibility:visible;
		   position:absolute;
		  left:140px;
		  text-align:left;
		 
	  }
	  tr.leaderboard1{
		  background-color:#000000;		  
		 
	  }
	  </style>
      <div id="leadmenu" class="leadmenu" onmouseover="javascript:mouseisoverdiv=true;" onmouseout="javascript:mouseisoverdiv=false;" >
      <table cellpadding="0" cellspacing="0" border="0" id="leadmenut">  <tr><td class="menubody">
      <A class=menu href="http://fastclan.org/leaderboard.php" onmouseover="javascript:showmenu()">
	Composite leaderboard
      </A><br />
      <A class=menu href="http://fastclan.org/weeklylb.php">
	Weekly leaderboard
      </A><br>
<A class=menu href="http://fastclan.org/weeklyhist.php">
	Weekly winners
      </A><br>
<A class=menu href="http://fastclan.org/topnames.php">
	Top names
      </A><br></td></tr></table>
	</div>     

<A class=menu href="http://fastclan.org/servers.php">

	Servers
      </A><br>
<A class=menu href="http://fastclan.org/voice.php">
	Voice Chat
      </A><br>
      <A class=menu href="http://youtube.com/fastclan" target="_blank">
	Videos
      </A><br>
<A class=menu href="http://fastclan.org/cool.php">
	Cool links
  </A><br>
<A class=menu href="http://fastclan.org/forum/">

	Forum index
  </A>    <br />
  <A class=menu href="http://fastclan.org/forum/viewtopic.php?f=27&t=252">
	Forum rules
  </A>     <br />
  <A class=menu href="http://fastclan.org/members.php">
	[FAST] Admins
  </A>    
						  </TD>
					  </TR>
			   </TABLE><br>
  <TABLE border="0"  cellpadding="0" cellspacing="0">
	      <TR  align="top">

					  	  <TD width="170" height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">
                            <div class="cap-right">
                            <h4>
                            <abbr title="Click to view friendboard"><a href='leaderboard.php?mf=1&view[]=1&view[]=2&view[]=3&view[]=4&view[]=5'>Friend board</a></abbr>
                            </h4>
                            </div>
                            </div>

                            </div>
						  </TD>
					  </TR>
			   		  <TR >
					  	  <TD  align="center" class="menubody">
             <?php                
/*$dbuser = 'crasxit0_halo';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'crasxit0_haloStats';
if(!($conn = mysql_connect('127.0.0.1', $dbuser, $dbpass))){
echo "Error, can't connect right now because".mysql_error();
exit;
}*/
mysql_select_db($dbname);
       if($user->data['is_registered'])
    {
	$que=mysql_query("select p.name, p.server , p.id from friends f left join names p on (f.friend=p.id) where f.id='".$user->data['user_id']."' and not p.name is null order by p.server desc");
	echo "Add names by clicking the +<br>";
		echo "Remove names with the -<br>";
	echo mysql_error();
	if($que)while($r=mysql_fetch_array($que)){
	echo "<a href='leaderboard.php?rmfriend=".$r['id']."'>-</a> <a href='leaderboard.php?view[]=1&view[]=2&view[]=3&view[]=4&view[]=5&search=".urlencode($r['name'])."'>".htmlspecialchars($r['name'])."</a>".($r['server']==0?"<br>":"<img src='images/online.gif' /><br>");
	}
	}else
	echo "You must be logged in to use this";
	?>
                       </TD></TR></TABLE>   
                            <TABLE border="0"  cellpadding="0" cellspacing="0">

	      <TR  align="top" >
					  	  <TD width=170 height="30px" >
						  <div class="cap-div">
                            <div class="cap-left">
                            <div class="cap-right">
                            <h4>
                            Teamspeak                          
                            </h4>
                            </div>

                            </div>
                            </div>
						  </TD>
					  </TR>
			   		  <TR >
					  	  <TD  class="menubody" id=teamspeakdiv >
              <img src="http://fastclan.org/images/load.gif" />
              <script type="text/javascript">
			
				function loadts(){
				getLHttp("http://fastclan.org/ajaxloads/teamspeak.php", "teamspeakdiv");
				}
				</script>

                          </TD></TR></TABLE>               
               </td><td valign="top" align="center" >
