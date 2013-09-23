<?php
require CLASSES."admin.criteria.php";
class adminCustom extends adminCriteria{
	function getUpdateCheckAsBool($name){
		if(isset($_POST[$name]))return $_POST[$name]=='1'||$_POST[$name]=='on';
		return false;
	}
	
	function editCustom($cid=0){
		
		if($cid!=0){
			$i=$this->getCustomInfo($cid);
			if(!$i||sizeof($i)==0)return "Error updating";
		}
		
		$name=$_POST['custname'];
		if($name=='')return "custname";
		$required=$this->getUpdateCheckAsBool('isrequired');
		$judge=$this->getUpdateCheckAsBool('showinjudge');
		$report=$this->getUpdateCheckAsBool('showinreport');
		if(RSS){	$rss=$this->getUpdateCheckAsBool('showinrss');
		$rssR=$this->getUpdateCheckAsBool('showinrssR');	
		}
		if(PUB){	$pub=$this->getUpdateCheckAsBool('showtopub');
		}
		//clist
		global $db;
				
		$ins=array('name'=>$name, 'required'=>$required, 'judge'=>$judge, 'mc'=>$report);
		if(RSS){$ins['rss']=$rss;
		$ins['rssR']=$rssR;
		}
		if(PUB)$ins['public']=$pub;
		
		if($cid==0){
			$cid= $db->query_insert(T_CUST, $ins);
		}else{
			$db->query_update(T_CUST, $ins, "id=$cid");
		}
		
		$db->query("delete from ".T_COMP_CUST." where custom=$cid");
		$clr=array();
		if(isset($_POST['clist']))
		foreach($_POST['clist'] as $c){
			$cinf=$this->getCustomInfo($c);
			if(is_array($cinf)){
				$db->query("insert into ".T_COMP_CUST."(competition, custom) values({$c}, {$cid})");
				$clr[]=$c;
				}
		}
		
		
		return array($cid, implode(",",$clr));
		
	}
	
	function listComps4Custom(){
				global $db;
		$rows=$db->fetch_all_array("select c.id, ifnull(g.id,0) gid,  ifnull(c.order, 0) `order`, c.name, c.description,  ifnull(g.name, '<em>None</em>') `group`  from ".T_COMPS. " c left join ".T_COMP_GROUPS." g on c.group=g.id order by name");
		$maxW=0;
		$grpW=0;
		foreach($rows as $r){
			$sl=strlen($r['name']);
			$maxW=$sl>$maxW?$sl:$maxW;
			$sl=strlen($r['description']);
			$maxW=$sl>$maxW?$sl:$maxW;
			$sl=strlen($r['group']);
			$grpW=$sl>$grpW?$sl:$grpW;
			$ret.="<tr id=compList".$r['id']." ><td><input type=checkbox name='clist[]' value='".$r['id']."' ></td><td>".$r['name']."<br><i>".$r['description']."</i></td><td>".$r['group']."</td><td>".$r['order']."</td></tr>";
		}
		$ret.="</table>";
		$maxW=6*$maxW;
		$ret="<div' class=stdList ><h2>Competitions containing field</h2><table border=1 id=compList class=sorted ><thead><tr><th>&nbsp;</th>
					<th width='{$maxW}px'>Name</th>
					<th width='{$grpW}px'>Group</th>
					  <th >Order</th></thead></tr>".$ret."</div>";
		return $ret;
	}
	
	function createCustomForm(){
		global $config;
			return "<div style='float: left; padding-right: 40px; text-align: center; width: 50%;'><form  id=customForm autocomplete=off action='javascript:submitForm(\"customForm\", function(data){createCustomCallback(data);});'  ><table class=stdForm >
			<tr><td colspan=2 ><h2>Custom field configuration</h2></td></tr>
			<tr><td>Custom field name:</td><td style='text-align:left;'><input type=text name=custname /></td></tr>
			<tr><td>Required</td><td style='text-align:left;'><input type=checkbox name=isrequired value=1  ></td></tr>
			<tr><td>Include on judge screen</td><td style='text-align:left;'><input type=checkbox name=showinjudge value=1 ></td></tr>
			<tr><td>Show to MC</td><td style='text-align:left;'><input type=checkbox name=showinreport value=1  ></td></tr>
			".(RSS?"
			<tr><td>Include on rss feed</td><td style='text-align:left;'><input type=checkbox name=showinrss value=1 ></td></tr>
			<tr><td>Include on rss results</td><td style='text-align:left;'><input type=checkbox name=showinrssR value=1 ></td></tr>":"").(PUB?"
			<tr><td>Show to public</td><td style='text-align:left;'><input type=checkbox name=showtopub value=1 ></td></tr>":"")."
			<tr id=submittr ><td colspan=2 align=center></td></tr></table></form></div><div style='float: left; padding-right: 40px; text-align: center;'>".$this->listComps4Custom()."</div><br clear=all / >".$this->listCustom();	
	}

function deleteCustom($d){
		global $db, $user;		
		$i=$this->getCustomInfo($d);
		if(!$i||sizeof($i)==0)return false;
		$db->query("delete from ".T_CUST." where id=$d");
		$db->query("delete from ".T_CUST_VALS."  where field=$d"); 
		return true;		
	}	
	
