<?php
if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
echo "Error, can't connect 	right now because".mysql_error();
exit;
}
mysql_select_db($dbname);
$date=getdate();
if($date['wday']==0&&$day['hours']==0&&$day['minutes']==0){
	mysql_query("insert into time_weekly(time, count) values(date_sub(NOW(), INTERVAL 1 WEEK), (select round(avg(count)) from time_daily where WEEK(time)=(WEEK(date_sub(now(), 	INTERVAL 1 WEEK))) ))");
	mysql_query("delete from time_daily where WEEK(time)=(WEEK(date_sub(now(), INTERVAL 2 WEEK))) )");
	
}
if($day['hours']==0&&$day['minutes']==0){
	mysql_query("insert into time_daily(time, count) values(date_sub(NOW(), INTERVAL 1 DAY), (select round(avg(count)) from time_hourly where DAY(time)=(DAY(date_sub(now(), INTERVAL 1 DAY))) ))");
	mysql_query("delete from time_hourly where DAY(time)=(DAY(date_sub(now(), INTERVAL 2 DAY)))");
}
 
if($day['minutes']==0){
	mysql_query("insert into time_hourly(time, count) values(date_sub(NOW(), INTERVAL 1 HOUR), (select round(avg(count)) from time where hour(time)=(hour(date_sub(now(), INTERVAL 1 HOUR))) ))");
	echo mysql_error(); 
	mysql_query("delete from time where hour(time)<=(hour(date_sub(now(), interval 2 hour)))");	
}	

	mysql_query("insert into time(time, count) values(now(), (select count(*) from halo_leaderboard.names where server!=0))");
	
	echo mysql_error();

?>