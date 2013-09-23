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
	
	
$dbhost = 'localhost';
$dbuser = 'halo_remote';
$dbpass = 'O7ROdnRU8oifZMz';
$dbname = 'halo_advanced';
if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
echo "Error, can't connect right now because".mysql_error();
exit;
}
mysql_select_db($dbname);

  echo "<h2>Return to</h2>
  <a href=scoreboard.php >Scoreboard</a><br />
  <a href=scoreboard.php?t=W >Weekboard</a><br />
  <a href=scoreboard.php?t=D >Dayboard</a><br />
  <a href='javascript::History.go(-1)'>Previous page</a><br />
<br />
<hr width=100%>"; 
if(isset($_GET['p'])){
$p=mysql_real_escape_string($_GET['p']);
$quer=mysql_query("select * from names where id='$p'");
if(mysql_num_rows($quer)==0){
echo "<h1>person not found</h1>";	
}else{
	$par=mysql_fetch_array($quer);
	$pid=$p;
	$p=htmlentities($par['name']);
$stats=array(
			 "CTF"=>array("Scores"=>0,"Captures"=>0,"Returns"=>0,"Kills"=>0,"Deaths"=>0,"Kill death ratio"=>0,"Assists"=>0,"Suicides"=>0,"Betrays"=>0, "Time online"=>0, "Last seen"=>0),
			 "Slayer"=>array("Scores"=>0,"Kills"=>0,"Deaths"=>0,"Kill death ratio"=>0,"Assists"=>0,"Suicides"=>0,"Betrays"=>0, "Time online"=>0, "Last seen"=>0),
			 "Race"=>array("Laps"=>0,"Kills"=>0,"Deaths"=>0,"Kill death ratio"=>0,"Assists"=>0,"Suicides"=>0,"Betrays"=>0, "Time online"=>0, "Last seen"=>0),
			 "Oddball"=>array("Time"=>0,"Kills"=>0,"Deaths"=>0,"Kill death ratio"=>0,"Assists"=>0,"Suicides"=>0,"Betrays"=>0, "Time online"=>0, "Last seen"=>0),
			 "King"=>array("Time"=>0,"Kills"=>0,"Deaths"=>0,"Kill death ratio"=>0,"Assists"=>0,"Suicides"=>0,"Betrays"=>0, "Time online"=>0, "Last seen"=>0)
			 );

$qr=mysql_query("select c.scores Scores, c.captures Captures, c.returns Returns, c.kills Kills, c.deaths Deaths, c.assists Assists, ifnull(c.kills/c.deaths,0) 'Kill death ratio', c.suicides Suicides, c.betrays Betrays, c.online 'Time online', c.joined 'Last seen' from scores_ctf c where c.name=$pid ");

if($q=mysql_fetch_array($qr)){
	$rk=array_keys($q);
for($i=1;$i<count($rk);$i+=2){
	$stats['CTF'][$rk[$i]]=$q[$rk[$i]];	
}
}

$qr=mysql_query("select c.scores Scores, c.kills Kills, c.deaths Deaths, c.assists Assists, ifnull(c.kills/c.deaths,0) 'Kill death ratio', c.suicides Suicides, c.betrays Betrays, c.online 'Time online', c.joined 'Last seen' from scores_slayer c where c.name=$pid ");
if($q=mysql_fetch_array($qr)){
	$rk=array_keys($q);
for($i=1;$i<count($rk);$i+=2){
	$stats['Slayer'][$rk[$i]]=$q[$rk[$i]];	
}
}

$qr=mysql_query("select c.laps Laps, c.kills Kills, c.deaths Deaths, c.assists Assists, ifnull(c.kills/c.deaths,0) 'Kill death ratio', c.suicides Suicides, c.betrays Betrays, c.online 'Time online', c.joined 'Last seen' from scores_race c where c.name=$pid ");
if($q=mysql_fetch_array($qr)){
	$rk=array_keys($q);
for($i=1;$i<count($rk);$i+=2){
	$stats['Race'][$rk[$i]]=$q[$rk[$i]];	
}
}

$qr=mysql_query("select sec_to_time(c.scores) Time, c.kills Kills, c.deaths Deaths, c.assists Assists, ifnull(c.kills/c.deaths,0) 'Kill death ratio', c.suicides Suicides, c.betrays Betrays, c.online 'Time online', c.joined 'Last seen' from scores_oddball c where c.name=$pid ");
if($q=mysql_fetch_array($qr)){
	$rk=array_keys($q);
for($i=1;$i<count($rk);$i+=2){
	$stats['Oddball'][$rk[$i]]=$q[$rk[$i]];	
}
}

$qr=mysql_query("select sec_to_time(c.scores) Time, c.kills Kills, c.deaths Deaths, c.assists Assists, ifnull(c.kills/c.deaths,0) 'Kill death ratio', c.suicides Suicides, c.betrays Betrays, c.online 'Time online', c.joined 'Last seen' from scores_king c where c.name=$pid ");
if($q=mysql_fetch_array($qr)){
	$rk=array_keys($q);
for($i=1;$i<count($rk);$i+=2){
	$stats['King'][$rk[$i]]=$q[$rk[$i]];	
}
}

echo "<h1>Profile for ".$p."</h1>";
mkmenu("Stats");
echo "<table border=1>";
$showk=array("Kills"=>0,"Deaths"=>0,"Kill death ratio"=>0,"Assists"=>0, "Suicides"=>0,"Betrays"=>0, "Time online"=>0,"Awards"=>"");
foreach($stats as $k=>$v){
foreach($showk as $sk=>$v2){
$showk[$sk]+=$v[$sk];
}
}
/*
$showk['Awards']="";
$qr=mysql_query("select date_format(week, '%V in %X') week from historyctf where name=$pid ");
while($q=mysql_fetch_array($qr)){
$showk['Awards'].="CTF leader for week ".$q['week']."<br>";
}
$qr=mysql_query("select date_format(week, '%V in %X') week from historyslayer where name=$pid ");
while($q=mysql_fetch_array($qr)){
$showk['Awards'].="Slayer leader for week ".$q['week']."<br>";
}

$qr=mysql_query("select date_format(week, '%V in %X') week from historyrace where name=$pid ");
while($q=mysql_fetch_array($qr)){
$showk['Awards'].="Race leader for week of ".$q['week']."<br>";
}

$qr=mysql_query("select date_format(week, '%V in %X') week from historyoddball where name=$pid ");
while($q=mysql_fetch_array($qr)){
$showk['Awards'].="Oddball leader for week ".$q['week']."<br>";
}

$qr=mysql_query("select date_format(week, '%V in %X') week from historyking where name=$pid ");
while($q=mysql_fetch_array($qr)){
$showk['Awards'].="King leader for week ".$q['week']."<br>";
}
*/
$showk['Kill death ratio']=$showk['Deaths']==0?0:$showk['Kills']/$showk['Deaths'];
$showk['Awards']=$showk['Awards']==""?"None":$showk['Awards'];
$showk["Time online"]=Sec2Time($showk["Time online"]);

foreach($showk as $k=>$v){
echo "<tr><td>{$k}</td><td>$v</td></tr>";	
}
echo "</table>";
mkdone();
echo "<br><table><tr>"; 	
foreach($stats as $k=>$v){
	echo "<td valign=top >";
	mkmenu($k);
	$v["Time online"]=Sec2Time($v["Time online"]);
	echo "<table border=1>";
	foreach($v as $k1=>$v1){
		echo "<tr><td>{$k1}</td><td>{$v1}</td></tr>";
	}
	echo "</table>";
	mkdone();
	echo "</td>";
}
echo"</tr></table>";
}
}else
echo "<h1>No player selected</h1><br>
<br>
<br>
";
echo "</div>";

function mkmenu($title){
echo '  <TABLE border="0"  cellpadding="0" cellspacing="0">
	      <TR  align="top">
					  	  <TD height="30px" >
                            <h4>'.
                            $title.'
                            </h4>
						  </TD>
					  </TR>
			   		  <TR >
					  	  <TD  align="center" class="menubody">
';	
}
function mkdone(){
echo '</TD>
					  </TR>
			   </TABLE>';	
}
function Sec2Time($time){
  if(is_numeric($time)){
	  $r="";
    if($time >= 3600){
      $i= floor($time/3600);
	  $r.= ($i<10?"0":"")."$i:";
      $time = ($time%3600);
    }else $r.="00:";
    if($time >= 60){
      $i = floor($time/60);
	  $r.= ($i<10?"0":"")."$i:";
      $time = ($time%60);
    }else $r.="00:";
	  $r.= $time==0?"00":($time<10?"0":"")."$time";
     return $r;
    
  }else{
    return (bool) FALSE;
  }
}

$template->assign_vars(array(
	'HEADERTEXT'		=> "Profile",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?> 