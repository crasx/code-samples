<?php

	$fields=array(array("CTF", 0, "scores_ctf", "scores"), array("Captures", 0, "scores_ctf", "captures"), array("Returns", 0, "scores_ctf", "returns"), array("CTF kills", 0, "scores_ctf", "kills"), array("CTF deaths", 0, "scores_ctf", "deaths"), array("CTF suicides", 0, "scores_ctf", "suicides"), array("CTF betrays", 0, "scores_ctf", "betrays"), array("CTF assists", 0, "scores_ctf", "assists"), array("CTF time", 0, "scores_ctf", "sec_to_time(online)"), 
	array("Slayer", 0, "scores_slayer", "scores"), array("Slayer kills", 0, "scores_slayer", "kills"), array("Slayer deaths", 0, "scores_slayer", "deaths"), array("Slayer suicides", 0, "scores_slayer", "suicides"), array("Slayer betrays", 0, "scores_slayer", "betrays"), array("Slayer assists", 0, "scores_slayer", "assists"), array("Slayer time", 0, "scores_slayer", "sec_to_time(online)"), 
	array("Race", 0, "scores_race", "laps"), array("Race kills", 0, "scores_race", "kills"), array("Race deaths", 0, "scores_race", "deaths"), array("Race suicides", 0, "scores_race", "suicides"), array("Race betrays", 0, "scores_race", "betrays"), array("Race assists", 0, "scores_race", "assists"), array("Race time", 0, "scores_race", "sec_to_time(online)"), 
	array("King", 0, "scores_king", "scores"), array("King kills", 0, "scores_king", "kills"), array("King deaths", 0, "scores_king", "deaths"), array("King suicides", 0, "scores_king", "suicides"), array("King betrays", 0, "scores_king", "betrays"), array("King assists", 0, "scores_king", "assists"), array("King time", 0, "scores_king", "sec_to_time(online)"), 
	array("Oddball", 0, "scores_oddball", "scores"), array("Oddball kills", 0, "scores_oddball", "kills"), array("Oddball deaths", 0, "scores_oddball", "deaths"), array("Oddball suicides", 0, "scores_oddball", "suicides"), array("Oddball betrays", 0, "scores_oddball", "betrays"), array("Oddball assists", 0, "scores_oddball", "assists"), array("Oddball time", 0, "scores_oddball", "sec_to_time(online)"), 
	array("All kills", 1, "", "kills"), array("All deaths", 1, "", "deaths"), array("All suicides", 1, "", "suicides"), array("All betrays", 1, "", "betrays"), array("All assists", 1, "", "assists"), array("All time", 1, 1, "online"),);

class cache{
	
	function inCache($id, $path){
		global $cacheD;
		if(!is_numeric($id))return;
		
		$dbhost = '127.0.0.1';
		$dbuser="halo";
		$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
		$dbname = 'halo_phpbb';
		if(!($conn = mysql_connect($dbhost, $dbuser, $dbpass))){
		echo "Error, can't connect right now because".mysql_error();
		exit;
		}
		mysql_select_db($dbname);

		$que=mysql_query("select h.pf_halo_name username, u.user_avatar, u.user_avatar_type, u.user_avatar_width, u.user_avatar_height  from users u, profile_fields_data h where u.user_id=h.user_id and u.user_id=$id");

		$av=array("", "", 0, 0, 0);
		if($r=mysql_fetch_array($que)){
			$av=array($r['username'], $r['user_avatar'], $r['user_avatar_type'], $r['user_avatar_width'], $r['user_avatar_height']);
			
		}
		if($av[0]=="")return false;
//		if(file_exists($path)&&time()-filemtime($path)<160) return true;
		
		$vals=array(0, 9, 16, 23, 30, 37, 42);
		$que=mysql_query("select * from leaderboard_profile where user=$id");
		if($r=mysql_fetch_array($que)){
			for($i=0;$i<6;$i++){
				$vals[$i]=$r[$i];	
			}
		}
		
		if(!file_exists($cacheD[$t][$id]))$this->recache($av, $vals, $path, $id);
		
		return true;
	}
	
