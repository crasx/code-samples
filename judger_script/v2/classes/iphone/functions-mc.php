<?php
require_once('adm/cfg.php');
if(!$userInfo['mc'])die("You shouldnt be here");
if(isset($_GET['do'])){
 if($_GET['do']=="list"){
		if(isset($_GET['view'])){
		$v=$_GET['view'];
			if(is_numeric($v)){
				if($nam=mysql_fetch_array(mysql_query("select name from ".T_COMPS." where id=$v and (day<=curdate() or enabled=0)"))){
					$body.="<h2><a href='javascript:getHttp(\"ajax-php.php?page=mc&do=list\", \"page\")'>Back to competitions</a></h2>";			
					$body.="<div class=mtop onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=list&view=$v\", \"page\")'>".dsql($nam['name'])."</div>";			
					if(isset($_GET['line'])){
						mysql_query("update ".T_CHECKS." set line=0 where competition=$v");
						foreach($_GET['line'] as $k=>$va){			
						if($va=="0")continue;
							if(is_numeric($va)){
								if(!mysql_query("insert into ".T_CHECKS."(competition, contestant, line) values($v,$k, 1) on duplicate key update line=1")){
									$message="Error updating one or more checks: ".mysql_error();
								}
								}else $message="Error updating one or more checks: not number ";
						}
						$message=$message==""?"Checks updated":$message;
					}
					if(isset($_GET['called'])){
						mysql_query("update ".T_CHECKS." set called=0 where competition=$v");
						foreach($_GET['called'] as $k=>$va){	
						if($va=="0")continue;
							if(is_numeric($va)){
								
								if(!mysql_query("insert into ".T_CHECKS."(competition, contestant, called) values($v,$k, 1) on duplicate key update called=1")){
									$message="Error updating one or more checks: ".mysql_error();
								}
								}else $message="Error updating one or more checks: not number ";
						}
						$message=$message==""?"Checks updated--".date("g:i:s"):$message;
					}
					$line=array();
					$called=array();
					$que=mysql_query("select contestant from ".T_CHECKS." where competition=$v and line=1");
					if($que)while($r=mysql_fetch_array($que)){
						$line[]=$r['contestant'];
					}
					$que=mysql_query("select contestant from ".T_CHECKS." where competition=$v and called=1");
					if($que)while($r=mysql_fetch_array($que)){
						$called[]=$r['contestant'];
					}
					if(!isset($_GET['per'])){
					$que=mysql_query("select t.id, r.number, t.name from ".T_CONTS." t, ".T_REGS." r where r.contestant=t.id and r.competition=$v order by r.number");
					$body.="<form id=checked action='javascript:getUri(\"ajax-php.php?page=mc&do=list&view=$v&\", \"checked\", \"page\")' > $message <table border=1 class=fulipb ><tr><th>In line</th><th>Called</th><th>Position</th><th>Name</th></tr>";
					if($que)while($r=mysql_fetch_array($que)){						
							$body.="<tr><td><input type=hidden name=line[".$r['id']."] id=cb".$r['id']." value='".(in_array($r['id'],$line)?"1":"0")."' /><img src='img/cb".(in_array($r['id'],$line)?"f":"e").".png'  id=cbi".$r['id']." onclick='javascript:switchImg(\"cbi".$r['id']."\",\"cb".$r['id']."\")' /></td><td><input type=hidden name=called[".$r['id']."] id=cbc".$r['id']." value='".(in_array($r['id'],$called)?"1":"0")."' /><img src='img/cb".(in_array($r['id'],$called)?"f":"e").".png' onclick='javascript:switchImg(\"cbci".$r['id']."\",\"cbc".$r['id']."\")' id=cbci".$r['id']." /></td><td>".$r['number']."</td><td><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=list&view=$v&per=".$r['id']."\", \"page\")'>".dsql($r['name'])."</div></td></tr>";		
					}
					$body.="<tr><td colspan=4 align=center ><input type=submit value='Update checks' class=isubmit ><br /><input type=button onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=list&view=$v\", \"page\")' value='Refresh checks' class=isubmit ></td></tr></table>";
					}else{
						if(is_numeric($_GET['per'])){
						$que=mysql_query("select  c.name, c.description, c.image, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$_GET['per']);
						if($que)if($r=mysql_fetch_array($que)){
							$body.="<h3>".$r['number'].". ".$r['name']."</h3><br />";
							$body.="<img src='".$r['image']."' class=hund /><br /><table border=1><tr><td>Description</td><td>";
							$body.=str_replace("\n", "<br />",htmlspecialchars($r['description']))."</td></tr>";
							$quei=mysql_query("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v where c.id=v.field  and v.contestant='".$_GET['per']."'");
							if($quei)while($rx=mysql_fetch_array($quei)){
								$body.= "<tr><td>".dsql($rx['name'])."</td><td>".dsql($rx['val'])."</td></tr>";
							}
							$body.="</table>";
						
						}
						}else $body.="Person doesn't exist";
					}
			}
		}
		}
		if(!isset($v)||!is_numeric($v)){
		$body="<ul><li class=mtop >View competition contestants (registered)</li>";
		$que=mysql_query("select * from ".T_COMPS." where day<=curdate() order by day desc");
		$dy="";
		$fr=true;
			if($que)while($r=mysql_fetch_array($que)){
				$count=0;
				$ques=mysql_query("select count(*) 'c' from ".T_REGS." where competition='".$r['id']."'");
				if($ques)if($sr=mysql_fetch_array($ques)){
					$count=$sr['c'];
				}
				if($dy!=$r['day']){
					$dy=$r['day'];
					if(!$fr)$body.= "</table>";
					else $fr=false;
					$body.="<li class=mtop >$dy</li>";					
				}
			$body.="<li><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=list&view=".$r['id']."\", \"page\")'>".dsql($r['name'])." ($count)</div></li>";
			}
		
		}
		
		
	}else if($_GET['do']=="res"){		
	$dov=false;
		if(isset($_GET['view'])){
		$v=$_GET['view'];
			if(is_numeric($v)){
				if($nam=mysql_fetch_array(mysql_query("select name from ".T_COMPS." where id=$v and (day<=curdate() or enabled=0)"))){
					$body.="<h2><a href='javascript:getHttp(\"ajax-php.php?page=mc&do=res\", \"page\")'>Back to competitions</a></h2>";		
					$body.="<div class=mtop onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=res&view=$v\", \"page\")'>".dsql($nam['name'])."</div>";		
					
				$list=false;
					if(isset($_GET['info'])){
					$list=true;
					if(is_numeric($_GET['info'])){
						$inf=$_GET['info'];
						$colspan=0;
						$jsco=mysql_query("select j.name from ".T_SCORES." s, ".T_USERS." j,".T_CATS." c where s.judge=j.id and s.competition=$v and s.category=c.id and s.contestant=$inf group by s.judge order by s.judge");
						$stable="<table border=1><tr><td>&nbsp;</td>";
						if($jsco)while($jr=mysql_fetch_array($jsco)){
							$stable.="<th>".dsql($jr['name'])."</th>";
							$colspan++;
						}
						$stable.="</tr>";
						
						$crow=mysql_query("SELECT concat( '<tr><th>', c.name, '</th><td>', group_concat( s.score ORDER BY s.judge SEPARATOR '</td><td>' ) , '</td></tr>' ) FROM ".T_SCORES." s, ".T_CATS." c WHERE c.id = s.category and s.competition=$v and s.contestant=$inf GROUP BY s.category order by s.category");
						if($crow)while($tr=mysql_fetch_array($crow)){
						$stable.=$tr[0];	
						}
						$crow=mysql_query("SELECT concat(round(sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))*100,2),'%') 'score' FROM ".T_SCORES." s where s.competition=$v and s.contestant=$inf GROUP BY s.judge order by s.category");
						echo mysql_error();
						$stable.="<tr><th>Average:</th>";
						if($crow)while($tr=mysql_fetch_array($crow)){
						$stable.="<td>".$tr[0]."</td>";	
					
						}
						$stable.="</tr>";
						$place=0;
						$totscore=0;
						$posscore=0;
						$tscore=0;
						$plac=mysql_query("select p.id, p.name, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score', sum(s.score) 'total', (select sum(c.max)*(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v )) 'possible' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by round(sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))*100,2) desc");
						if($plac)while($pla=mysql_fetch_array($plac)){
							$place++;
							if($pla['id']==$inf){
								$tscore=$pla['score'];
								$totscore=$pla['total'];
								$posscore=$pla['possible'];
								break;
							}
						}
						$que=mysql_query("select  c.name, c.description, c.type, c.image, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$_GET['info']);
						if($que)if($r=mysql_fetch_array($que)){
							$stable.="<tr><th>Total points</th><td colspan=$colspan >$totscore</td></tr>";
							$stable.="<tr><th>Possible points</th><td colspan=$colspan >$posscore</td></tr>";
							$stable.="<tr><th>Average score</th><td colspan=$colspan >$tscore</td></tr></table>";
							$body.="<h3>".$place.". ".dsql($r['name'])." (".$r['number'].")</h3><br />";
							$body.=$stable;
							$body.="<img src='".$r['image']."' class=hund />";
							$body.="<br /><table border=1><tr><td>Description</td><td>";
							$body.=str_replace("\n", "<br />",htmlspecialchars($r['description']))."</td></tr>";		
							$quei=mysql_query("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v where c.id=v.field  and v.contestant='".$_GET['info']."'");
							if($quei)while($rx=mysql_fetch_array($quei)){
								$body.= "<tr><td>".dsql($rx['name'])."</td><td>".$rx['val']."</td></tr>";
							}
							$body.="</table>";
							$dov=1;
						}
						}else $body.="Person doesn't exist";
					}
					if(!$list){
					$isen=0;
					$que=mysql_query("select finished from ".T_COMPS." where id=$v");
					if($que)if($r=mysql_fetch_array($que)){
						$isen=$r['finished'];
					}
					if(isset($_GET['revfin'])){
						$isen=$isen==0?time():0; 
						mysql_query("update ".T_COMPS." set finished=$isen where id=$v");
					}
					$body.=	"<h2><a href='javascript:getHttp(\"ajax-php.php?page=mc&do=res&view=$v&revfin=1\", \"page\")'>".($isen==0?"Enable rss publication":"Disable rss publication")."</a></h2>";		
					$dov=true;
					$que=mysql_query("select p.id, p.name, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2) desc");
				
					$p=1;
					$body.="<table border=1 ><tr><td>Place</td><td>Name</td><td>Position</td><td>Score</td></tr>";
						if($que)while($r=mysql_fetch_array($que)){
						$body.="<td>$p</td><td><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=res&view=$v&info=".$r['id']."\", \"page\")'>".dsql($r['name'])."</div><br />
</td><td>".$r['number']."</td><td>".$r['score']."</td></tr>";
						$p++;
						}
						$body.="</table>";
					}
				
				}
			}
		}
		if(!$dov){
		$body="<ul><li class=mtop >View competition results</li>";
		$que=mysql_query("select * from ".T_COMPS." where day<=curdate() order by day desc");
		$dy="";
		$fr=true;
			if($que)while($r=mysql_fetch_array($que)){
				if($dy!=$r['day']){
					$dy=$r['day'];
					if(!$fr)$body.= "</table>";
					else $fr=false;
					$body.="<li class=mtop >$dy</li>";					
				}
			$body.="<li><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=res&view=".$r['id']."\", \"page\")'>".dsql($r['name'])."</div></li>";
			}
		}
	
	}else if($_GET['do']=="resv"){//////////////////////////////////////////////////////////////////////
			$dov=false;
		
		if(isset($_GET['view'])){
		$v=$_GET['view'];
			if(is_numeric($v)){
				if($nam=mysql_fetch_array(mysql_query("select name from ".T_COMPS." where id=$v and (day<=curdate() or enabled=0)"))){
					$body.="<h2><a href='javascript:getHttp(\"ajax-php.php?page=mc&do=resv\", \"page\")'>Back to competitions</a></h2>";		
					$body.="<div class=mtop onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=resv&view=$v\", \"page\")'>".dsql($nam['name'])."</div>";		
					
				$isen=0;
					$que=mysql_query("select finished from ".T_COMPS." where id=$v");
					if($que)if($r=mysql_fetch_array($que)){
						$isen=$r['finished'];
					}
					if(isset($_GET['revfin'])){
						$isen=$isen==0?time():0; 
						mysql_query("update ".T_COMPS." set finished=$isen where id=$v");
					}
					$body.=	"<h2><a href='javascript:getHttp(\"ajax-php.php?page=mc&do=resc&view=$v&revfin=1\", \"page\")'>".($isen==0?"Enable rss publication":"Disable rss publication")."</a></h2>";		
					$dov=true;
					$quelp=mysql_query("select p.id, p.name, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by 4 desc");
					$p=1;
					$body.="<table border=1 >";
					if($quelp)while($rlp=mysql_fetch_array($quelp)){
						$body.="<tr><td><h2>$p. ".$rlp['name']."</h2>";
						$body.="Contestant number: ".$rlp['number']."<br />";
						$p++;				
						$inf=$rlp['id'];
						$colspan=0;
						$jsco=mysql_query("select j.name from ".T_SCORES." s, ".T_USERS." j,".T_CATS." c where s.judge=j.id and s.competition=$v and s.category=c.id and s.contestant=$inf group by s.judge order by s.judge");
						$stable="<table border=1><tr><td>&nbsp;</td>";
						if($jsco)while($jr=mysql_fetch_array($jsco)){
							$stable.="<th>".dsql($jr['name'])."</th>";
							$colspan++;
						}
						$stable.="</tr>";
						
						$crow=mysql_query("SELECT concat( '<tr><th>', c.name, '</th><td>', group_concat( s.score ORDER BY s.judge SEPARATOR '</td><td>' ) , '</td></tr>' ) FROM ".T_SCORES." s, ".T_CATS." c WHERE c.id = s.category and s.competition=$v and s.contestant=$inf GROUP BY s.category order by s.category");
						if($crow)while($tr=mysql_fetch_array($crow)){
						$stable.=$tr[0];	
						}
						$crow=mysql_query("SELECT concat(round(sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))*100,2),'%') 'score' FROM ".T_SCORES." s where s.competition=$v and s.contestant=$inf GROUP BY s.judge order by s.category");

						echo mysql_error();
						$stable.="<tr><th>Average:</th>";
						if($crow)while($tr=mysql_fetch_array($crow)){
						$stable.="<td>".$tr[0]."</td>";	
					
						}
						$stable.="</tr>";
						$place=0;
						$totscore=0;
						$posscore=0;
						$tscore=0;
						$plac=mysql_query("select p.id, p.name, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score', sum(s.score) 'total', (select sum(c.max)*(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v )) 'possible' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by round(sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))*100,2) desc");
						if($plac)while($pla=mysql_fetch_array($plac)){
							$place++;
							if($pla['id']==$inf){
								$tscore=$pla['score'];
								$totscore=$pla['total'];
								$posscore=$pla['possible'];
								break;
							}
						}
						$que=mysql_query("select  c.name, c.description, c.image, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$inf);
						if($que)if($r=mysql_fetch_array($que)){
							$stable.="<tr><th>Total points</th><td colspan=$colspan >$totscore</td></tr>";
							$stable.="<tr><th>Possible points</th><td colspan=$colspan >$posscore</td></tr>";
							$stable.="<tr><th>Average score</th><td colspan=$colspan >$tscore</td></tr></table>";
							$body.=$stable;
							$body.="<img src='".$r['image']."' class=hund />";
							$body.="<br /><table border=1><tr><td>Description</td><td>";
							$body.=str_replace("\n", "<br />",htmlspecialchars($r['description']))."</td></tr>";		
							$quei=mysql_query("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v where c.id=v.field  and v.contestant='".$inf."'");
							if($quei)while($rx=mysql_fetch_array($quei)){
								$body.= "<tr><td>".dsql($rx['name'])."</td><td>".$rx['val']."</td></tr>";
							}
							$body.="</table>";
							$dov=1;
						}
						$body.="</td></tr>";
						}else $body.="Person doesn't exist";
					}
						}
						$body.="</table>";
					}
				
		
		if(!$dov){
		$body="<ul><li class=mtop >View competition results</li>";
		$que=mysql_query("select * from ".T_COMPS." where day<=curdate() order by day desc");
		$dy="";
		$fr=true;
			if($que)while($r=mysql_fetch_array($que)){
				if($dy!=$r['day']){
					$dy=$r['day'];
					if(!$fr)$body.= "</table>";
					else $fr=false;
					$body.="<li class=mtop >$dy</li>";					
				}
			$body.="<li><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=resv&view=".$r['id']."\", \"page\")'>".dsql($r['name'])."</div></li>";
			}
		}
	
		
	}else if($_GET['do']=="report"){//////////////////////////////////////////////////////////////////////

		$body="<h2>View competition Report</h2>";
		$que=mysql_query("select * from ".T_COMPS." where day<=curdate() order by day desc");
		$dy="";
			if($que)while($r=mysql_fetch_array($que)){
				$body.="<table border=1 ><tr><th colspan=6 >".$r['name']."</th</tr>";
				$poid=$r['id'];
				$p=1;
				$sque=mysql_query("select p.id, p.name, r.number,  concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$poid ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$poid and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$poid' and r.competition=$poid and r.contestant=s.contestant group by s.contestant order by round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$poid ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$poid and contestant=p.id))*100,2) desc limit 3");
						$body.="<tr><td>Place</td><td>Name</td><td>Position</td>";
						$quei=mysql_query("select c.name from ".T_CUST." c where report=1 order by c.id");
							if($quei)while($rx=mysql_fetch_array($quei)){
								$body.= "<td>".$rx['name']."</td>";
							}
						$body.="<td>Score</td></tr>";
						if($sque)while($rt=mysql_fetch_array($sque)){
						$body.="<tr><td>$p</td><td><div class=aholder onclick='javascript:getHttp(\"ajax-php.php?page=mc&do=res&view=$poid&info=".$rt['id']."\", \"page\")'>".$rt['name']."</div>
</td><td>".$rt['number']."</td>";
								$quei=mysql_query("select v.val from ".T_CUST_VALS." v, ".T_CUST." c where v.contestant='".$rt['id']."' and c.id=v.field and c.report=1 order by v.field");
							if($quei)while($rx=mysql_fetch_array($quei)){
								$body.= "<td>".$rx['val']."</td>";
							}
						$body.="<td>".$rt['score']."</td></tr>";
						$p++;
						}
						$body.="</table><br />";

				
			}
			
				
	}
}
/////////functionz//////////////
?> 