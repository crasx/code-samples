<?php
error_reporting(E_ALL ^ E_NOTICE);

if ($_SERVER['HTTP_X_FORWARD_FOR']) {
$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}

global $user;
	$dbhost = '127.0.0.1';
	$dbuser = "crasxit0_ips";
	$dbpass = 'hK7dvX9B2qSyH4YR';
	if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
		echo "<div class='mb'><div class='body1'>";
		echo "Error, can't connect right now"	;
		echo "</div></div>";
		exit;	
		}	
	$dbname = 'crasxit0_ips';
	mysql_select_db($dbname);
$counterHits=0;
$indb=mysql_query("select count(*) from ips ");
if($row=mysql_fetch_assoc($indb)){
$counterHits=$row['count(*)'];
}
$viewcount=0;
mysql_free_result($indb);
$indb=mysql_query("select sum(times) as 'hts' from user_ip_pageviews");
if($row=mysql_fetch_assoc($indb)){
$viewcount=$row['hts'];
}
mysql_free_result($indb);
$countToday=0;
$indb=mysql_query("select count from days where Date(date)=CURDATE()");
if($row=mysql_fetch_assoc($indb)){
$countToday=$row['count'];
}
mysql_free_result($indb);

echo "<center>$counterHits Visitors<br />$viewcount Page views<br /><a href='http://fastclan.org/hits/count.php?view=histo'>$countToday today</a></center>";	

?>