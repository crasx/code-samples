<?php
error_reporting(E_ALL ^ E_NOTICE);
 
/*if ($_SERVER['HTTP_X_FORWARD_FOR']) {
$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
} else {
*/$ip = $_SERVER['REMOTE_ADDR'];
//}

global $user;
	$dbhost = '127.0.0.1';	
	$dbuser = 'halo';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
	if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
		echo "<div class='mb'><div class='body1'>";
		echo "Error, can't connect right now"	;
		echo "</div></div>";
		exit;	
		}	
	$dbname = 'halo_ips';
	mysql_select_db($dbname);
	//////////////end connection
	$url="http://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
	if(preg_match("/[?&](sid=)[a-f0-9]*/", $url))
	{
		preg_match_all("/[?&](sid=)[a-f0-9]*/", $url,$out);
		$url= substr($url,0,strpos($url,$out[0][0])+1).substr($url,strpos($url,$out[0][0])+38);
	}
mysql_query("insert into ips(ip) values('$ip')");
if($user->data['username']!=""&&$user->data['username']!="Anonymous"){	
mysql_query("insert into user_ips(user, ip, date) values('".$user->data['user_id']."' ,(select id 'ip' from ips where ip='$ip'), now()) on duplicate key update date=now()");
mysql_query("insert into user_ip_pageviews(user_ip_id, page, date) values((select id 'user_ip_id' from user_ips where user='".$user->data['user_id']."' and ip=(select id 'ip' from ips where ip='$ip')),'$url',now()) on duplicate key update times=times+1");

}else{
	mysql_query("insert into user_ips(user, ip, date) values('0' , (select id 'ip' from ips where ip='$ip'), now()) on duplicate key update date=now()");
mysql_query("insert into user_ip_pageviews(user_ip_id, page, date) values((select id 'user_ip_id' from user_ips where user='0' and ip=(select id 'ip' from ips where ip='$ip')),'$url',now()) on duplicate key update times=times+1");
}
mysql_query("insert into days(date,count) values (now(),1) on duplicate key update count=count+1");
if(stristr($_SERVER['PHP_SELF'],"/hits/count.php")){
$scale=isset($_GET['scale'])?$_GET['scale']:50;
echo "<form action=count.php method=get ><input type=text name=scale /><input type=submit value='set scale' /></form><br />
1 * = $scale page views<br />";
$indb=mysql_query("select * from days order by date");
while($row=mysql_fetch_assoc($indb)){
echo $row['date'];
for($i=0;$i<$row['count']/$scale;$i++){echo "*";}
echo "[".$row['count']."]<br />";
}
mysql_free_result($indb);
}


?>