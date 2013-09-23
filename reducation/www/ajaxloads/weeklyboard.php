<?php 
	$bodu="";
	
$dbuser = 'halo';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbschema="halo_advanced";
$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
mysql_select_db($dbschema);
$cols=array("CTF"=>array("scores_ctf", "scores_w"),
			"CTF Captures"=>array("scores_ctf", "captures_w"),
			"CTF Returns"=>array("scores_ctf", "returns_w"),
			"Slayer scores"=>array("scores_slayer", "scores_w"),
			"Race"=>array("scores_race", "laps_w"),
			"King"=>array("scores_king", "scores_w"),
			"Oddball"=>array("scores_oddball", "scores_w"));

$cols2=array("Kills"=>"Kills_w", "Assists"=>"assists_w", "Deaths"=>"deaths_w", "Suicides"=>"suicides_w", "Betrays"=>"betrays_w", "Online"=>"online_w");
	
	foreach(array_keys($cols) as $k){
		echo $k."<br />";
$qr=mysql_query("select  n.name, c.".$cols[$k][1]." scores from names n, ".$cols[$k][0]." c where c.name=n.id and n.common=0 order by 2 desc limit 1");
echo mysql_error();
while($q=mysql_fetch_array($qr)){
echo "<a href='/scoreboard.php?t=D&search=".urlencode($q['name'])."'>".htmlentities(stripslashes($q['name']))." (".$q['scores'].")</a><br />
";
}
	}
	echo "<br />Advanced areas temporarily disabled";/*
	foreach(array_keys($cols2) as $k){
		echo $k."<br />";
		$f=$cols2[$k];
	$qr=mysql_query("select n.name, c.".$f."+s.".$f."+r.".$f."+k.".$f."+o.".$f." scores, c.online_w+s.online_w+r.online_w+k.online_w+o.online_w online_w from names n
	 left join scores_ctf c on n.id=c.name
	left join scores_slayer s on n.id=s.name
	left join scores_race r on n.id=r.name
	left join scores_king k on n.id=k.name
	left join scores_oddball o on n.id=o.name where n.common=0 order by 2 desc, n.name limit 1");
echo mysql_error();
while($q=mysql_fetch_array($qr)){
echo "<a href='/scoreboard.php?t=D&search=".urlencode($q['name'])."'>".htmlentities(stripslashes($q['name']))." (".$q['scores'].")</a><br />
";
}
	}
	*/
?>