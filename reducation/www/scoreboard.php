<?php 
ob_start();

define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Leaderboard');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
	
	
$dbhost = '127.0.0.1';
$dbuser = 'halo_remote';
$dbpass = 'y9bZTQMMblv9zls';
$dbname = 'halo_advanced';
if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
echo "Error, can't connect right now because".mysql_error();
exit;
}
mysql_select_db($dbname);


$get="";
$mf="";
$tables="";
$notype="";
$noview="";
switch($_GET['t']){
	case "W":
	$get="t=W&";
	$suffix="_W";
	$url.="?t=W";
	break;
	case "D":
	$get="t=D&";
	$suffix="_D";
	$url.="?t=D";
	break;
}

	if(isset($_GET['search'])){
		$get.="search=".urlencode($_GET['search'])."&";
		$notype.="search=".urlencode($_GET['search'])."&";	
	}
$noview=$get;
$vw=array();
$quer.="n.id, n.name 'Name' ";
if(isset($_GET['view'])){
foreach($_GET['view'] as $k=>$v){
if($v=="")continue;
switchval($v);
$vw[]=$v;
$get.="view[]=$v&";
$notype.="view[]=$v&";

}
}


global $user;
if($user->data['is_registered']){
	  	if(!$user->data['is_bot']){			
			if(isset($_GET['mf'])){
			$get.="mf=1&";
			$notype.="mf=1&";
			$noview.="mf=1&";
			}
			$quer="concat('<a href=?$get"."addfriend=',n.id,'&search=".addslashes(urlencode($_GET['search']))." >+</a>') 'Add', ".$quer;
		}
		}
$quer="select ".$quer;

echo "<table><tr><td><form method=get action=\"?$noview\" method=get  >";
echo "<input type=checkbox name=view[] ".(in_array(1,$vw)?"checked ":"")." value=1 ><a href=\"?$noview"."view[]=1\" >CTF</a><br>";
echo "<input type=checkbox name=view[] ".(in_array(2,$vw)?"checked ":"")." value=2 ><a href=\"?$noview"."view[]=2\">Slayer</a><br>";
echo "<input type=checkbox name=view[] ".(in_array(3,$vw)?"checked ":"")." value=3 ><a href=\"?$noview"."view[]=3\">Race</a><br>";
echo "<input type=checkbox name=view[] ".(in_array(4,$vw)?"checked ":"")." value=4 ><a href=\"?$noview"."view[]=4\"'>Oddball</a><br>";
echo "<input type=checkbox name=view[] ".(in_array(5,$vw)?"checked ":"")." value=5 ><a href=\"?$noview"."view[]=5\">King</a><br><br />";
echo "Search: <input type=text name=search value='".$_GET['search']."'> 
Show: <select name=count >
<option value=10 >10</option>
<option value=25 >25</option>
<option value=50 selected='selected' >50</option>
<option value=100 >100</option>
<option value=250 >250</option>
<option value=500 >500</option></select>
<br />
<input type=submit value=View ></form>
</td><td valign=top >".(isset($_GET['t'])?"
<a href=\"?$notype"."\">Scoreboard</a>":"Scoreboard")."<br />".
(strcmp($_GET['t'],"W")!=0?"
<a href=\"?$notype"."t=W\">Weekboard</a>":"Weekboard")."<br />".
(strcmp($_GET['t'],"D")!=0?"
<a href=\"?$notype"."t=D\">Dayboard</a>":"Dayboard");

echo "</td></tr></table>";
$ips="";
$ipp="";

