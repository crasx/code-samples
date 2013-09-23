<?php 
if(!isset($_GET['go'])){
header( 'Status: 404' ); header("HTTP/1.0 404 Not Found");
exit;
}
$dat=$_GET['srt'];
$srts=array("ip"=>"ip","user"=>"user","seen"=>"seen","hits"=>"hits");
$sql="select * from ip ";
if(isset($_GET['srt']))
if(in_array($dat,$srts))	{
$sql.="order by $dat";
if(!isset($_GET['way'])){
	$srts[$dat].="&way=1";
}else $sql.=" desc";
}
echo "<table border='1'>
<tr>
<th><a href='data.php?go=1&srt=".$srts['ip']."'>IP</a></th>
<th><a href='data.php?go=1&srt=".$srts['user']."'>Forum username</a></th>
<th><a href='data.php?go=1&srt=".$srts['seen']."'>Last seen</a></th>
<th><a href='data.php?go=1&srt=".$srts['hits']."'>Page views</a></th></tr>";
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
	//////////////end connection
	$result=mysql_query($sql);
while($row=mysql_fetch_array($result))
{
echo "<tr>";
echo "<td>" .$row['ip']."</td>";
echo "<td>" .$row['user']."&nbsp;</td>";
echo "<td>" .$row['seen']."</td>";
echo "<td>" .$row['hits']."</td>";
}
echo "</table>";


?>