	function updateCustomCheck($id, $type){
		global $db;
		switch($type){case 2:$type="required";break;case 3:$type="mc";break;
		case 4:$type="judge";break;
		case 5:if(!RSS){if(!PUB)return false; else $type="public";}else$type="rss";break;
		case 6:if(!RSS){if(!PUB)return false; else $type="publicVote";}else$type="rssR";break;		
		case 7:if(!RSS&&!PUB)return false; $type="public";break;
		case 8:if(!RSS&&!PUB)return false; $type="publicVote";break;
		default:return false;}
		$ov=$this->updateCustomLookup($id,$type);
		if($ov==-1)return false;
		if($ov==0)$ov=1;
		else $ov=0	;
		if($ov==1&&$type=="rssR")$ov=time();
		$db->query("update ".T_CUST." set $type=$ov where id=$id");								
		global $ret;
		if($ov==0)$ret['color']="#ff0000";
		else $ret['color']="#00ff00";
		return true;
	}
	
	function updateCustomLookup($id, $v){
		global $db;
		if(!is_numeric($id))return -1;
		$r=$db->fetch_all_array("select * from ".T_CUST." where id=$id");	
		if(!is_array($r)||sizeof($r)!=1)return -1;
		return $r[0][$v];
	}
	
	//////////////////////////////////////////////////
	///////////////////////////////////////////////////
	
	
	
	function listCustom(){
		global $db, $config;
		$rows=$db->fetch_all_array("select * from ".T_CUST);
		$body.="<br clear=all><h2>Current custom fields</h2>
		<div style=' padding-right:30px;' >
		<table  border=1 id='customList' class=sorted  >
		<thead><tr><th>Name</th><th>Required in<br />registration</th><th>Show to MC</th><th>Show to judge</th>";
		if(RSS)$body.="<th>Show on rss <br />(Registration)</th><th>Show on rss <br />(Results)</th>";
		if(PUB)$body.="<th>Show to public</th>";
		$body.="</tr></thead>
		<tbody>";
		foreach($rows as $r){
			$body.="<tr id=customList".$r['id']." competitions='".$this->loadCustomComps($r['id'])."' >
			<td><a href='javascript:populateCustomForm(".$r['id'].")'>".$r['name']."</a></td>
			<td bgcolor='".($r['required']?"#00ff00":"#ff0000")."' onclick='javascript:updateCustomCheck(".$r['id'].", 2)'>&nbsp;</td>
			<td bgcolor='".($r['mc']?"#00ff00":"#ff0000")."' onclick='javascript:updateCustomCheck(".$r['id'].", 3)'>&nbsp;</td>
			<td bgcolor='".($r['judge']?"#00ff00":"#ff0000")."' onclick='javascript:updateCustomCheck(".$r['id'].", 4)'>&nbsp;</td>
			";
			$row=5;
			if(RSS)
			$body.="
			<td bgcolor='".($r['rss']?"#00ff00":"#ff0000")."' onclick='javascript:updateCustomCheck(".$r['id'].", ".$row++.")'>&nbsp;</td>
			<td bgcolor='".($r['rssR']?"#00ff00":"#ff0000")."' onclick='javascript:updateCustomCheck(".$r['id'].",  ".$row++.")'>&nbsp;</td>";			
			if(PUB)
			$body.="
			<td bgcolor='".($r['public']?"#00ff00":"#ff0000")."' onclick='javascript:updateCustomCheck(".$r['id'].",  ".$row++.")'>&nbsp;</td>";			
			$body.="</tr>";
		}
		$body.="</tbody></table></div>";			
		return $body;
		
	}

}
?>