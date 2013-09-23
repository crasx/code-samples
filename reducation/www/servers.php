<?php
ob_start();

define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Server list');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));

$dbuser = 'halo_remote';
$dbpass = 'y9bZTQMMblv9zls';
$dbname = 'halo_advanced';
$conn = mysql_connect('127.0.0.1', $dbuser, $dbpass);
mysql_select_db($dbname);

$stemplate="<td align=center valign=top ><u>{Server}</u><br />
<table cellspacing=3 cellpadding=3 ><tr><td valign=top >
{ip}:{port}<br />
Playing <b>{Gametype}</b> on <b>{Current map}</b><br /><table>";


$que=mysql_query("select id, name Server, map 'Current map', type Gametype, ip, port, TIME_TO_SEC(TIMEDIFF(now(), online)) as Uptime, afk 'Afk kicks', ping 'Ping kicks', teamswitch 'Teamswitches', teamswitchk 'Teamswitch kicks', insultw 'Insult warns', insultk 'Insult kicks', namek 'Name kicks', betrayk 'Betray kicks', mapskips 'Map skips'  from servers where online!=0");

echo "<table cellpadding=3 cellspacing=4 width=100% >";
echo mysql_error();
$fr=true;
$i=0;

if($que)while($r=mysql_fetch_array($que, MYSQL_ASSOC)){
	if($i==0)echo "<tr>";
		$rk=array_keys($r);		
		$colz=0;
		$t1=$stemplate;
		foreach($rk as $ct){
			if($colz<6)$t1=str_replace("{".$ct."}", str_replace("\x01", "", $r[$ct]), $t1);
			else $t1.="<tr><td><b>".$ct."</b>:</td><td>".$r[$ct]."</td></tr>";
			$colz++;
		}
		echo $t1;
		echo "</table></td>\n";
		
		echo "<td valign=top >";
		$que2=mysql_query("select name from names where server=".$r['id']);
		if($que2)while($r2=mysql_fetch_array($que2)){
			echo htmlentities($r2['name'])."<br />";
		}
		
		echo "</td></tr></table>";
		$i++;
		if($i==2){
			echo "</tr>";
			$i=0;
		}
}

$b=false;
if($i!=0)
for(;$i<2;$i++){
	echo "<td>&nbsp;</td>";
	$b=true;
}
if($b)echo "</tr>";

echo "</table>";


$template->assign_vars(array(
	'HEADERTEXT'		=> $title,
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();

?>