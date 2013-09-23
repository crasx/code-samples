<?php
if ($_SERVER['HTTP_X_FORWARD_FOR']) {
$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
} else {
$ip = $_SERVER['REMOTE_ADDR'];
}


$dbuser = 'halo';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbschema="halo_leaderboard";
$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
mysql_select_db($dbschema);
if(isset($_POST['pass'])&&isset($_POST['port'])){
	if(strcmp($_POST['pass'],"C9C1D0933546B125229B1D2")==0){
		if(!mysql_query("insert into servers(ip,port, account) values('$ip','".addslashes($_POST['port'])."',2)")){
			echo "Error in query";
			exit;
		}else echo "OK";
	}else echo "Invaid pass";
}else echo "Invaid password";
?>