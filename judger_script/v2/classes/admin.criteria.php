<?php
require CLASSES."admin.competitions.php";
class adminCriteria extends adminCompetitions{
	//////////////////////////////////////////////////////////////////////////////////////////
	function createCriteriaForm(){
		return "
		<div ><h2>Add criteria</h2>
		<form id=criteriaForm autocomplete=off action='javascript:submitForm(\"criteriaForm\", function(data){createCriteriaCallback(data);});' >
						<table class=stdForm ><tr><td>Name </td><td> <input type=text name=catname ></td></tr>
						<tr><td>Min score </td><td> <input type=text name=mscore  ></td></tr>
						<tr><td>Max score </td><td> <input type=text name=mxscore  ></td></tr>
						  <tr><td colspan=2 align=center ></td></tr></table></div><br clear='all' />
						  <div id=catlist style='padding-top: 40px;' >".$this->listCriteria()."</div></div>
";
		
	}
		

	
	
	function listCriteria($comps=false){
		global $db;
		$rows=$db->fetch_all_array("select * from ".T_CATS);
		$maxW=0;
		foreach($rows as $r){
			$sl=strlen($r['name']);
			$maxW=$sl>$maxW?$sl:$maxW;
			$ret.="<tr id=criteriaList".$r['id']." >".($comps?"<td><input type=checkbox name='slist[]' value='".$r['id']."' ></td>":"")."<td>".(!$comps?"<a href='javascript:populateCriteriaForm(".$r['id'].")' >".$r['name']."</a>":$r['name'])."</td><td>".$r['min']."</td><td>".$r['max']."</td></tr>";
		}
		$ret.="</table>";
		$maxW=6*$maxW;
		$ret="<div ".(!$comps?"style='width:50%;' class=stdList":"")." ><h2>Competition criteria</h2><table border=1 id=criteriaList class=sorted ><thead><tr>".($comps?"<th>&nbsp;</th>":"")."
					<th width='{$maxW}px'>Name</th>
					<th width='20px'>Min</th>
					<th width='20px'>Max</th></thead></tr>".$ret."</div>";
		return $ret;
	
}
//////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////

	
	//////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////ajaxed////////////////////////////////////
	function editCriteria($id=0){
		global $config, $db;
		if($id!=0){
			$i=$this->getCriteriaInfo($id);
			if(!$i||sizeof($i)==0)return "Error updating";
		}
		$name=$_POST['catname'];
		$min=$_POST['mscore'];
		$max=$_POST['mxscore'];
		if($name=='')return "catname";
		if(!is_numeric($max))return "mxscore";
		if(!is_numeric($min)) return "mscore";
		$kv=array('name'=>$name, 'min'=>$min, 'max'=>$max);
		if($id==0){
			return $db->query_insert(T_CATS, $kv);										
		}else{
			if($db->query_update(T_CATS, $kv, "id=$id"))return $id;
			else return false;
			
		}
					
	}
	

function deleteCriteria($d){
		global $db, $user;		
		$i=$this->getCriteriaInfo($d);
		if(!$i||sizeof($i)==0)return false;
		$db->query("delete from ".T_CATS." where id=$d");
		$db->query("delete from ".T_CCLIST."  where category=$d"); 
		$db->query("delete from ".T_SCORES." where category=$d");
		return true;		
	}
}
	
?>