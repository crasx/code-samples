<?php 
	$bodu="";
	
$dbuser = 'halo';
$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
$dbschema="halo_advanced";
$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
mysql_select_db($dbschema);
$cols=array("CTF"=>array("scores_ctf", "scores"),
			"CTF Captures"=>array("scores_ctf", "captures"),
			"CTF Returns"=>array("scores_ctf", "returns"),
			"Slayer scores"=>array("scores_slayer", "scores"),
			"Race"=>array("scores_race", "laps"),
			"King"=>array("scores_king", "scores"),
			"Oddball"=>array("scores_oddball", "scores"));

$cols2=array("Kills"=>"Kills", "Assists"=>"assists", "Deaths"=>"deaths", "Suicides"=>"suicides", "Betrays"=>"betrays", "Online"=>"online");
	
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
	$qr=mysql_query("select n.name, ifnull(c.".$f.",0)+ifnull(s.".$f.",0)+ifnull(r.".$f.",0)+ifnull(k.".$f.",0)+ifnull(o.".$f.",0) scores, c.online+s.online+r.online+k.online+o.online online from names n
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