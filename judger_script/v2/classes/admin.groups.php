<?php
require CLASSES."admin.custom.php";
class adminGroups extends adminCustom{
	function updateGroupCheck($id, $type){
		global $db;

		switch($type){case 2:$type="register";break;case 3:$type="mc";break;
		case 4:$type="judge";break;
		case 5:if(!RSS){if(!PUB)return false; else $type="public";}else$type="rss";break;
		case 6:if(!RSS){if(!PUB)return false; $type="publicVote";}else$type="rssR";break;
		case 7:if(!RSS&&!PUB)return false; $type="public";break;
		case 8:if(!RSS&&!PUB)return false; $type="publicVote";break;
		default:return false;}
		$ov=$this->updateGroupLookup($id,$type);
		if($ov==-2)return false;
		if($ov==0)$ov=1;
		else $ov=0;		
		if($ov==1&&$type=="rssR")$ov=time();
		$db->query("update ".T_COMP_GROUPS." set $type=$ov where id=$id");								
		global $ret;
		if($ov==0)$ret['color']="#ff0000";
		else $ret['color']="#00ff00";
		return true;
	}
	
	function updateGroupLookup($id, $v){
		global $db;
		if(!is_numeric($id))return -2;
		$r=$db->fetch_all_array("select * from ".T_COMP_GROUPS." where id=$id");	
		if(!is_array($r)||sizeof($r)!=1)return -2;
		return $r[0][$v];
	}
	
	
	function createGroupForm(){
			return "<form  id=groupForm autocomplete=off action='javascript:submitForm(\"groupForm\", function(data){createGroupingCallback(data);});' ><table class=stdForm >
			<tr><td colspan=2 ><h2>Competition grouping configuration</h2></td></tr>
			<tr><td >Group name:</td><td style='text-align:left'><input type=text name=groupname /></td></tr>
			<tr><td >Show to registration:</td><td style='text-align:left'><input type=checkbox name=registration /></td></tr>
			<tr><td >Show to MC:</td><td style='text-align:left'><input type=checkbox name=MC /></td></tr>
			<tr><td >Show to judges:</td><td style='text-align:left'><input type=checkbox name=judging /></td></tr>
			
			".(RSS?"
			<tr><td >Show on RSS Registration:</td><td style='text-align:left'><input type=checkbox name=rss /></td>
			<tr><td >Show on RSS Results:</td><td style='text-align:left'><input type=checkbox name=rssR /></td></tr>":"").
			(PUB?"
			<tr><td >Show to public:</td><td style='text-align:left'><input type=checkbox name=public /></td></tr>
			<tr><td >Allow public to vote:</td><td style='text-align:left'><input type=checkbox name=publicV /></td></tr>":"")."
			<tr id=submittr ><td colspan=2 align=center></td></tr></table></form><br clear=all />".$this->listGrouping();	
		
	}

function deleteGroup($d){
		global $db, $user;		
		$i=$this->getGroupInfo($d);
		if(!$i||sizeof($i)==0)return false;
		$db->query("delete from ".T_COMP_GROUPS." where id=$d");
		$db->query("update ".T_COMPS." set `group`=0 where `group`=$d"); 
		return true;		
	}
	
	function editGroup($cid=0){		
		if($cid!=0){
			$i=$this->getGroupInfo($cid);
			if(!$i||sizeof($i)==0)return "Error updating";
		}
		
		$name=$_POST['groupname'];
		if($name=='')return "groupname";
		$reg=$this->getUpdateCheckAsBool('registration');
		$judge=$this->getUpdateCheckAsBool('judging');
		if(RSS){	
			$rss=$this->getUpdateCheckAsBool('rss');
			$rssR=$this->getUpdateCheckAsBool('rssR');
		}
		if(PUB){
			$pub=$this->getUpdateCheckAsBool('public');
			$pubV=$this->getUpdateCheckAsBool('publicV');
		}
		global $db;
				
		$ins=array('name'=>$name, 'register'=>$reg, 'judge'=>$judge, 'mc'=>$report);
		if(RSS){$ins['rss']=$rss;
		$ins['rssR']=$rssR;
		}
		if(PUB){$ins['public']=$pub;
		$ins['publicVote']=$pubV;
		}

		if($cid==0){
			return $db->query_insert(T_COMP_GROUPS, $ins);
		}else{
			$db->query_update(T_COMP_GROUPS, $ins, "id=$cid");
			return $cid;
		
		}
	}	
	function listGrouping(){
		global $db, $config;
		$rows=$db->fetch_all_array("select * from ".T_COMP_GROUPS);
		$body.="<div style=' margin-left: auto;margin-right: auto; padding-right:30px;' ><h2>Current groups</h2><table  border=1 id='groupList' class=sorted  ><thead><tr><th>Name</th><th>Show to<br />registration</th><th>Show to MC</th><th>Show to judge</th>";
		if(RSS)$body.="<th>Show on RSS<br />(registration)</th><th>Show on RSS<br />(results)</th>";
		if(PUB)$body.="<th>Show to public</th><th>Allow public<br />to vote</th>";
		$body.="</tr></thead><tbody>";
		foreach($rows as $r){
			$body.="<tr id=groupList".$r['id']." >
			<td><a href='javascript:populateGroupForm(".$r['id'].")'>".$r['name']."</a></td>
			<td bgcolor='".($r['register']==1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", 2)'>&nbsp;</td>
			<td bgcolor='".($r['mc']==1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", 3)'>&nbsp;</td>
			<td bgcolor='".($r['judge']==1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", 4)'>&nbsp;</td>";
			$tid=5;
			if(RSS)
			$body.="<td bgcolor='".($r['rss']==1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", ".$tid++.")'>&nbsp;</td>
			<td bgcolor='".($r['rssR']>=1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", ".$tid++.")'>&nbsp;</td>";	
			
			if(PUB)
			$body.="<td bgcolor='".($r['public']==1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", ".$tid++.")'>&nbsp;</td>
			<td bgcolor='".($r['publicVote']==1?"#00ff00":"#ff0000")."' onclick='javascript:updateGroupCheck(".$r['id'].", ".$tid++.")'>&nbsp;</td>";
			$body.="</tr>";
		}
		$body.="</tbody></table></div>";			
		return $body;
		
	}

}
?>