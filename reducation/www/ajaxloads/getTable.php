<?php

echo "<table border=1 style='color:000;' cellpadding=3 >";

$dbuser = 'halo_remote'; 
$dbpass = 'O7ROdnRU8oifZMz';
$dbname = 'halo_advanced';
if(!($conn = mysql_connect('127.0.0.1', $dbuser, $dbpass))){
echo "Error, can't connect right now because".mysql_error();
exit;
}
mysql_select_db($dbname);

$cnt=mysql_query("select count(*) as cnt from names where server!=0");
$players=0;
if($tr=mysql_fetch_array($cnt)){ 
$players=$tr['cnt'];
}
echo "<tr><td colspan=3>Players online</td><td >$players</td></tr>";
echo "<tr><td>&nbsp;</td><td>Total</td><td>Week</td><td>Day</td></tr>";
$cnt0=mysql_query("select count(*) cnt from names ");
$cnt1=mysql_query("select count(*) cnt from names n
left join scores_ctf c on n.id=c.name
left join scores_slayer s on n.id=s.name
left join scores_race r on n.id=r.name
left join scores_king k on n.id=k.name
left join scores_oddball o on n.id=o.name where ifnull(c.online_w,0)+ ifnull(s.online_w,0)+ ifnull(o.online_w,0)+ ifnull(r.online_w,0)+ ifnull(k.online_w,0)!=0");
$cnt2=mysql_query("select count(*) cnt from names n
left join scores_ctf c on n.id=c.name
left join scores_slayer s on n.id=s.name
left join scores_race r on n.id=r.name
left join scores_king k on n.id=k.name
left join scores_oddball o on n.id=o.name where ifnull(c.online_d,0)+ ifnull(s.online_d,0)+ ifnull(o.online_d,0)+ ifnull(r.online_d,0)+ ifnull(k.online_d,0)!=0");
$players=0;
$plw=0;
$pld=0;
if($tr=mysql_fetch_array($cnt0)){ 
$players=$tr['cnt'];
}
if($tr=mysql_fetch_array($cnt1)){ 
$plw=$tr['cnt'];
}
if($tr=mysql_fetch_array($cnt2)){ 
$pld=$tr['cnt'];
}
echo "<tr><td>Players</td><td>$players</td><td>$plw</td><td>$pld</td></tr>";

$gets=array(
			"CTF scores"=>array("scores_ctf", "scores"),
			"Slayer kills"=>array("scores_slayer", "scores"),
			"Race laps"=>array("scores_race", "laps")
			);
foreach($gets as $k=>$v){
$col=$v[1]; $tab=$v[0];
$cnt=mysql_query("select sum(t.$col) as cnt, sum(t.".$col."_w) cntw, sum(t.".$col."_d) cntd from $tab t");
$players=0;$p2=0;$p3=0;
if($tr=mysql_fetch_array($cnt)){ 
$players=$tr['cnt'];
$p2=$tr['cntw'];
$p3=$tr['cntd'];
}
echo "<tr><td>$k</td><td>$players</td><td>$p2</td><td>$p3</td></tr>";

}

$gets=array(
			"Oddball time"=>array("scores_oddball", "scores"),
			"King time"=>array("scores_king", "scores"),
			);
foreach($gets as $k=>$v){
$col=$v[1]; $tab=$v[0];
$cnt=mysql_query("select sec_to_time(sum(t.$col)) as cnt, sec_to_time(sum(t.".$col."_w)) as cntw, sec_to_time(sum(t.".$col."_d)) as cntd from $tab t");

$players=0;$p2=0;$p3=0;
if($tr=mysql_fetch_array($cnt)){ 
$players=$tr['cnt'];
$p2=$tr['cntw'];
$p3=$tr['cntd'];
}
echo "<tr><td>$k</td><td>$players</td><td>$p2</td><td>$p3</td></tr>";

}

echo "</table>";
?>