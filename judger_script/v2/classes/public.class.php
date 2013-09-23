<?php
	class pub extends main{
		
		function execute(){
			global $db, $user, $outjs;
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
			define("NOCENTER", 1);
				$dov=true;
			$v=$_GET['view'];
			$cinfo=$this->getCompetition($v);
			if($cinfo&&sizeof($cinfo)>0){
				
				$cinfo=$cinfo[0];
				$que=$db->fetch_all_array("select p.id, p.name, r.number, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by 4 desc limit 3");
				
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
			
			$outjs=substr($outjs, 0, -3).");";
			define("JSHEADER", $outjs);	
			return $body;
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
		$body.=($size?"<img src='".BASE."/image.php?i=".$per."' id=popupimage onLoad='javascript:setTimeout(\"$.colorbox.resize({width:100%, height:100%})\", 500);' onClick='javascript:self.location=\"".BASE."/image.php?i=".$per."\"' /><br />":"")."<center><table border=1>";
		$quei=$db->fetch_all_array("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v, ".T_COMP_CUST." cc where v.contestant='".$per."' and c.id=v.field and c.mc=1 and cc.custom=c.id and cc.competition=$v ");
		foreach($quei as $rx){
			$body.= "<tr><td>".$rx['name']."</td><td>".$rx['val']."</td></tr>";
		}
		$body.="</table></center>";
		return $body;
	}
	
		function getCompetition($c){
			global $db;
			if(!is_numeric($c))return false;
			return $db->fetch_all_array("select c.* from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.id=".$c." and (c.public=1 or (g.public=1 and c.public=-1)) order by c.name ");
		}
		
		

}