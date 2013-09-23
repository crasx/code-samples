<?php
	mysql_select_db($dbname);
$td=getdate();
$keys=array("CTF"=>0, "Slayer"=>1, "Race"=>2, "Oddball"=>3, "King"=>4);

if($td['hours']==23){
echo "A";
//ctf=>name, score, day
$fields=array(
array(),array(),array(),array(),array()
);
$que=mysql_query("select * from daily_2");
if($que)while($r=mysql_fetch_array($que)){
	$fields[$r['field']][]=array($r['name'], $r['score'],  $r['day'], $r['time']);
}
mysql_query("truncate daily_2");
foreach(array_keys($keys) as $f){
	$que=mysql_query("select name, ".$f."D, onlineD from scores_2 order by ".$f."D desc, name limit 10");
	if($que)while($r=mysql_fetch_array($que)){
		if($r[1]==0)continue;
		$fields[$keys[$f]][]=array($r['name'], $r[1], time(), $r['onlineD']);	
		}
		
		usort($fields[$keys[$f]], "cmp");
		print_r($fields[$keys[$f]]);
		for($i=0;$i<10;$i++){
			$r=$fields[$keys[$f]][$i];
			mysql_query("insert into daily_2(day, field, place, name, score, time) values($r[2],$keys[$f], $i, $r[0], $r[1], $r[3])");
		}
}
mysql_query("update scores_2 set ctfD=0, kingD=0, slayerD=0, oddballD=0, raceD=0, onlineD=0");
}

function cmp($a,$b){
	if($a[1]==$b[1])return 0;
	return $a[1]>$b[1]?-1:1;
}
	
?>