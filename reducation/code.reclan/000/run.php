<?php
 require("../GameQ.php");
 set_time_limit(0);
if(isset($_GET['sid'])&&is_numeric($_GET['sid'])){
	$dbuser = 'halo';
	$dbpass = 'E$U!wf29"$9X}%>82tky~HH';
	$dbname = 'halo_leaderboard';
	$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
	mysql_select_db($dbname);
	
	$sid=$_GET['sid'];
	$aid=0;
	
	echo $sid;
	
	$map="";
	$type="";
	
	$doWeek=false;
	if($s=mysql_fetch_array(mysql_query("select * from servers where id=".$sid))){
		$ip=$s['ip'];
		$aid=$s['account'];

		$port=$s['port'];			
		$map=$s['map'];
		$type=$s['type'];
		
	}else die("no such server");
	$servers=array();
	$servers[]=array("halo",$ip,$port);
	
	$gq = new GameQ;

	
	// Add the servers defined earlier
	try {
		$gq->addServers($servers);
	}
	catch (Exception $e) {
	 //   print 'One of the server entries was not defined correctly.';
	 echo $e;
		exit;
	}
	
	$gq->setOption('timeout', 10000);     // Socket timeout in ms
	$gq->setFilter('normalise');
	try {
		$results = $gq->requestData();
	}
	catch (Exception $e) {
		print 'An error occurred while requesting or processing data.';
		exit;
	}
	// Display the data using a simple table
	$playerids=array();
	foreach ($results as $id => $result) {
		if(array_key_exists("hostname",$result)){
			$hn=mysql_real_escape_string(preg_replace('/\x01/','',$result['hostname']));
			$lookup=(strcmp($map,$result['mapname'])==0&&strcmp($type,$result['gametype'])==0);
			mysql_query("update servers set seen=now() , name='".$hn."', map='".mysql_real_escape_string($result['mapname'])."', type='".mysql_real_escape_string($result['gametype'])."',maxplayers='".mysql_real_escape_string($result['maxplayers'])."' where id='".$sid."'"); 
			$col=strtolower($result['gametype']);
			$joined=0;
			$oldscore=0;
			 foreach ($result['players'] as $player) {																																																	
			  $thisplayer=mysql_query("select * from names where name='".mysql_real_escape_string($player['player'])."'");		
				  if($tpr=mysql_fetch_array($thisplayer)){
					if($lookup){
						if($tpr['common']==1){
							echo "common";
							continue;
						}
						if($tpr['server']==$sid){
							$oldscore=$tpr['score'];
							$joined=$tpr['joined'];
						}
					 }
			}
			$joined=$joined==0?time():$joined;
				 $onlinescr=0;
				  switch($col){
					case 'king':
					case 'oddball':
					$spl=explode(":", $player['score']);
					foreach($spl as $k=>$v){if(!is_numeric($v))$spl[$k]=0;}
					if(count($spl)==2){
					$tmpto=$spl[0]*60;
					$tmpto+=$spl[1];
					}else $tmpto=$spl[0];
					  $onlinescr=$tmpto;
					if($oldscore>$tmpto)$toadd=$tmpto;
					else $toadd=$tmpto-$oldscore;
					break;
					case 'slayer':		//this can go back one			
					$onlinescr=$player['score'];
					 if($player['score']<$oldscore&&$player['score']<($oldscore/2)&&($oldscore>0))$toadd=$player['score'];
					else $toadd=$player['score']-$oldscore;	
					break;
					default:
					$onlinescr=$player['score'];
					 if($player['score']<$oldscore)$toadd=$player['score'];
					else $toadd=$player['score']-$oldscore;				
				  }
				 mysql_query("insert into names(name, server, score, joined) values('".mysql_real_escape_string($player['player'])."', ".$sid.", '".$onlinescr."', ".time()." ) on duplicate key update server=".$sid.", score='".$onlinescr."', joined=".time());
				  $plid=0;
				$que=mysql_query("select id from names where name='".mysql_real_escape_string($player['player'])."'");
				if($que)while($r=mysql_fetch_array($que)){
					$plid=$r['id'];
				}
				$wki=array("","","");
				$wki[0]=$col."W, ";
				$wki[1]=$toadd.", ";
				$wki[2]=$col."W=".$col."W+".$toadd.", onlineW=onlineW+".(time()-$joined).",";
				//day
				$wki[0].=$col."D, ";
				$wki[1].=$toadd.", ";
				$wki[2].=$col."D=".$col."D+".$toadd.", onlineD=onlineD+".(time()-$joined).",";
			
				$playerids[]=$plid;
				$query="insert into  scores (name, ".$col.",  online) values($plid, $toadd, 0 ) on duplicate key update $col=$col+$toadd, online=online+".(time()-$joined);
				$query2="insert into scores_".$aid."  (name, ".$col.",".$wki[0]."online) values ($plid, $toadd, ".$wki[1]." 0) on duplicate key update $col=$col+$toadd, ".$wki[2]." online=online+".(time()-$joined);
				  mysql_query($query);	
				  
				  echo "<br />".mysql_error();		  
				  mysql_query($query2);
				  echo "<br />".mysql_error();
			}//end players	
			
		}//end hostname
	}//end result
	
			  $pids="'".implode("','",$playerids)."'";
				mysql_query("update names set server=0,joined=0 where (server=".$sid." and id not in($pids)) or server in(select id from servers where unix_timestamp(now())-unix_timestamp(seen)>(60*60*3))");
	mysql_query("delete from servers where unix_timestamp(now())-unix_timestamp(seen)>(60*60*3) and nodelete=0");
}	?>