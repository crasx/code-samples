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


$dbuser="halo";
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbname = 'halo_leaderboard';
$conn = mysql_connect('127.0.0.1', $dbuser, $dbpass);
mysql_select_db($dbname);

$que=mysql_query("select id,name,seen,ip,port,maxplayers,map,type from servers s where s.account=2 order by s.name");

$cols=4;
echo "<table border=1><tr>";
$i=0;
if($que)while($r=mysql_fetch_array($que)){
	
	if($i==$cols){
		echo "</tr><tr>";
		$i=0;
	}
		echo "<td valign=top >";	
	
		echo "<h3><u>".($r['name']==""?"Unknown":$r['name'])."</u></h3>Last seen:".($r['seen']==""?"Never":$r['seen'])."<br />".$r['ip'].":".$r['port']."<br />".mysql_num_rows(mysql_query("select * from names where server=".$r['id']))." of ".$r['maxplayers']." taken<br /> Currently playing ".$r['type']." on ".$r['map']."<br /><br />
		<h3>Players:</h3>
";		
$que2=mysql_query("select name from names where server=".$r['id']);
if($que2)while($r2=mysql_fetch_array($que2)){
	echo $r2['name']."<br />";
}
echo '<br /><br />';
	$i++;
		echo "</td>";	
}
echo "</tr></table>";


$template->assign_vars(array(
	'HEADERTEXT'		=> $title,
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();

?>