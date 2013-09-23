<?php
if($_GET['go']!=1)die("NOC");
$dbuser = 'halo_remote';
$dbpass = 'O7ROdnRU8oifZMz';
$dbname = 'halo_advanced';
	$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
	mysql_select_db($dbname);
$keys=array("scores_d"=>array(0, "scores_ctf"), "scores_d"=>array(1, "scores_slayer"), "laps_d"=>array(2, "scores_race"), "scores_d"=>array(3, "scores_oddball"), "scores_d"=>array(4, "scores_king"), "Returns_d" =>array(5, "scores_ctf"), "Captures_d"=>array(6,  "scores_ctf"));
$keys2=array("Kills_d"=>7, "assists_d"=>8, "deaths_d"=>9, "suicides_d"=>10, "betrays_d"=>11, "online_d"=>12);
date_default_timezone_set("America/Chicago");
$td=getdate();
echo $td['hours'];
set_time_limit(0);
$message="Running...\r\n";

if($td['hours']==23){
	$message.="Correct hour\r\n";
$fields=array();
for($i=0;$i<13;$i++){
	$fields[$i]=array();	
}
$que=mysql_query("select * from daily");
if($que)while($r=mysql_fetch_array($que)){
	$fields[$r['field']][]=array($r['name'], $r['score'],  $r['day'], $r['time']);
}
mysql_query("truncate daily");
foreach(array_keys($keys) as $f){
	$que=mysql_query("select c.name, c.".$f.", c.online_d from ".$keys[$f][1]." c, names n where n.id=c.name and n.common=0 order by ".$f." desc, name limit 10");
	if($que)while($r=mysql_fetch_array($que)){
		if($r[1]==0)continue;
			$fields[$keys[$f][0]][]=array($r['name'], $r[1], time(), $r['online_d']);	
		}
		
		usort($fields[$keys[$f][0]], "cmp");	
		//print_r($fields[$keys[$f][0]]);
		for($i=0;$i<10;$i++){
			$r=$fields[$keys[$f][0]][$i];
			mysql_query("insert into daily(day, field, place, name, score, time) values($r[2],".$keys[$f][0].", $i, $r[0], $r[1], $r[3])");
			$message.="insert into daily(day, field, place, name, score, time) values($r[2],".$keys[$f][0].", $i, $r[0], $r[1], $r[3])\r\n".mysql_error()."\r\n";
		}
	}

	foreach(array_keys($keys2) as $f){
		$que=mysql_query("select n.id, c.".$f."+s.".$f."+r.".$f."+k.".$f."+o.".$f." score, c.online_d+s.online_d+r.online_d+k.online_d+o.online_d online_d from names n
	 left join scores_ctf c on n.id=c.name
	left join scores_slayer s on n.id=s.name
	left join scores_race r on n.id=r.name
	left join scores_king k on n.id=k.name
	left join scores_oddball o on n.id=o.name order by 2 desc, n.name limit 10");
		
		if($que)while($r=mysql_fetch_array($que)){
			if($r[1]==0)continue;
				$fields[$keys2[$f]][]=array($r['id'], $r[1], time(), $r['online_d']);	
		}
			usort($fields[$keys2[$f]], "cmp");
			//print_r($fields[$keys2[$f]]);
			for($i=0;$i<10;$i++){
				$r=$fields[$keys2[$f]][$i];
				mysql_query("insert into daily(day, field, place, name, score, time) values($r[2],$keys2[$f], $i, $r[0], $r[1], $r[3])");
				$message.="insert into daily(day, field, place, name, score, time) values($r[2],$keys2[$f], $i, $r[0], $r[1], $r[3])\r\n".mysql_error()."\r\n";
			}
		
	}
$tab=array("scores_ctf"=>array("scores_d", "returns_d", "captures_d"), "scores_slayer"=>array("scores_d"), "scores_king"=>array("scores_d"), "scores_oddball"=>array("scores_d"), "scores_race"=>array("laps_d"));
foreach($tab as $k=>$v){
	$quer="update $k set ";
	foreach($v as $vv){
		$quer.="$vv=0, ";	
	}
	$quer.=" kills_d=0, assists_d=0, suicides_d=0, betrays_d=0, deaths_d=0, online_d=0";
	mysql_query($quer);
	$message.=$quer."\r\n".mysql_error()."\r\n";
}

}



function cmp($a,$b){
	if($a[1]==$b[1])return 0;
	return $a[1]>$b[1]?-1:1;
}


require_once("c:\\root\\phpmailer\\mail.php");
sendmail("matthew@crasxit.net", "server@crasxit.net", "crasX IT", "Daily leaderboard", $message);
?>