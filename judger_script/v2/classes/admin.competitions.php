<?php
require CLASSES."admin.base.php";
class adminCompetitions extends adminBase{
		function createCompetitionForm(){
	return "<div style='float: left; padding-right: 40px;text-align:center;width:50%;' >
	<form id=competitionForm autocomplete=off action='javascript:submitForm2(\"competitionForm\", function(data){createCompetitionCallback(data);});' >
	<h2>Add competition</h2>
	<table class=stdForm  >
<tr><td align=right >Name:</td><td><input type=text name=cname  /></td></tr>
<tr><td align=right >Order priority:</td><td><input type=text name=cpriority  /></td></tr>
<tr><td align=right >Group</td><td align=left >".$this->makeSelectGroup()."</td></tr>
<tr><td align=right >Type</td><td align=left ><select name=ctype onchange='ctypeChange()' ><option value=0 >Vote on criteria</option><option value=1 >Vote on person</option></select></td></tr>
<tr><td align=right >Description</td><td align=left ><textarea rows=2 cols=16 name=description ></textarea></td></tr>
<tr id=submittr ><td colspan=2 align=center ></td></tr>
</table><br /><br />
</form></div>
<div style='float:left; padding-right: 40px;text-align:center;' >".$this->listCriteria(true)."</div>
		
		<div id=lcomps >".$this->listCompetitions()."</div>
		";

	}	
	

function listCompetitions(){
	global $db, $config;
$list=$db->fetch_all_array("select c.id, ifnull(g.id,0) gid, g.name `group`,  ifnull(c.order, 0) `order`, c.name, c.type, c.description from ".T_COMPS. " c left join ".T_COMP_GROUPS." g on c.`group`=g.id order by c.name");
$maxW=0;
				
if(sizeof($list)>0)
foreach($list as $com){
			$sl=strlen($com['name']);
			$maxW=$sl>$maxW?$sl:$maxW;
			$rid=8;
	$ret.= "<tr id='competitionList".$com['id']."' categories='".$this->loadCategories($com['id'])."' ><td><a href='javascript:populateCompetitionForm(".$com['id'].")'>".$com['name']."</a><br /><i>".$com['description']."</i></td><td>".$com['order']."</td>
<td group=".$com['gid']." >".$com['group']."</td>
<td type=".$com['type']." >".($com['type']==1?"Select winner":"Criteia vote")."</td>".
"</tr>";	
	}
$ret.="</tbody></table>";
$maxW=$maxW*6;
$ret="
<table border=1 id='competitionList' class=sorted  >
<thead><tr><th name=col1 style='width:{$maxW}px' >Name</th><th>Order</th><th>Group</th><th>Type</th></tr>
</thead><tbody>".$ret;
	return $ret;
}

function loadCategories($id){
	global $db;
	$r=$db->fetch_all_array("select * from ".T_CCLIST." where competition=$id");
	$ret=array();
	foreach($r as $v){
		$ret[]=$v['category'];
	}
	return implode(",",$ret);
}
function loadCustomComps($id){
	global $db;
	$r=$db->fetch_all_array("select * from ".T_COMP_CUST." where custom=$id");
	$ret=array();
	foreach($r as $v){
		$ret[]=$v['competition'];
	}
	return implode(",",$ret);
}

function deleteCompetition($d){
		global $db, $user;		
		$i=$this->getCompetitionInfo($d);
		if(!$i||sizeof($i)==0)return false;
		$db->query("delete from ".T_COMPS." where id=$d");
		$db->query("delete from ".T_CCLIST."  where competition=$d"); 
		$db->query("delete from ".T_SCORES." where competition=$d");
		$db->query("delete from ".T_REGS." where competition=$d");
		$db->query("delete from ".T_CHECKS." where competition=$d");
		return true;		
	}
	
	function editCompetition($id=0){
		global $config, $db;
		if($id!=0){
			$i=$this->getCompetitionInfo($id);
			if(!$i||sizeof($i)==0)return "Invalid competition";
		}
			$name=$_POST['cname'];
			$description=$_POST['description'];
			if($name=="")return "cname";
			$group=$_POST['cgroup'];
			$order=$_POST['cpriority'];
			
			$type=$_POST['ctype'];
			if($order==""||!is_numeric($order))$order=0;
		
		$kv=array('name'=>$name, 'order'=>$order, 'description'=>$description, 'group'=>$group, "type"=>$type);
		
		if($id==0){
			if(!($id=$db->query_insert(T_COMPS, $kv)))return false;
			
		}else{
			if(!$db->query_update(T_COMPS, $kv, "id=$id")) return "Error updating";			
		}
		$db->query("delete from ".T_CCLIST." where competition=$id");
		$comps=array();
		if(isset($_POST['slist'])){
			$kv=array("competition"=>$id, "category"=>0);
			foreach($_POST['slist']	as  $k=>$v){	
				$i=$this->getCriteriaInfo($v);
				if(!$i||sizeof($i)==0)continue;
				$kv['category']=$v;	
				$comps[]=$v;
				$db->query_insert(T_CCLIST, $kv);
					
			}
		}
		///////////////////////////////
		
		
		$view=array();
		$viewk=array("registration", "judging","mc", "rss", "rssR", "public", "publicVote");
		
			$ov=$this->updateCompILookup($id);
		if($ov==-2)return "Error updating view options";
	foreach($viewk as $k=>$v){
		$nv=$ov[$k];	
		$gv=$ov[$v."G"];		
		if($nv==0){$view[$k]['color']="#ff0000";
		$view[$k]['text']="Competition";
		}elseif($nv==1){$view[$k]['color']="#00ff00";			
		$view[$k]['text']="Competition";
		}elseif($gv==1){
			$view[$k]['color']="#00ff00";
			$view[$k]['text']="Group";	
		}elseif($gv==0){
			$view[$k]['color']="#ff0000";
			$view[$k]['text']="Group";	
		}else{
			$view[$k]['text']="Disabled";
			$view[$k]['color']="#ff0000";
		}
	}
		return array($id, $comps, $view);
					
	}	
	

}
?>