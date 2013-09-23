<?php require CLASSES."admin.users.php";	
class adminVisibility extends adminUsers{
	function initVisibility(){
		return $this->listVisibility();
	}

	function getTdColor($c, $gid, $row, $col){
		$r="<td onclick='javascript:updateCompCol({$row},{$col})' bgcolor='";
		$c=($c==-1&&$gid==0)?0:$c;
		if($c>=1)$r.="#00ff00'>Competition";	
		elseif($c==0)$r.="#ff0000'>Competition";	
		elseif($c==-1)$r.="#FFA500'>Group";	
		else $r.="#ff0000'>Disabled";	
		return $r."</td>";
	}

function listVisibility(){
	global $db, $config;
$list=$db->fetch_all_array("select c.id, ifnull(g.id,0) gid, c.name, c.registration, c.mc, c.judging".(RSS?", c.rss, c.rssR":"").(PUB?", c.public, c.publicVote":"").", ifnull(g.name, '<em>None</em>') `group`  from ".T_COMPS. " c left join ".T_COMP_GROUPS." g on c.group=g.id order by name");
$maxW=0;
				
if(sizeof($list)>0)
foreach($list as $com){
			$sl=strlen($com['name']);
			$maxW=$sl>$maxW?$sl:$maxW;
			$rid=2;
	$ret.= "<tr id='competitionList".$com['id']."' categories='".$this->loadCategories($com['id'])."' ><td><a href='javascript:populateCompetitionForm(".$com['id'].")'>".$com['name']."</a><br /><i>(".$com['group'].")</i></td>".
$this->getTdColor($com['registration'], $com['gid'], $com['id'], $rid++).
$this->getTdColor($com['judging'], $com['gid'], $com['id'], $rid++).
$this->getTdColor($com['mc'], $com['gid'], $com['id'], $rid++).
(RSS?$this->getTdColor($com['rss'], $com['gid'], $com['id'], $rid++).
$this->getTdColor($com['rssR'], $com['gid'], $com['id'], $rid++):"").
(PUB?$this->getTdColor($com['public'], $com['gid'], $com['id'], $rid++).
$this->getTdColor($com['publicVote'], $com['gid'], $com['id'], $rid++):"").
"</tr>";	
	}
$ret.="</tbody></table>";
$maxW=$maxW*6;
$rid=2;
$ret="<h2>Visibility settings</h2>
<table border=1 id='competitionList' class=sorted  >
<thead><tr><th name=col1 style='width:{$maxW}px' >Name</th><th>Registration</th><th>Judging</th><th>MC</th>".(RSS?"<th >RSS<br />Registration</th><th >RSS<br />Results</th>":"").(PUB?"<th>Public</th><th>Public voting</th>":"")."</tr>
<tr><td ></td>
<td><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('registration')\" /></td>
<td><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('judging')\" /></td>
<td><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('MC')\" /></td>
".(RSS?"<td ><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('RSS registration')\" /></td>
<td ><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('RSS results')\" /></td>":"").
(PUB?"<td><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('public')\" /></td>
<td><input type=button value=\"Use group\" onclick=\"javascript:useGroupFor('Public vote')\" /></td>":"")."</tr>
</thead><tbody>".$ret;
	return $ret;
}

	function makeAllUseGroup($type){
		global $db, $ret;
		$col=2;
		$update=$type;
		switch($type){
			case "registration":$col=2;break;
			case "judging":$col=3;break;
			case "MC":$col=4;$update="mc";break;
			case "RSS registration":if(!RSS)return false; $col=5;$update="rss";break;
			case "RSS results":if(!RSS)return false;$col=6;$update="rssR";break;			
			case "public":if(!PUB)return false;$col=(RSS?7:5);break;
			case "Public vote":if(!PUB)return false;$col=(RSS?8:6);$update="publicVote";break;
		default:return false;}
		
		$db->query("update ".T_COMPS." set ".$update."=-1");
		$ret['col']=$col;
				
		
		$allComps=$db->fetch_all_array("select c.id, c.registration, c.judging, c.mc".(RSS?", c.rss, c.rssR":"").(PUB?", c.public, c.publicVote":"").", ifnull(g.register,-1) registrationG, ifnull(g.judge, -1) judgingG,ifnull(g.mc, -1) mcG".(RSS?", ifnull(g.rss,-1) rssG, ifnull(g.rssR,-1) rssRG":"").(PUB?", ifnull(g.public,-1) publicG,  ifnull(g.publicVote,-1) publicVoteG":"")." from ".T_COMPS. " c left join ".T_COMP_GROUPS." g on c.group=g.id ");
		foreach($allComps as $comp){
			$r=array();
			if($comp[$update]==0){
				$r['color']="#ff0000";
				$r['text']="Competition";
			}elseif($comp[$update]>=1){
				$r['color']="#00ff00";			
				$r['text']="Competition";
			}elseif($comp[$update."G"]>=1){
				$r['color']="#FFA500";
				$r['text']="Group";	
			}elseif($comp[$update."G"]==0){
				$r['color']="#FFA500";
				$r['text']="Group";	
			}else{
				$r['text']="Disabled";
				$r['color']="#ffff00";
			}
			$ret['rows'][$comp['id']]=$r;
		}
	return true;
	}
	
	function updateCompILookup($id){
		global $db;
		if(!is_numeric($id))return -2;
		$r=$db->fetch_all_array("select c.registration, c.judging, c.mc".(RSS?", c.rss, c.rssR":"").(PUB?", c.public, c.publicVote":"").", ifnull(g.register,-1) registrationG, ifnull(g.judge, -1) judgingG,ifnull(g.mc, -1) mcG".(RSS?", ifnull(g.rss,-1) rssG, ifnull(g.rssR,-1) rssRG":"").(PUB?", ifnull(g.public,-1) publicG,  ifnull(g.publicVote,-1) publicVoteG":"")." from ".T_COMPS. " c left join ".T_COMP_GROUPS." g on c.group=g.id where c.id=$id");	
		if(is_array($r)&&sizeof($r)==1)return $r[0];
		return -2;
	}
	
		function makeSelectGroup(){
		$r="<select name='cgroup'><option value=0 >None</option>";
		global $db;
		$cg=$db->fetch_all_array("select * from ".T_COMP_GROUPS);
		if(!sizeof($cg))return "<em>None, use configuration<br />to create groups</em><input type=hidden name=cgroup value=0 />";
		foreach($cg as $c){
			$r.="<option value=".$c['id']." >".$c['name']."</option>";
		}
		return $r."</select>";
	}

	
	function updateCompI($id, $col){
		global $db;
	
		switch($col){
			case 2:$type="registration";break;
			case 3:$type="judging";break;
			case 4:$type="mc";break;
		case 5:if(!RSS){if(!PUB)return false; else $type="public";}else $type="rss";break;
		case 6:if(!RSS){if(!PUB)return false; else $type="publicVote";}else $type="rssR";break;
		case 7:if(!RSS||!PUB)return false; $type="public";break;
		case 8:if(!RSS||!PUB)return false; $type="publicVote";break;
		default:return false;}
		
		
		$ov=$this->updateCompILookup($id);
		if($ov==-2)return false;
		$nv=$ov[$type];
		$nv--;
		if($nv==-2)$nv=1;
		$db->query("update ".T_COMPS." set ".$type."=$nv where id=$id");								
		global $ret;
		$ret['comp']=$nv;
		if($nv==0){$ret['color']="#ff0000";
		$ret['text']="Competition";
		}elseif($nv>=1){$ret['color']="#00ff00";			
		$ret['text']="Competition";
		}elseif($ov[$type."G"]>=1){$ret['color']="#FFA500";
		$ret['text']="Group";	
		}elseif($ov[$type."G"]==0){$ret['color']="#FFA500";
		$ret['text']="Group";	
		}else{$ret['text']="Disabled";
		$ret['color']="#ff0000";
		}
	return true;
	}

}
?>