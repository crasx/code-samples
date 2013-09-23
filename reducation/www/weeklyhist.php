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

    page_header('Weekly history');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));


$ad='<script type="text/javascript"><!--
google_ad_client = "pub-4881848801219558";
/* 728x15, created 6/1/09 */
google_ad_slot = "4605742255";
google_ad_width = 728;
google_ad_height = 15;
//-->
</script>
<script type="text/javascript"
src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>';

$dbuser="halo";
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'halo_leaderboard';
$conn = mysql_connect('127.0.0.1', $dbuser, $dbpass);
mysql_select_db($dbname);
$get="";
$mf="";
$tables="";
$vw=array();
$cols=4;
$quer.="n.id, n.name 'Name' ";
if(isset($_GET['view'])){
switchval($_GET['view']);
}
$get="view=". $_GET['view'];

global $user;

$quer="select ".$quer;
echo "<form action=\"/weeklyhist.php\">";
$rrr=array(1=>"CTF",2=>"Slayer",3=>"Race",4=>"Oddball",5=>"King");
foreach ($rrr as $k=>$v){
echo '<a href="weeklyhist.php?view='.$k.'&search='.(isset($_GET['search'])?$_GET['search']:'').'"> '.$v.'</a><br />
';
}

if(isset($_GET['view'])){
$count=50;
if(is_numeric($_GET['count']))$count=$_GET['count'];
$page=0;
if(is_numeric($_GET['page']))$page=$_GET['page']-1;
$begin=$page*$count;
$sort=$cols;
if(is_numeric($_GET['sort']))$sort=$_GET['sort'];
$link="weeklyhist.php?page=".($page+1)."&count=$count&$get"."sort=$sort";
	if(isset($_GET['desc']))$link.= "&desc=1";
	if(isset($_GET['search']))$link.="&search=".urlencode($_GET['search']);
$desc=!isset($_GET['desc'])?"desc":"";
	
$where;$join;$online;

//$tables.=" crasxit0_halo.players oc, ";
$where .="  n.name like '%".addslashes($_GET['search'])."%'";

$quer.=",  date_format(week, '%V (%X)') 'Week' from history_2 t left join names n on $on
where $where 
";
$quer.=" order by $sort $desc limit $begin, $count";


$co=mysql_query("select * from history_2 where $where ");
if($co)$rows = mysql_num_rows($co);


$qu=mysql_query($quer);
echo mysql_error();
$adver=ceil($count/3);
$cols=0;
$tad=0;
$fr=true;
echo "<table  ><tr>";
if($qu)
while($r=mysql_fetch_array($qu)){
	if($fr){
	$rk=array_keys($r);
	$arrln=count($rk);
	$cr=1;
	$cols=$arrln/2;
	for($i=1;$i<$arrln;$i+=2){
		if($rk[$i]=="id")continue;
	echo "<td><a href=\"/weeklyhist.php?$get"."sort=$cr";
	if($sort==$cr&&!isset($_GET['desc']))echo "&desc=1";
	echo "&search=".urlencode($_GET['search']);
	echo "$ipp\">".$rk[$i]."</a></td>";
	$cr++;
	
	}
	echo "</tr>";
	$fr=false;
	}
	if($tad==$adver){
	echo "<tr><td colspan=$cols >$ad </td></tr>";
	$tad=0;
	}else $tad++;
echo "<tr>";
for($i=1;$i<$arrln;$i+=2){
	if($rk[$i]=="Add") echo "<td>".$r[$rk[$i]]."</td>";
	elseif($rk[$i]=="id")continue;
	elseif($rk[$i]=="Name")echo "<td>".($r[$rk[$i]]==""?"&nbsp;":htmlentities(stripslashes($r[$rk[$i]])))."</td>";
	else echo "<td>".($r[$rk[$i]]==""?"&nbsp;":htmlentities($r[$rk[$i]]))."</td>";
}
echo "</tr>";
}
echo "</table><table border=1 ><tr><td>Page ";
$page++;
for($p=1;$p<=ceil($rows/$count);$p++){
if($p==$page)echo "$p ";
else{ echo "<a href=\"/weeklyhist.php?page=$p&count=$count&$get".($cr==""?"":"sort=$sort");
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
global $tables, $quer, $on;

switch($val){
	case 1:
	$quer.=($quer==""?"":", ")." t.ctfs 'Ctf scores'";
	$on="n.id=t.ctfn";
	break;
	case 2:
	$quer.=($quer==""?"":", ")." (t.slayers) 'Slayer scores'";
	$on="n.id=t.slayern";
	break;
	case 3:
	$quer.=($quer==""?"":", ")." t.races 'Race laps'";
	$on="n.id=t.racen";
	break;
	case 4:
	$quer.=($quer==""?"":", ")." sec_to_time(t.oddballs) 'Oddball time'";
	$on="n.id=t.oddballn";
	break;
	case 5:
	$quer.=($quer==""?"":", ")." sec_to_time(t.kings) 'King time' ";
	$on="n.id=t.kingn";	
	break;
	
}
	
}


$template->assign_vars(array(
	'HEADERTEXT'		=> "Past winners",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?> 