<?php
	class judge extends main{
		function auth(){
			global $user;
			if(!$user->isValid()||!$user->isJudge())return false;
			return true;
		}
		function execute(){
			global $db, $user;
				$c=$_GET['c'];
				$ci=$this->getCompetition($c);		
				if($this->getRecord($ci, $cinfo)){					
					$body="<script type='text/javascript'>$(function(){disableAll();});</script><h2><a href='".BASE."/judge/".$cinfo['id']."' style='color:#ffffff'>".$cinfo['name']."</a></h2>";		
					$body.="<table><tr><td valign=top ><div style='float:left;padding-right:30px;'>".$this->loadContestants($c)."</div></td><td valign=top >";	
					$body.="<div style='float:left;text-align:left;padding-right:30px;'><h2>Scores</h2><input type=button onclick='noShow();' value='No show' disabled=disabled id='noShow' /> ".$this->createScoreForm($c)."</div></td><td valign=top >";		
					$body.="<div style='float:left;text-align:left;padding-right:30px;' id=infoDiv ></div></td></tr></table>";
				}else if(strcmp($c, "menu")==0){
				global $comps;
					foreach($comps as $cmp){
						$body.="<a href='".BASE."/judge/".$cmp['id']."'>".htmlentities($cmp['name'])."</a><br />";
					}
				}else
					$body="<h2>Error finding competition</h2>";
		

			return $body;
		}
		
		function createScoreForm($c){
					
					$body.="<form url='".BASE."/ajax/judge/".$c."' id=jform >";
						global $db;
						$listc=$db->fetch_all_array("select a.id, a.name, a.min, a.max from ".T_CCLIST." l, ".T_CATS." a where l.competition=".$c." and l.category=a.id" );
						foreach($listc as $s){
							$body.=$this->getslider($s['id'], $s['min'], $s['max'],$s['name']);
						}
				
				return $body;
		}
		
		
		function loadContestants($c){
			global $db,$user;
			$ppl=$db->fetch_all_array("select c.name, r.number, c.id from ".T_CONTS. " c, ".T_REGS." r where r.competition=$c and r.contestant=c.id and r.enabled=1 order by r.number");
			$maxW=4;
			foreach($ppl as $p){
				
			$sl=strlen($p['name']);
			$maxW=$sl>$maxW?$sl:$maxW;
				$sco=false;
				$lscr=$db->fetch_all_array(" SELECT count( s.category ) AS sco, (SELECT count( c.category ) AS cat FROM ".T_CCLIST." c WHERE c.competition =$c) AS cat FROM ".T_SCORES." s WHERE s.contestant =".$p['id']." AND s.judge =".$user->getId()." AND s.competition=$c");
				$sco=$lscr[0]['sco']==$lscr[0]['cat'];			
				$body.= "<tr id=contestantList".$p['id']." noshow=0 info='".htmlentities($this->getPersonInfo($p['id'], $c), ENT_QUOTES)."' scores='".$this->getScoresJson($p['id'], $c)."' ><td style='background-color:#".($sco?"00ff00":"ff0000")."; color:#".($sco?"000000":"ffffff")."' onclick='displayUser(".$p['id'].");return false;' >".$p['number'].". ".$p['name']."</td></tr>";
			}
			
			$maxW=6*$maxW;
			$body.= "</table>";
			$body="<table border=1 class='sorted' id=contestantList ><thead><tr><th width='{$maxW}px'>Contestants</th></tr></thead>".$body;
			return $body;
		}
		
	function getPersonInfo($per, $v){
		global $db;
		if(!is_numeric($per))return false;
		$que=$db->fetch_all_array("select c.id, c.name, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$per);
		if(sizeof($que)!=1)return false;
		$r=$que[0];
		$body.="<h3>".$r['number'].". ".$r['name']."</h3><br />";
		$body.="<img src='".BASE."/image.php?i=".$r['id']."' /><br /><table border=1>";
		$quei=$db->fetch_all_array("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v, ".T_COMP_CUST." cc where v.contestant='".$per."' and c.id=v.field and c.judge=1 and cc.custom=c.id and cc.competition=$v ");
		foreach($quei as $rx){
			$body.= "<tr><td>".$rx['name']."</td><td>".$rx['val']."</td></tr>";
		}
		$body.="</table>";
		return $body;
	}
	
		function getCompetition($c){
			global $db;
			if(!is_numeric($c))return false;
			return $db->fetch_all_array("select c.* from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.id=".$c." and (c.judging=1 or (g.judge=1 and c.judging=-1)) order by c.name ");
		}
		function getScoresJson($p,$c){
			global $db, $user;
			if(!is_numeric($p)||!is_numeric($c))return false;
			$scores=$db->fetch_all_array("select category, score from ".T_SCORES." where contestant=$p and competition=$c and judge=".$user->getId());
			$ar=array();
			foreach($scores as $r){
				$ar[$r['category']]=$r['score'];
			}
			return json_encode($ar);
		}
		
	function getslider($name, $min, $max, $title){
return '<h3>'.$title.'</h3>
'.$min.' 
<div style="float:right;">'.$max.'</div>
<div id=slider'.$name.' style="margin-bottom:10px;margin-top:3px;" ></div>
<input type=text id=score'.$name.' value="'.$min.'" min="'.$min.'" onfocus=\'startScore('.$name.');\' onblur=\'if($(this).attr("readonly"))return;num=$(this).val();if(/^\\d*$/.test(num)&&num<$("#slider'.$name.'").slider( "option", "max")&&num>$("#slider'.$name.'").slider( "option", "min")) $("#slider'.$name.'").slider( "option", "value", $(this).val() );else $(this).val($("#slider'.$name.'").slider( "option", "value")); updateScore('.$name.');\'  /><br /><br />
<script type="text/javascript">$("#slider'.$name.'").slider({max:'.$max.', min:'.$min.', value:'.$min.', slide:function(event,ui){$("#score'.$name.'").val(ui.value);}, animate:true, start: function(event, ui) { startScore('.$name.'); }, stop: function(event, ui) { updateScore('.$name.'); }
 });</script>';
}

function isValidScore($comp, $cat, $score){
	global $db;
	if(!is_numeric($cat)||!is_numeric($score))return false;
	$r=$db->fetch_all_array("select * from ".T_CATS." c, ".T_CCLIST." cl where c.id=$cat and c.id=cl.category and cl.competition=$comp and c.min<=$score and c.max>=$score");
	return $r&&sizeof($r)==1;
}

function isValidReg($v, $per){	
	global $db;
		if(!is_numeric($per))return false;
		$que=$db->fetch_all_array("select c.id, c.name, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$per);
		if(sizeof($que)!=1)return false;
		return true;
}

function updateScore(){
	$comp=$_GET['action'];	

	$ci=$this->getCompetition($comp);		
	if($this->getRecord($ci, $cinfo)){		
		global $ret, $db, $user;
		$ret=array();	
		$cont=$_GET['cont'];
		if(isset($_GET['ns'])){
			if(is_numeric($cont))
			$db->query("delete from ".T_SCORES." where category in (select category from ".T_CCLIST." where competition=$comp)  and judge=".$user->getId()." and contestant=".$cont);
			return true;
		}
		$cat=$_GET['c'];
		$score=$_GET['s'];
		if($this->isValidScore($comp, $cat, $score)&&$this->isValidReg($comp, $cont)){
			$db->query("insert into ".T_SCORES."(competition, category, judge, contestant, score) values($comp, $cat, ".$user->getId().", $cont, $score) on duplicate key update score=$score");
			$ret['scores']=$this->getScoresJson($cont, $comp);	
			$lscr=$db->fetch_all_array(" SELECT count( s.category ) AS sco, (SELECT count( c.category ) AS cat FROM ".T_CCLIST." c WHERE c.competition =$comp) AS cat FROM ".T_SCORES." s WHERE s.contestant =".$cont." AND s.judge =".$user->getId()." AND s.competition=$comp");
				$sco=$lscr[0]['sco']==$lscr[0]['cat'];	
				$ret['color']=$sco?"#00ff00":"#ff0000";
			return true;
		}else return false;
	}else return false;
}

}