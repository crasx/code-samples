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



$dbuser="halo";
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'halo_leaderboard';
$conn = mysql_connect('127.0.0.1', $dbuser, $dbpass);
mysql_select_db($dbname);


$cols=array("CTF","Slayer","Race","Oddball","King");
echo '  <center> <TABLE border="0" bordercolor="white"  cellpadding="0" cellspacing="7" >
			   		  <TR bordercolor="black">
					  	  <TD  height="30px" >
						  <CENTER><B>CTF</B></CENTER>
						  </TD>
					  	  <TD  height="30px" >
						  <CENTER><B>Slayer</B></CENTER>
						  </TD>
					  	  <TD  height="30px" >
						  <CENTER><B>Race</B></CENTER>
						  </TD>
					  </TR>
			   		  <TR bordercolor="black">
			';
for($i=0;$i<3;$i++){
	echo '<TD  align="center">';
	$qr=mysql_query("select n.name, s.".$cols[$i]." scores from names n, scores_2 s where s.name=n.id order by s.".$cols[$i]." desc limit 10");
	while($q=mysql_fetch_array($qr)){
	echo htmlspecialchars($q['name'])." (".$q['scores'].")<br>
	";
	}
	echo '</TD>';
}
echo '</TR> <TR bordercolor="black">
					  	  <TD height="30px" >
						  <CENTER><B>Oddball</B></CENTER>
						  </TD>
					  	  <TD  height="30px" >
						  <CENTER><B>King</B></CENTER>
						  </TD>
					  </TR><TR>';
for($i=3;$i<5;$i++){
	echo '<TD  align="center">';
	$qr=mysql_query("select n.name, s.".$cols[$i]." scores from names n, scores_2 s where s.name=n.id order by s.".$cols[$i]." desc limit 10");
	while($q=mysql_fetch_array($qr)){
	echo htmlspecialchars($q['name'])." (".$q['scores'].")<br>
	";
	}
	echo '</TD>';
}
echo '</TABLE></center>';



$template->assign_vars(array(
	'HEADERTEXT'		=> "Top scoreboard names",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();

?>