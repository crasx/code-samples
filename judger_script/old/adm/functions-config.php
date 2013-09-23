<?php
require_once('cfg.php');
$body="<h1>Custom field configuration</h1>";
if(!$userInfo['admin']){$body=("You shouldnt be here");return;}
$edit=0;
$del=false;
if(isset($_GET['editcat'])&&is_numeric($_GET['editcat'])){
	$chec=isset($_GET['showinjudge'])?($_GET['showinjudge']==1?1:0):0;
	$chec2=isset($_GET['showinreport'])?($_GET['showinreport']==1?1:0):0;
	$chec3=isset($_GET['showinrss'])?($_GET['showinrss']==1?1:0):0;
	if($_GET['editcat']==0){
		if(mysql_query("insert into ".T_CUST."(name, display, report, rss) values('".mysql_real_escape_string($_GET['custname'])."',$chec, $chec2, $chec3)")){
			$body.="<h2>Category added</h2>";	
		}else $body.="Error adding:".mysql_error();
	}else{
		if(isset($_GET['done'])){
			if(mysql_query("update ".T_CUST." set name='".mysql_real_escape_string($_GET['custname'])."', display=$chec, report=$chec2, rss=$chec3 where id='".$_GET['editcat']."'"))
			   {
				$body.="<h2>Category updated</h2>";
			   }		
		}
		$name="";
		$que=mysql_query("select * from ".T_CUST." where id='".$_GET['editcat']."'");
		if($que)if($r=mysql_fetch_array($que)){
			$name=$r['name'];
			$edit=$r['id'];
			$checked=$r['display']==1?"checked=checked":"";
			$checked2=$r['report']==1?"checked=checked":"";
			$checked3=$r['rss']==1?"checked=checked":"";
		}
	}
}else if(isset($_GET['delcat'])&&is_numeric($_GET['delcat'])){
	$del=true;
	if($getnq=mysql_fetch_array(mysql_query("select name from ".T_CUST." where id='".$_GET['delcat']."'"))){
	$catname=$getnq['name'];
	if(isset($_GET['done'])){
		if(strcmp(md5($_GET['done']),$userInfo['password'])==0){
			$docnof=false;
			if(mysql_query("delete from ".T_CUST." where id='".$_GET['delcat']."'")&&mysql_query("delete from ".T_CUST_VALS." where field='".$_GET['delcat']."'")){
				$body.="<h2>Category deleted</h2>";
			}else $body.="<h2>Error deleting category-".mysql_error()."</h2>";
			$body.="<br />
<br /><input type=button onclick='javascript:getHttp(\"ajax-php.php?page=config\",\"page\")' value='Back' >";
		}else $doconf=true;
	}else $doconf=true;
	if($doconf){
	$body.="<form action='javascript:getUri(\"ajax-php.php?page=config&delcat=".$_GET['delcat']."&\",\"delcat\",\"page\")' id='delcat'><h2>Are you sure you want to delete $catname?</h2><h3>Please enter your password to verify</h3><input type=password name=done ><br />
<input type=submit value=Delete ><br />
<input type=button onclick='javascript:getHttp(\"ajax-php.php?page=config\", \"page\")' value='Cancel' ></form>";
	}
	}else $body.="<h2>Category not found</h2>";
}

if(!$del){
	$body.="<form action='javascript:getUri(\"ajax-php.php?page=config&editcat=$edit&".($edit!=0?"done=1&":"")."\",\"editcat\", \"page\")' id='editcat'><table><tr><td>Custom field name:</td><td><input type=text value='$name' name=custname /></td></tr><tr><td>Include on judge screen</td><td><input type=checkbox name=showinjudge value=1 $checked ></td></tr><tr><td>Include on report</td><td><input type=checkbox name=showinreport value=1 $checked2 ></td></tr><tr><td>Include on rss feed</td><td><input type=checkbox name=showinrss value=1 $checked3 ></td></tr><tr><td colspan=2 align=center ><input type=submit value=".($edit==0?"Add":"Edit")." /></td></tr></table></form>";
}