	function recache($av, $vals, $path, $id){
		global $fields;
		$rl=array(100, 27, 
				  223, 27, 
				  347, 27, 
				  100, 50, 
				  223, 50, 
				  347, 50,
				  347, 76);
		$image=imagecreatefrompng("c:\\root\\reclan\\lbp\\template2.png");
		$black = imagecolorallocate($image, 0, 0, 0);
		$white = imagecolorallocate($image, 255, 255, 255);
		$bgc = imagecolorallocate($image, 101, 105, 109);
		
		if($av[2]!=0){
			imagefilledrectangle($image, 11, 8, 79, 76, $bgc);
			$ext = end(explode('.', $av[1]));
			if($av[2]==1)$av[1]="http://reclan.com/forum/download/file.php?avatar=".$av[1];
			switch($ext){
				case "png":
					$im2=imagecreatefrompng($av[1]);
				break;
				case "gif":
					$im2=imagecreatefromgif($av[1]);				
				break;
				case "jpg":case "jpeg":
					$im2=imagecreatefromjpeg($av[1]);				
				break;
			}		
			$l=array(11, 8, 68, 68);
			if($av[3]<68){
				$l[0]=11+(68-$av[3])/2;
				$l[2]=$av[3];
			}
			if($av[4]<68){
				$l[1]=11+(68-$av[3])/2;
				$l[3]=$av[4];
			}
			imagecopyresized($image, $im2, $l[0], $l[1], 0, 0, $l[2], $l[3], $av[3], $av[4]);
//		imagecopymerge($image, $im2, 13+(100-$av[3])/2, 13+(100-$av[4])/2, 0, 0, $av[3], $av[4], 100);
		}
		$filename=$path.$id;	
		putenv('GDFONTPATH=C:\WINDOWS\Fonts');
		$font = getenv('GDFONTPATH') . '\Arial.ttf';
		imagettftext($image, 11, 0, 95, 76, $white, $font, $av[0]);
		$av[0]=mysql_real_escape_string($av[0]);
		$nid=0;
		mysql_select_db("halo_advanced");
		
		$que=mysql_query("select * from names where name='".$av[0]."'");
		if($que)if($r=mysql_fetch_array($que)){
			$nid=$r['id'];
		}
		foreach($vals as $k=>$v){
		$vi=0;
		if($fields[$v][1]==0)
		$qr=("select ".$fields[$v][3]." score from ".$fields[$v][2]." where name=".$nid);
		else
		$qr=("select ".($fields[$v][2]==1?"sec_to_time(":"")."ifnull(c.".$fields[$v][3].",0)+ifnull(s.".$fields[$v][3].",0)+ifnull(r.".$fields[$v][3].",0)+ifnull(k.".$fields[$v][3].",0)+ifnull(o.".$fields[$v][3].",0)".($fields[$v][2]==1?")":"")." score from names n
	 left join scores_ctf c on n.id=c.name
	left join scores_slayer s on n.id=s.name
	left join scores_race r on n.id=r.name
	left join scores_king k on n.id=k.name
	left join scores_oddball o on n.id=o.name where n.id=".$nid);
		$que=mysql_query($qr);
		
		if($r=mysql_fetch_array($que)){
			$vi=$r['score'];
		}
		if($k!=6)
			imagettftext($image, 8, 0, $rl[$k*2], $rl[($k*2)+1], $black, $font, $fields[$v][0].": ".$vi);
			else
			imagettftext($image, 8, 0, $rl[$k*2], $rl[($k*2)+1], $white, $font, $fields[$v][0].": ".$vi);
			
		}
		
		imagepng($image, $filename);
		imagedestroy($image);
		
	}
	
	
		
}