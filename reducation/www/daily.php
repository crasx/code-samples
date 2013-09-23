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

    page_header('Leaderboard');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
	
	
$title="Daily records";


$dbuser="halo";
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'halo_advanced';
$conn = mysql_connect('127.0.0.1', $dbuser, $dbpass);
mysql_select_db($dbname);
$cols=array("CTF"=>array("scores_ctf", "scores_d", 0),
			"CTF Captures"=>array("scores_ctf", "captures_d",5),
			"CTF Returns"=>array("scores_ctf", "returns_d", 6),
			"Slayer scores"=>array("scores_slayer", "scores_d", 2),
			"Race"=>array("scores_race", "laps_d",3),
			"King"=>array("scores_king", "sec_to_time(scores_d)",5),
			"Oddball"=>array("scores_oddball", "sec_to_time(scores_d)",4));

$cols2=array("Kills"=>"Kills_d", "Assists"=>"assists_d", "Deaths"=>"deaths_d", "Suicides"=>"suicides_d", "Betrays"=>"betrays_d", "Online"=>"online_d");
	echo '  <center> <TABLE border="0" bordercolor="white"  cellpadding="0" cellspacing="7" >
			   		  <TR bordercolor="black">
					  	  <TD  height="30px" >
						  <CENTER><B>History</B></CENTER>
						  </TD>
					  	  <TD  height="30px" >
						  <CENTER><B>Today</B></CENTER>
						  </TD>
					  </TR>
';

foreach(array_keys($cols) as $th){
	echo '<TR>
	<TH colspan=2>'.$th.'</TH></TR>
	<tr>
	<TD>';
	if($cols[$th][2]!=4&&$cols[$th][2]!=5)
	$qr=mysql_query("select n.name, s.score, s.day from names n, daily s where s.name=n.id and s.field=".$cols[$th][2]." order by s.score desc");
	else 
		$qr=mysql_query("select n.name, sec_to_time(s.score) score, s.day from names n, daily s where s.name=n.id and s.field=".$cols[$th][2]." order by s.score desc");
	while($q=mysql_fetch_array($qr)){
	echo "<li>(".$q['score'].") ".htmlspecialchars($q['name'])." on ".date("n/d/Y", $q['day'])."</li>";
	}
	echo '</ol></TD><TD>';
		$qr=mysql_query("select n.name, s.".$cols[$th][1]." scores from names n, ".$cols[$th][0]." s where s.name=n.id order by 2 desc limit 10");

	echo "<ol>";
	while($q=mysql_fetch_array($qr)){
		
	echo "<li> (".$q['scores'].") ".htmlspecialchars($q['name'])."</li>";
	}
	echo '</ol></TD></TR>';
}

/*//////////
$rk=array_keys($cols2);
for($i=7;$i<13;$i++){
	$th=$rk[$i];
	echo '<TR>
	<TH colspan=2>'.$th.'</TH></TR>
	<tr>
	<TD>';
	if($cols[$th][2]!=4&&$cols[$th][2]!=5)
	$qr=mysql_query("select n.name, s.score, s.day from names n, daily s where s.name=n.id and s.field=".$cols[$th][2]." order by s.score desc");
	else 
		$qr=mysql_query("select n.name, sec_to_time(s.score) score, s.day from names n, daily s where s.name=n.id and s.field=".$cols[$th][2]." order by s.score desc");
	while($q=mysql_fetch_array($qr)){
	echo "<li>(".$q['score'].") ".htmlspecialchars($q['name'])." on ".date("n/d/Y", $q['day'])."</li>";
	}
	echo '</ol></TD><TD>';
		$qr=mysql_query("select n.name, s.".$cols[$th][1]." scores from names n, ".$cols[$th][0]." s where s.name=n.id order by 2 desc limit 10");

	echo "<ol>";
	while($q=mysql_fetch_array($qr)){
		
	echo "<li> (".$q['scores'].") ".htmlspecialchars($q['name'])."</li>";
	}
	echo '</ol></TD></TR>';
}*/
echo "</table>";


$template->assign_vars(array(
	'HEADERTEXT'		=> $title,
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();

?>