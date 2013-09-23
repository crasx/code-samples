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



$dbuser="halo";
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'halo_leaderboard';
$conn = mysql_connect('127.0.0.1', $dbuser, $dbpass);
mysql_select_db($dbname);
$suffix="";
$get="";
		
$url="http://".$_SERVER['SERVER_NAME']."/scoreboard.php";
	
switch($_GET['t']){
	case "W":
	$get="t=W&";
	$suffix="W";
	$url.="?t=W";
	break;
	case "D":
	$get="t=D&";
	$suffix="D";
	$url.="?t=D";
	break;
}
		$mf="";
		$tables="";
		$vw=array();
		$quer.="n.id, n.name 'Name', scr.CTF".$suffix." CTF, scr.slayer".$suffix." Slayer, scr.race".$suffix." Race, sec_to_time(scr.oddball".$suffix.") 'Oddball', sec_to_time(scr.king".$suffix.") 'King' ";
		/*
		$dbuser="crasxit0_custlb";
		$dbpass="AJkRcgeHvPMxyIlbYtR5ZrO1";
		$dbschema="crasxit0_custlb";
		$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
		mysql_select_db($dbschema);*/
		
		if($user->data['is_registered']){
				if(!$user->data['is_bot']){			
					if(isset($_GET['mf'])){$mf.="mf=1&";
						$gmf="<input type=hidden name='mf' value=1 />";
					}
					$get.=$mf;
					$quer="concat('<a href=$url?$get"."addfriend=',n.id,'&search=".addslashes(urlencode($_GET['search']))." >+</a>') 'Add', ".$quer;
				}}	   
		  echo "<div id=page >";
		echo "<form method=get action=\"$url\" method=get  >
		<input type=hidden name=t value='".$suffix."' />
		Search: <input type=text name=search value='".$_GET['search']."'> 
		Show: <select name=count >
		<option value=10 >10</option>
		<option value=25 >25</option>
		<option value=50 selected='selected' >50</option>
		<option value=100 >100</option>
		<option value=250 >250</option>
		<option value=500 >500</option></select>
		<input type=submit value=View > - <a href='scoreboard.php'>Total</a> || <a href='scoreboard.php?t=W'>Week</a> || <a href='scoreboard.php?t=D'>Day</a></form>
		<br />";
		
		global $user;
		
		$quer="select ".$quer;
		
		
		
		$count=50;
		if(is_numeric($_GET['count']))$count=$_GET['count'];
		$page=0;
		if(is_numeric($_GET['page']))$page=$_GET['page']-1;
		$begin=$page*$count;
		$sort=3;
		
		if($user->data['is_registered']&&!$user->data['is_bot'])
		$sort++;
		if(is_numeric($_GET['sort']))$sort=$_GET['sort'];
		$link="index.php?page=".($page+1)."&count=$count&$get"."sort=$sort";
			if(isset($_GET['desc']))$link.= "&desc=1";
			if(isset($_GET['search']))$link.="&search=".urlencode($_GET['search']);
		$desc=!isset($_GET['desc'])?"desc":"";
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
		$quer.=",  sec_to_time(online".$suffix.") 'Time online', srv.name Server  from scores_2 scr left join names n on scr.name=n.id left join servers srv  on n.server=srv.id
		where $where 
		";
		$quer.=" order by $sort $desc limit $begin, $count";
		
		$co=mysql_query("select * from names n where $where ");
		$rows = mysql_num_rows($co);
		$qu=mysql_query($quer);
		echo mysql_error();
		$adver=ceil($count/3);
		$cols=0;
		$tad=0;
		$fr=true;
		$eotc=true;
			echo "<table cellspacing=0 width=100% ><tr ".($eotc?"class= leaderboard1":"")." >";
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
			echo "<td ><a href=\"$url?$get"."sort=$cr";
			if($sort==$cr&&!isset($_GET['desc']))echo "&desc=1";
			echo "&search=".urlencode($_GET['search']);
			echo "\">".$rk[$i]."</a></td>";
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
			elseif($rk[$i+1]=="Name")echo "<td>".($r[$rk[$i]]==""?"&nbsp;":htmlentities($r[$rk[$i]]))."</td>";
			else echo "<td>".($r[$rk[$i]]==""?"&nbsp;":htmlentities($r[$rk[$i]]))."</td>";
		}
		echo "</tr>";
		
			$eotc=!$eotc;
		}
		echo "</table><table border=1 ><tr><td>Page ";
$page++;
	$link="&count=$count&$get".($cr==""?"":"sort=$sort");
	if($sort==$i)$link.= "&desc=1";
	$link.=$_GET['search']==""?"":"&search=".urlencode($_GET['search']);
	paginator($page, ceil($rows/$count), $link);
	
		echo "</td></tr></table>";

function paginator($cur, $max, $link){
	if($cur>1){
		$i=$cur-5;
		if($i>1) echo "<a href='http://reclan.com/leaderboard.php?page=1".$link."'>1</a>... ";
		$i=$i<1?1:$i;
		$i=$i==$cur?$i+1:$i;
		for(;$i<$cur;$i++){
			echo "<a href='http://reclan.com/leaderboard.php?page=$i".$link."'>$i</a> ";
		}
	}
	
		echo $cur." ";
	if($cur<$max){
		$i=$cur+1;	
		$mf=false;
		for(;$i<$cur+5;$i++){
			if($i>=$max){$mf=true;continue;}
			echo "<a href='http://reclan.com/leaderboard.php?page=$i".$link."'>$i</a> ";
		}
		if(!$mf)echo "... <a href='http://reclan.com/leaderboard.php?page=$max".$link."'>$max</a> ";
	}
}


$template->assign_vars(array(
	'HEADERTEXT'		=> $title,
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();


?>