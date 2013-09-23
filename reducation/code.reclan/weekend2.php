<?php

	$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
	mysql_select_db($dbname);
$keys=array("scores_w"=>array(0, "scores_ctf"), "scores_w"=>array(1, "scores_slayer"), "laps_w"=>array(2, "scores_race"), "scores_w"=>array(3, "scores_oddball"), "scores_w"=>array(4, "scores_king"), "Returns_w" =>array(5, "scores_ctf"), "Captures_w"=>array(6,  "scores_ctf"));
$keys2=array("Kills_w"=>7, "assists_w"=>8, "deaths_w"=>9, "suicides_w"=>10, "betrays_w"=>11, "online_w"=>12);
date_default_timezone_set("America/Chicago");
$td=getdate();
set_time_limit(0);
$message="Running...\r\n";

if($td['wday']==6){
	$message.="Correct day\r\n\r\n============std==========\r\n";

foreach(array_keys($keys) as $f){
	$q="insert into weekly (week, field, name, score, time) select now(), ".$keys[$f][0].", c.name, c.".$f.", c.online_w from ".$keys[$f][1]." c, names n where n.id=c.name and n.common=0 order by ".$f." desc, name limit 1";
	$que=mysql_query($q);
	$message.=$q."\r\n".mysql_error()."\r\n";
	}
	$message.="\r\n============more==========\r\n";

	foreach(array_keys($keys2) as $f){
		$q="insert into weekly (week, field, name, score, time) select now(), ".$keys2[$f].", c.name, c.".$f."+s.".$f."+r.".$f."+k.".$f."+o.".$f." score, c.online_d+s.online_d+r.online_d+k.online_d+o.online_d online_d from names n
	 left join scores_ctf c on n.id=c.name
	left join scores_slayer s on n.id=s.name
	left join scores_race r on n.id=r.name
	left join scores_king k on n.id=k.name
	left join scores_oddball o on n.id=o.name order by 4 desc, n.name limit 1";
		$que=mysql_query($q);
	$message.=$q."\r\n".mysql_error()."\r\n";
	}
	$message.="\r\n\r\n========adios\r\n";
$tab=array("scores_ctf"=>array("scores_w", "returns_w", "captures_w"), "scores_slayer"=>array("scores_w"), "scores_king"=>array("scores_w"), "scores_oddball"=>array("scores_w"), "scores_race"=>array("laps_w"));
foreach($tab as $k=>$v){
	$quer="update $k set ";
	foreach($v as $vv){
		$quer.="$vv=0, ";	
	}
	$quer.=" kills_w=0, assists_w=0, suicides_w=0, betrays_w=0, deaths_w=0, online_w=0";
	mysql_query($quer);
	$message.=$quer."\r\n".mysql_error()."\r\n";
}

}


require_once("c:\\root\\phpmailer\\mail.php");
echo sendmail("matthew@crasxit.net", "server@crasxit.net", "crasX IT", "Weekly leaderboard", $message);
?>