<?php
/*

*/
//header("Content-Type: application/rss+xml");
require_once("adm/cfg.php");

$finished=mysql_query("select * from ".T_COMPS." where finished!=0 order by finished desc");
	$br= htmlentities('<br />');

//$mxdate
$now = date("D, d M Y H:i:s T");
echo '
<rss version="2.0">
	<channel>
        <title>Paradise tattoo fest</title>
        <link>http://competitionjudge.com/paradise/rss.php</link>
        <description>Announcements and winners</description>
        <language>en-us</language>
        <pubDate>'.$now.'</pubDate>
        <lastBuildDate>'.$now.'</lastBuildDate>
        <docs>http://hellcity.com</docs>
        <managingEditor>matthew@crasxit.net (Matthew Ramir)</managingEditor>
        <webMaster>matthew@crasxit.net (Matthew Ramir)</webMaster>';
		$printArr=array();
if($finished)
while($f=mysql_fetch_array($finished)){
	$img='';
	$v=$f['id'];
	while(in_array($f['finished'],array_keys($printArr))){
			$f['finished']++;							  
	  }// contest //place //date //cont number
			$que=mysql_query("select p.id, p.image, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by 4 desc limit 3");
			$wn=array();
			if($que)while($r=mysql_fetch_array($que)){
				$w['number']=$r['number'];
				$w['image']=$r['image'];
				$w['custom']=array();
				$quec=mysql_query("select id, name from ".T_CUST." where rss=1");
				if($quec)while($rc=mysql_fetch_array($quec)){
					$quecv=mysql_query("select val from ".T_CUST_VALS." where contestant='".$r['id']."' and field='".$rc['id']."'");
					if($quecv)while($rcv=mysql_fetch_array($quecv)){
						$w['custom'][$rc['name']]=$rcv['val'];
					}
				}
				$wn[]=$w;
			}
		$printArr[$f['finished']]=array('name'=>$f['name'],'description'=>$f['description'],'date'=>$f['day'],'winner'=>$wn);
}

$custRss=mysql_query("select * from ".T_RSS);
while($f=mysql_fetch_array($custRss)){
		while(in_array($f['date'],array_keys($printArr))){
			$f['date']++;							  
	  }			
	  $printArr[$f['date']]=array('name'=>$f['title'],'description'=>str_replace("\n",$br,htmlentities($f['text'])));
}

$newRegs=mysql_query("select * from ".T_CONTS." where registered!=0 order by registered");
while($f=mysql_fetch_array($newRegs)){
		while(in_array($f['registered'],array_keys($printArr))){
			$f['registered']++;							  
	  }			
	  
	$regComps=mysql_query("select c.name from ".T_REGS." r, ".T_COMPS." c where c.id=r.competition and r.contestant=".$f['id']);
	$description="Registered in:<br />";
	while($fr=mysql_fetch_array($regComps)){
		$description.=$fr['name']."<br />";
	}
	$quec=mysql_query("select id, name from ".T_CUST." where rss=1");
	if($quec)while($rc=mysql_fetch_array($quec)){
		$quecv=mysql_query("select val from ".T_CUST_VALS." where contestant='".$f['id']."' and field='".$rc['id']."'");
		if($quecv)while($rcv=mysql_fetch_array($quecv)){
		$description.=$rc['name'].": ".$rcv['val'];
		}
	}
	  $description.="<br /><img src=\"".ROOT."/".$f['image']."\" /><br /><hr>";
	  $printArr[$f['registered']]=array('name'=>"New Registration",'description'=>str_replace("\n",$br,htmlentities($description)));
}
ksort($printArr, SORT_NUMERIC);
$printArr=array_reverse($printArr, true);
foreach($printArr as $k=>$p){
echo '
		<item>
			<title>'.dsql($p['name']).'</title>
			<link>http://hellcity.com</link>
			<description>'.dsql($p['description']).$br;
			//winner
			$places=array("1st Place","2nd Place", "3rd Place");
			if(in_array('winner',array_keys($p))){
				$war=$p['winner'];
				foreach($war as $wr=>$dt){
					echo $br.htmlentities("<h2>".$places[$wr]."</h2>").$br;
					foreach($dt['custom'] as $kc=>$v){
						echo $kc.": ".$v.$br;	
					}
					echo htmlentities("<br /><img src=\"".ROOT."/".$dt['image']."\" /><br /><hr>");
			
				}
			}
			echo '
			</description>
			<pubDate>'.date("D, d M Y H:i:s T", $k).'</pubDate>
		</item>';
			
}

?>
	</channel>
</rss>