if(isset($_GET['view'])){
$count=50;
if(is_numeric($_GET['count']))$count=$_GET['count'];
$page=0;
if(is_numeric($_GET['page']))$page=$_GET['page']-1;
$begin=$page*$count;
$sort=3;

if($user->data['is_registered']&&!$user->data['is_bot'])
$sort++;
if(is_numeric($_GET['sort']))$sort=$_GET['sort'];
$link="?page=".($page+1)."&count=$count&$get"."sort=$sort";
	if(isset($_GET['desc']))$link.= "&desc=1";
	if(isset($_GET['search']))$link.="&search=".urlencode($_GET['search']);
$desc=!isset($_GET['desc'])?"desc":"";
	
$where;$join;$kill;$death;$suicide;$betray;$assist;$online;
$ad='<script type="text/javascript"><!--
google_ad_client = "pub-4881848801219558";
/* 4ftop */
google_ad_slot = "1181663952";
google_ad_width = 468;
google_ad_height = 60;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';
//$tables.=" crasxit0_halo.players oc, ";
$where .="  n.name like '%".addslashes($_GET['search'])."%'";

if($user->data['is_registered']){
	  	if(!$user->data['is_bot']){
if(isset($_GET['mf'])){
	$where.=" and n.id in(select friend from friends where id=".$user->data['user_id'].")";			   
}}}
$quer.=", $kill 'Kills', $death 'Deaths', $assist 'Assists', $suicide 'Suicides', $betray 'Betrayals', sec_to_time($online) 'Time online', srv.name Server $ips from names n left join servers srv  on(n.server=srv.id) $tables
where $where 
";
$quer.=" order by $sort $desc limit $begin, $count";
$co=mysql_query("select * from names n $tables where $where ");
$rows = mysql_num_rows($co);
$qu=mysql_query($quer);
echo mysql_error();
$adver=ceil($count/4);
$cols=0;
$tad=0;
$fr=true;
$eotc=true;
	echo "<table  cellspacing=2 cellpadding=3 ><tr ".($eotc?"class= leaderboard1":"")." >";
if($qu)
while($r=mysql_fetch_array($qu)){
	if($fr){
	$rk=array_keys($r);
	$arrln=count($rk);
	$cr=1;
	$cols=$arrln/2;
	$eotc=false;
	for($i=1;$i<$arrln;$i+=2){
	if($rk[$i]=='id'){$cr++;continue;}
	echo "<td ><a href=\"?$get"."sort=$cr";
	if($sort==$cr&&!isset($_GET['desc']))echo "&desc=1";
	echo "&search=".urlencode($_GET['search']);
	echo "$ipp\">".$rk[$i]."</a></td>";
	$cr++;
	}
	echo "</tr>";
	$eotc=!$eotc;
	$fr=false;
	}
	if($tad==$adver){
	echo "<tr class= leaderboard1 ><td colspan=$cols align=center >$ad </td></tr>";
	$tad=0;	
	}else $tad++;
echo "<tr ".($eotc?"class= leaderboard1":"")." >";
for($i=0;$i<$arrln;$i+=2){
if($rk[$i+1]=="Add") echo "<td>".$r[$rk[$i]]."</td>";
	elseif($rk[$i+1]=="id")continue;
	elseif($rk[$i+1]=="Name")echo "<td><a href='profile.php?p=".$r['id']."'>".($r[$rk[$i]]==""?"&nbsp;":htmlentities($r[$rk[$i]]))."</a></td>";
	else echo "<td>".($r[$rk[$i]]==""?"&nbsp;":htmlentities(str_replace("\x01", "",$r[$rk[$i]])))."</td>";
}
echo "</tr>";

	$eotc=!$eotc;
}
echo "</table><table border=1 ><tr><td>Page ";
$page++;
for($p=1;$p<=ceil($rows/$count);$p++){
if($p==$page)echo "$p ";
else{ echo "<a href=\"?page=$p&count=$count&$get".($cr==""?"":"sort=$sort");
	if($sort==$i)echo "&desc=1";
	echo $_GET['search']==""?"":"&search=".urlencode($_GET['search']);
	echo "$ipp\">$p </a>";
}
if($p%25==0)echo "<br />
";
}
echo "</td></tr>";
echo "</table>";
}

function switchval($val){
global $tables, $quer, $kill,$death,$suicide,$betray,$assist,$online, $where, $full, $suffix;

switch($val){
	case 1:
	$tables.="left join scores_ctf c on n.id=c.name ";
	$quer.=($quer==""?"":", ")." ifnull(c.scores$suffix,0) 'Ctf scores', ifnull(c.captures$suffix,0) Captures, ifnull(c.returns$suffix,0) Returns ";
	$t="c";
	break;
	case 3:
	$quer.=($quer==""?"":", ")." ifnull(r.laps$suffix,0) 'Race laps'";
	$tables.="left join scores_race r on n.id=r.name ";
	$t="r";
	break;
	case 2:
	$quer.=($quer==""?"":", ")." ifnull(s.scores$suffix,0) 'Slayer' ";
	$tables.="left join scores_slayer s on n.id=s.name ";	
	$t="s";
	break;
	case 4:
	$quer.=($quer==""?"":", ")." ifnull(sec_to_time(o.scores$suffix),'00:00:00') 'Oddball' ";
	$tables.="left join scores_oddball o on n.id=o.name ";
	$t="o";
	break;
	case 5:
	$quer.=($quer==""?"":", ")." ifnull(sec_to_time(k.scores$suffix),'00:00:00') 'King' ";
	$tables.="left join scores_king k on n.id=k.name ";
	$t="k";
	break;
}
	$kill.=($kill!=""?"+":"")."ifnull($t.kills$suffix,0) ";
	$death.=($death!=""?"+":"")."ifnull($t.deaths$suffix,0) ";
	$suicide.=($suicide!=""?"+":"")."ifnull($t.suicides$suffix,0) ";
	$betray.=($betray!=""?"+":"")."ifnull($t.betrays$suffix,0)";
	$assist.=($assist!=""?"+":"")."ifnull($t.assists$suffix,0) ";
	$online.=($online!=""?"+":"")."ifnull($t.online$suffix,0) ";
}

$template->assign_vars(array(
	'HEADERTEXT'		=> "Leaderboard",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?> 