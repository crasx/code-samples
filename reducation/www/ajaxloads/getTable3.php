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

$cnt=mysql_query("select count(*) as cnt from servers where name!=''");
$players=0;
if($tr=mysql_fetch_array($cnt)){ 
$players=$tr['cnt'];
}
echo "<tr><td colspan=2>Servers online</td><td >$players</td></tr>";
echo "<tr><td>&nbsp;</td><td>Total</td><td>Week</td><td>Day</td></tr>";

$gets=array(
			"CTF Captures"=>array("scores_ctf", "captures"),
			"CTF Returns"=>array("scores_ctf", "returns"),
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

$gets=array("Kills", "Assists", "Deaths","Suicides","Betrays");
foreach($gets as $k=>$v){
$col=strtolower($v);
$tablez=array("scores_ctf","scores_slayer","scores_race","scores_oddball","scores_king");
$sum=0; $sum2=0; $sum3=0;
foreach($tablez as $t){
$cnt=mysql_query("select sum(t.$col) cnt,  sum(t.".$col."_w) cntw, sum(t.".$col."_d) cntd from $t t" );
	if($tr=mysql_fetch_array($cnt)){ 
	$sum+=$tr['cnt'];
	$sum3+=$tr['cntw'];
	$sum2+=$tr['cntd'];
	}
}
echo "<tr><td>$v</td><td>$sum</td><td>$sum2</td><td>$sum3</td></tr>";
}

echo "</table>";
?>