$que=mysql_query("select * from ".T_CUST);
if(mysql_num_rows($que)>0){
$body.="<br />
<br /><table border=1><tr><td>&nbsp;</td><td>&nbsp;</td><td>Name</td><td>Show to judge</td><td>Show on report</td><td>Show on rss</td></tr>";
if($que)while($r=mysql_fetch_array($que)){
	$body.="<tr><td><a href='javascript:getHttp(\"ajax-php.php?page=config&editcat=".$r['id']."\",\"page\")'><img src='img/green_edit.jpg' /></a></td>
	<td><a href='javascript:getHttp(\"ajax-php.php?page=config&delcat=".$r['id']."\",\"page\")'><img src='img/red_delete.jpg' /></a></td>
	<td>".$r['name']."</td>
	<td>".($r['display']?"Yes":"No")."</td>
	<td>".($r['report']?"Yes":"No")."</td>
	<td>".($r['rss']?"Yes":"No")."</td>
	</tr>";
}
$body.="</table>";
}
//////////////begin rss
$body.="<br /><hr style='width:100%;' />";
$edit=0;
$del=false;
if(isset($_GET['editrss'])&&is_numeric($_GET['editrss'])){
	$edit=$_GET['editrss'];
	if($_GET['editrss']==0){
			$title=isset($_GET['rsstitle'])?$_GET['rsstitle']:"";
			$text=isset($_GET['rsstext'])?$_GET['rsstext']:"";
			$date=isset($_GET['rssdate'])?$_GET['rssdate']:"";
			$datetime =  strtotime($date);
			if($title=="")$body.= "<h2>You need a title</h2>";
			elseif($text=="")$body.= "<h2>You need text</h2>";
			elseif(!$datetime)$body.= "<h2>Invalid date</h2>";
			else{
				if(mysql_query("insert into ".T_RSS."(title, text, date) values('".sqld($title)."','".sqld($text)."','".sqld($datetime)."')")){
				   $date='';$title='';$text='';
					$body.="<h2>Feed added</h2>";	
				}else $body.="Error adding:".mysql_error();
			}
	}else{
		if(isset($_GET['done'])){
			$title=isset($_GET['rsstitle'])?$_GET['rsstitle']:"";
			$text=isset($_GET['rsstext'])?$_GET['rsstext']:"";
			$date=isset($_GET['rssdate'])?$_GET['rssdate']:"";
			$datetime =  strtotime($date);
			if($title=="")$body.= "<h2>You need a title</h2>";
			elseif($text=="")$body.= "<h2>You need text</h2>";
			elseif(!$datetime)$body.= "<h2>Invalid date</h2>";
			else{
			if(mysql_query("update ".T_RSS." set title='".sqld($title)."', text='".sqld($text)."', date='".sqld($datetime)."' where id='".$_GET['editrss']."'"))
			   {
				   $date='';$title='';$text='';
				$body.="<h2>Feed updated</h2>";
			   }		
			}
		}
		$name="";
		$que=mysql_query("select * from ".T_RSS." where id='".$_GET['editrss']."'");
		if($que)if($r=mysql_fetch_array($que)){
			$title=dsql($r['title']);
			$text=dsql($r['text']);
			$date=$r['date'];
			$datetime=true;
		}
	}
}else if(isset($_GET['delrss'])&&is_numeric($_GET['delrss'])){
	$del=true;
	if($getnq=mysql_fetch_array(mysql_query("select title from ".T_RSS." where id='".$_GET['delrss']."'"))){
	$title=$getnq['title'];
	if(isset($_GET['done'])){
		if(strcmp(md5($_GET['done']),$userInfo['password'])==0){
			$docnof=false;
			if(mysql_query("delete from ".T_RSS." where id='".$_GET['delrss']."'")){
				$body.="<h2>Feed deleted</h2>";
			}else $body.="<h2>Error deleting feed-".mysql_error()."</h2>";
			$body.="<br />
<br /><input type=button onclick='javascript:getHttp(\"ajax-php.php?page=config\",\"page\")' value='Back' >";
		}else $doconf=true;
	}else $doconf=true;
	if($doconf){
	$body.="<form action='javascript:getUri(\"ajax-php.php?page=config&delrss=".$_GET['delrss']."&\",\"delrss\",\"page\")' id='delrss'><h2>Are you sure you want to delete $title?</h2><h3>Please enter your password to verify</h3><input type=password name=done ><br />
<input type=submit value=Delete ><br />
<input type=button onclick='javascript:getHttp(\"ajax-php.php?page=config\", \"page\")' value='Cancel' ></form>";
	}
	}else $body.="<h2>Feed not found</h2>";
}
if(!$del){
	
$date=$date!=""&&$datetime?date("Y-m-d H:i:s",$date):"";
$body.="<h1>Rss publishing</h1>
<form action='javascript:getUri(\"ajax-php.php?page=config&editrss=$edit&".($edit!=0?"done=1&":"")."\",\"editcat\", \"page\")' id='editcat'>
<table><tr><td>Title:</td><td><input type=text value='$title' name=rsstitle /></td></tr>
<tr><td>Text:</td><td><textarea name=rsstext cols=20 rows=3 >$text</textarea></td></tr>
<tr><td>Date:</td><td><input type=text value='$date' name=rssdate /><br />
yyyy-mm-dd hh:mm:ss or now <a href='http://us2.php.net/strtotime#function.strtotime.examples' target='_blank'>guide</a></td></tr>
<tr><td colspan=2 align=center ><input type=submit value=".($edit==0?"Add":"Edit")." /></td></tr></table></form>
<br /><br />";
}
$que=mysql_query("select * from ".T_RSS);
if(mysql_num_rows($que)>0){
$body.="<br />
<br /><table border=1><tr><td>&nbsp;</td><td>&nbsp;</td><td>Title</td><td>Date</td></tr>";
if($que)while($r=mysql_fetch_array($que)){
	$body.="<tr><td><a href='javascript:getHttp(\"ajax-php.php?page=config&editrss=".$r['id']."\",\"page\")'><img src='img/green_edit.jpg' /></a></td>
	<td><a href='javascript:getHttp(\"ajax-php.php?page=config&delrss=".$r['id']."\",\"page\")'><img src='img/red_delete.jpg' /></a></td>
	<td>".$r['title']."</td>
	<td>".date("Y-m-d H:i:s",$r['date'])."</td>	
	</tr>";
}
$body.="</table>";
}
?>