<?php
class mc extends main{
	
	function auth(){
		global $user;
		return $user->isMc();
	}
	
	function execute(){
		global $db, $outjs;
		$outjs="var preloaded = new Array();
function preload_images() {
    for (var i = 0; i < arguments.length; i++){
        preloaded[i] = document.createElement('img');
        preloaded[i].setAttribute('src',arguments[i]);
    };
};
preload_images(
";
		switch($_GET['sub']){
	 		case "competitions":
			$v=$_GET['view'];
			$cinfo=$this->getCompetitionInfo($v);
			if($cinfo&&sizeof($cinfo)>0){			
				$line=array();
				$called=array();
				$que=$db->fetch_all_array("select contestant from ".T_CHECKS." where competition=$v and line=1");
				foreach($que as $r){
					$line[]=$r['contestant'];
				}
				$que=$db->fetch_all_array("select contestant from ".T_CHECKS." where competition=$v and called=1");
				foreach($que as $r){
					$called[]=$r['contestant'];
				}
				$cinfo=$cinfo[0];
			//////////////////////list
			$body.="<h2><a href='".BASE."/mc/competitions?view=$v'>".$cinfo['name']."</a></h2>";	
						
					$body.=" <table border=1 class=report ><thead><tr><th>In line</th><th>Called</th><th>Position</th><th>Name</th></tr></thead><tbody>";
					$que=$db->fetch_all_array("select t.id, r.number, t.name from ".T_CONTS." t, ".T_REGS." r where r.contestant=t.id and r.competition=$v order by r.number");
					foreach($que as $r){			
							$inf=addslashes(htmlentities($this->getPersonInfo($r['id'], $v)));
							$body.="<tr id=contestantList".$r['id']." ><td onclick='javascript:updateLineCheck(".$r['id'].", $v)' bgcolor=#".(in_array($r['id'],$line)?"00ff00":"ff0000")." >&nbsp;</td><td onclick='javascript:updateCalledCheck(".$r['id'].", $v)' bgcolor=#".(in_array($r['id'],$called)?"00ff00":"ff0000")." >&nbsp;</td><td>".$r['number']."</td><td><a href=' ' onclick=\"$.colorbox({html:'{$inf}'});return false;\">".$r['name']."</a></td></tr>";		
					}
					$body.="</tbody></table></div>";
			
			$outjs=substr($outjs, 0, -3).");";
			define("JSHEADER", $outjs);	
			}else{
			$body="<h2>View competition contestants</h2>";
			$body.=$this->listCompetitions(BASE."/mc/competitions?view={I}");
			}
		break;
		
		case "results":	
			$dov=true;
			$v=$_GET['view'];
			$cinfo=$this->getCompetitionInfo($v);
			if($cinfo&&sizeof($cinfo)>0){
				
				$cinfo=$cinfo[0];
						$que=$db->fetch_all_array("select p.id, p.name, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by 4 desc");
				
					$p=1;
					$body.="<h2>".$cinfo['name']."</h2><table border=1 class=report ><tr><td>Place</td><td>Name</td><td>Position</td><td>Score</td></tr>";
					foreach($que as $r){
							$inf=addslashes(htmlentities($this->getPersonInfo($r['id'], $v)));
						$body.="<td>$p</td><td><a href='' onclick=\"$.colorbox({html:'{$inf}'});return false;\">".$r['name']."</a><br />
			</td><td>".$r['number']."</td><td>".$r['score']."</td></tr>";
						$p++;
					}
					$body.="</tbody></table></div>";
				$dov=false;			
			}
			if($dov){
			$body="<h2>View competition results</h2>";
			$body.=$this->listCompetitions(BASE."/mc/results?view={I}");
			}
			$outjs=substr($outjs, 0, -3).");";
			define("JSHEADER", $outjs);
		
	break;
	case "report":
	
			$body="<h2>View competition Report</h2>";
			$que=$db->fetch_all_array("select c.*, ifnull(g.name, '') gid from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.mc=1 or (g.mc=1 and c.mc=-1) order by c.group, c.order ");
			$dy="";
				foreach($que as $r){
					$col=4;
					$poid=$r['id'];
					$p=1;
//					$spec=$db->fetch_all_array("select c.name from ".T_CUST." c, ".T_CUST_COMP." cc where cc.custom=c.id and cc.competition=$poid and (cc.mc=1 or (cc.mc is null and c.mc=1)) order by c.name");
					$spec=$db->fetch_all_array("select c.name from ".T_CUST." c where c.mc=1 order by c.name");
					$sque=$db->fetch_all_array("select p.id, p.name, r.number,  concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$poid ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$poid and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$poid' and r.competition=$poid and r.contestant=s.contestant group by s.contestant order by round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$poid ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$poid and contestant=p.id))*100,2) desc limit 3");
					$bodyt.="<tr><td>Place</td><td>Name</td><td>Position</td><td>Score</td>";
					foreach($spec as $sp){
						$bodyt.='<td>'.$sp['name'].'</td>';
						$col++;
					}
					$bodyt.="</tr>";
					foreach($sque as $rt){
						$inf=addslashes(htmlentities($this->getPersonInfo($rt['id'], $r['id'])));
						$bodyt.="<tr><td>$p</td><td><a href='' onclick=\"$.colorbox({html:'{$inf}'});return false;\" >".$rt['name']."</a><br />
	</td><td>".$rt['number']."</td><td>".$rt['score']."</td>";
						//$spec=$db->fetch_all_array("select v.val from ".T_CUST." c, ".T_CUST_VALS." v, ".T_CUST_COMP." cc where v.contestant='".$rt['id']."' and c.id=v.field and cc.custom=c.id and cc.competition=".$r['id']." and (cc.mc=1 or (cc.mc is null and c.mc=1)) order by c.name ");
						$spec=$db->fetch_all_array("select v.val from ".T_CUST." c, ".T_CUST_VALS." v where v.contestant='".$rt['id']."' and c.id=v.field and c.mc=1 order by c.name ");
						foreach($spec as $sp){
							$bodyt.='<td>'.$sp['val'].'</td>';
						}	
						$bodyt.="</tr>";
						$p++;
					}
					$body.="<table class=report ><tr><th colspan=$col >".$r['name']."</th</tr>".$bodyt."</tbody></table><br />";
					$bodyt="";
				}						
			$outjs=substr($outjs, 0, -3).");";
			//define("JSHEADER", $outjs);
			break;
			case "menu":
				$body.="<a href='".BASE."/mc/results'>Results</a><br />";
				$body.="<a href='".BASE."/mc/report'>Report</a><br />";
				$body.="<a href='".BASE."/mc/competitions'>Contesant list</a><br />";
			break;
		}
		return $body;
		
		
		}
		
	function getCompetitionInfo($id){
		global $db;
		if(!is_numeric($id))return false;
		$row=$db->fetch_all_array("select c.*, ifnull(g.name, '') gid from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.id=$id and (c.mc=1 or (g.mc=1 and c.mc=-1)) ");
		return $row;
	}
	
	function updateLine($id, $comp){
		global $db;
		$ov=$this->updateLookup($id,$comp, "line");
		if($ov==-1)return false;
		if($ov==0)$ov=1;
		else $ov=0;
		$db->query("insert into ".T_CHECKS."(competition, contestant, line) values($comp,$id, $ov) on duplicate key update line=$ov");									
		global $ret;
		if($ov==0)$ret['color']="#ff0000";
		else $ret['color']="#00ff00";
		return true;
	}
	
	function updateCalled($id, $comp){
		global $db;
		$ov=$this->updateLookup($id,$comp, "called");
		if($ov==-1)return false;
		if($ov==0)$ov=1;
		else $ov=0		;
		$db->query("insert into ".T_CHECKS."(competition, contestant, called) values($comp,$id, $ov) on duplicate key update called=$ov");								
		global $ret;
		if($ov==0)$ret['color']="#ff0000";
		else $ret['color']="#00ff00";
		return true;
	}
	
	function updateLookup($id, $comp, $v){
		global $db;
		if(!is_numeric($id)||!is_numeric($comp))return -1;
		$r=$db->fetch_all_array("select * from ".T_REGS." where competition=$comp and contestant=$id");	
		if(!is_array($r)||sizeof($r)!=1)return -1;
		$r=$db->fetch_all_array("select * from ".T_CHECKS." where competition=$comp and contestant=$id");
		if(is_array($r)&&sizeof($r)==1)return $r[0][$v];
		return 0;
	}
	
	function getPersonInfo($per, $v){
			global $db, $outjs;
		if(!is_numeric($per))return false;
		$que=$db->fetch_all_array("select c.id, c.name, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$per);
		if(sizeof($que)!=1)return false;
		$r=$que[0];
		$er=error_reporting();error_reporting(0);
		$size=getimagesize(CJURL.BASE."/image.php?i=".$per);
		error_reporting($er);
		$outjs.="'".BASE."/image.php?i=".$per."',\r\n";
		$body.="<h3>".$r['number'].". ".$r['name']."</h3><br />";		
		$body.=($size?"<img src='".BASE."/image.php?i=".$per."' id=popupimage onLoad='javascript:setTimeout(\"$.colorbox.resize({width:100%, height:100%})\", 100)' onClick='javascript:self.location=\"".BASE."/image.php?i=".$per."\"' /><br />":"")."<center><table border=1>";
		$quei=$db->fetch_all_array("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v, ".T_COMP_CUST." cc where v.contestant='".$per."' and c.id=v.field and c.mc=1 and cc.custom=c.id and cc.competition=$v ");
		foreach($quei as $rx){
			$body.= "<tr><td>".$rx['name']."</td><td>".$rx['val']."</td></tr>";
		}
		$body.="</table></center>";
		return $body;
	}

	function listCompetitions($url){
			global $db;
			$que=$db->fetch_all_array("select c.*, ifnull(g.name, '') gid from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where (c.mc=1 or (g.mc=1 and c.mc=-1)) order by g.id, c.order");
			$dy="";
			$fr=true;
			foreach($que as $r){
					if($dy!=$r['gid']||$fr){
						$dy=$r['gid'];
						if(!$fr)$body.= "</table>\n";
						else $fr=false;
						$body.="<br /><table class='report'><tr><th align=center >$dy</th></tr>\n";					
					}
				$body.="<tr><td align=center ><a href='".str_replace("{I}", $r['id'],$url)."'>".$r['name']."</a></td></tr>\n";
				}
				if(!$fr)$body.="</table>\n";
				return $body;
					
	}
	
	
	function getPersonResults($id, $v){
		if(!is_numeric($id))return false;
		global $db;

		$colspan=0;
		$scores=array();//judge->array(category->score)
		$stdCat=array();
		$max=0;
		
		//create std cat
		$crow=$db->fetch_all_array("SELECT  c.name, c.min, c.max FROM ".T_CCLIST." cc, ".T_CATS." c WHERE  cc.competition=$v and cc.category=c.id ");
		foreach($crow as $tr){
			$stdCat[$tr['name']]=$tr['min'];	
			$max+=$tr['max'];
		}
		$colspan=sizeof($stdCat)+1;
		
		$crow=$db->fetch_all_array("SELECT j.name, s.score, c.name category FROM ".T_SCORES." s, ".T_USERS." j, ".T_CATS." c WHERE s.judge=j.id and  s.competition=$v and s.category=c.id");
		foreach($crow as $r){
			if(!in_array($r['name'], array_keys($scores)))$scores[$r['name']]=$stdCat;
			$scores[$r['name']][$r['category']]=$r['score'];
		}
		
		$max*=sizeof($scores);
		
		$stable="<table border=1><tr><td>&nbsp;</td>";
		$total=0;
		
		foreach($scores as $jn=>$scrl){
			$stable.="<th>$jn</th>";
		}
		$stable.="<td>Average</td></tr>";
		
		foreach($stdCat as $k=>$val){
			$catAvg=0;
			$stable.="<tr><th>$k</th>";
			foreach($scores as $j=>$sar){
				$stable.="<td>$sar[$k]</td>";
				$catAvg+=$sar[$k];
				$total+=$sar[$k];
			}
			$stable.="<td>".round($catAvg/sizeof($scores),3)."</td></tr>";
		}
		
		
		
		/* i get to do this the fun way
		$jsco=$db->fetch_all_array("select j.name from ".T_SCORES." s, ".T_USERS." j,".T_CATS." c where s.judge=j.id and s.competition=$v and s.category=c.id and s.contestant=$id group by s.judge order by s.judge");
		
		$stable="<table border=1><tr><td>&nbsp;</td>";
		foreach($jsco as $jr){
			$stable.="<th>".$jr['name']."</th>";
			$colspan++;
		}
		
		$stable.="</tr>";
		
		echo htmlentities("SELECT concat( '<tr><th>', c.name, '</th><td>', group_concat( ifnull(s.score, c.min) ORDER BY s.judge SEPARATOR '</td><td>' ) , '</td></tr>' ) as 'r' FROM   ".T_CCLIST." cc, ".T_CATS." c, ".T_SCORES." s WHERE  cc.competition=$v and cc.category=c.id and c.id = s.category and s.competition=$v and s.contestant=$id GROUP BY s.category order by s.category");
		
		$crow=$db->fetch_all_array("SELECT concat( '<tr><th>', c.name, '</th><td>', group_concat( ifnull(s.score, c.min) ORDER BY s.judge SEPARATOR '</td><td>' ) , '</td></tr>' ) as 'r' FROM   ".T_CCLIST." cc, ".T_CATS." c, ".T_SCORES." s WHERE  cc.competition=$v and cc.category=c.id and c.id = s.category and s.competition=$v and s.contestant=$id GROUP BY s.category order by s.category");
		foreach($crow as $tr){
			$stable.=$tr['r'];	
		}
		$crow=$db->fetch_all_array("SELECT sum(s.score) 'score', (select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v )) 'max' FROM ".T_SCORES." s where s.competition=$v and s.contestant=$id GROUP BY s.judge order by s.judge");
		$stable.="<tr><th>Average:</th>";
		foreach($crow as $tr){
			$stable.="<td>".round(($tr['score']/$tr['max'])*100, 2)."%</td>";	
		}
		$stable.="</tr>";
		$place=0;
		$totscore=0;
		$posscore=0;
		$tscore=0;

		}*/
				$plac=$db->fetch_all_array("select p.id, sum(s.score) 'total' from ".T_CONTS." p, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' group by s.contestant order by 2 desc");
		foreach($plac as $pla){
			$place++;
			if($pla['id']==$id){
				break;
			}
		}
			$que=$db->fetch_all_array("select  c.name, c.image, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$id);
		foreach($que as $r){
			$stable.="<tr><th>Total points</th><td colspan=$colspan >$total</td></tr>";
			$stable.="<tr><th>Possible points</th><td colspan=$colspan >$max</td></tr>";
			$stable.="<tr><th>Average score</th><td colspan=$colspan >".round($total/$max*100,3)."</td></tr></table>";
			$body.="<h3>".$place.". ".$r['name']." (".$r['number'].")</h3><br />";
			$body.=$stable;
			$body.="<img src='".$r['image']."' />";
			$body.="<br /><table border=1>";		
			$quei=$db->fetch_all_array("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v where c.id=v.field  and v.contestant='".$id."'");
			foreach($quei as $rx){
				$body.= "<tr><td>".$rx['name']."</td><td>".$rx['val']."</td></tr>";
			}
			$body.="</table>";
			$dov=1;
		}					
		
	return $body;				
	}
}

?>