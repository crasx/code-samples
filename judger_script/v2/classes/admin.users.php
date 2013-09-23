<?php
require CLASSES."admin.groups.php";
class adminUsers extends adminGroups{
function editUser($id=0){
		global $user, $db;
		if($id!=0){
			$i=$this->getUserInfo($id);
			if(!$i||sizeof($i)==0)return "Invalid user";
		}
		$name=$_POST['name'];
		$login=$_POST['login'];
		$contact=$_POST['contact'];
		$password=$_POST['p1'];
		$p2=$_POST['p2'];
		$admin=false;
		$mc=false;
		$reg=false;
		$judge=false;
		if(isset($_POST['privs'])&&is_array($_POST['privs'])){
			$admin=in_array("a", $_POST['privs']);
			$mc=in_array("m", $_POST['privs']);
			$reg=in_array("r", $_POST['privs']);
			$judge=in_array("j", $_POST['privs']);
		}
		if($name=="")return "name";
		if($login=="")return "login";
		
			if($id==0&&(strcmp($password,$p2)!=0||$password==""))return "p1],[name=p2";
			if($id!=0&&($password!=""&&$p2!="")&&strcmp($password,$p2)!=0)return "p1],[name=p2";

		if($user->getId()==$uid&&!$admin)return "You must remain an admin";
		
		$li=$db->fetch_all_array("select * from ".T_USERS." where id!=$id and username='".$db->escape($login)."'");
		if(sizeof($li))return "Login in use!";
				
		$sets=array("name"=>$name, "username"=>$login, "contact"=>$contact, "admin"=>$admin?"1":"0","register"=>$reg?"1":"0","mc"=>$mc?"1":"0","judge"=>$judge?"1":"0");
		if($password!="")$sets['password']=md5($password);		
	
		if($id==0){
			return $db->query_insert(T_USERS, $sets);		
		}else{			
			if($db->query_update(T_USERS, $sets, "id=$id"))
				return $id;			
		}					
	}
	//////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////
	/////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////

	function initUser(){

	return "<h2>Add user</h2>
	<form id=userForm  autocomplete=off action='javascript:submitForm(\"userForm\", function(data){createUserCallback(data);});'>
					<table class=stdForm ><tr>
					<td align=right >Name:</td><td style='text-align:left'><input type=text name=name value='$name' /></td></tr><tr>
					<td align=right >Login:</td><td style='text-align:left'><input type=text name=login value='$login' /></td></tr><tr>
					<td align=right >Contact:</td><td style='text-align:left'><input type=text name=contact value='$contact' /></td></tr><tr>
					<td align=right >Password:</td><td style='text-align:left'><input type=password name=p1 /></td></tr><tr>
					<td align=right >Password again:</td><td style='text-align:left'><input type=password name=p2 /></td></tr><tr>
					<td align=center colspan=2 >Privileges</td></tr><tr>
					<td align=right >Admin:</td><td style='text-align:left'><input type=checkbox name=privs[] value=a /></td></tr><tr>
					<td align=right >MC:</td><td style='text-align:left'><input type=checkbox name=privs[] value=m  /></td></tr><tr>
					<td align=right >Registration:</td><td style='text-align:left'><input type=checkbox name=privs[] value=r /></td></tr><tr>	
					<td align=right >Judge:</td><td style='text-align:left'><input type=checkbox name=privs[] value=j /></td></tr><tr>
					<td align=center colspan=2 ></td></tr></table></form><br clear=all />".$this->listUsers();
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////////////////////////////////
	function listUsers(){
	global $db;
	
	$rs=$db->fetch_all_array("select * from ".T_USERS." order by name");
	
	$rotr.= "<div class=stdList ><h2>Current users</h2><table border=1 id=userList class=sorted  ><thead><tr>	
	<th>Name</th>
	<th>Login</th>
	<th>Contact</th>
	<th>Admin</th>
	<th>Registration</th>
	<th>MC</th>
	<th>Judge</th>
	</tr></thead><tbody>";
	foreach($rs as $r){
		$rotr.="<tr id='userList".$r['id']."'><td><a href='javascript:populateUserForm(".$r['id'].")'>".$r['name']."</a></td>";
		$rotr.="<td>".$r['username']."</td>";
		$rotr.="<td>".$r['contact']."</td>";
		
		$rotr.="<td bgcolor='".($r['admin']?"#00ff00":"#ff0000")."' defaultcolor='".($r['admin']?"#00ff00":"#ff0000")."' onclick='javascript:updateUserCheck(".$r['id'].", 4)'>&nbsp;</td>";
		$rotr.="<td bgcolor='".($r['register']?"#00ff00":"#ff0000")."' defaultcolor='".($r['register']?"#00ff00":"#ff0000")."' onclick='javascript:updateUserCheck(".$r['id'].", 5)'>&nbsp;</td>";
		$rotr.="<td bgcolor='".($r['mc']?"#00ff00":"#ff0000")."' defaultcolor='".($r['mc']?"#00ff00":"#ff0000")."' onclick='javascript:updateUserCheck(".$r['id'].", 6)'>&nbsp;</td>";
		$rotr.="<td bgcolor='".($r['judge']?"#00ff00":"#ff0000")."' defaultcolor='".($r['judge']?"#00ff00":"#ff0000")."' onclick='javascript:updateUserCheck(".$r['id'].", 7)'>&nbsp;</td>";
		$rotr.="</tr>";
	}
	return $rotr."</tbody></table></div>";
}


	function updateUserCheck($id, $type){
		global $db;

		switch($type){
		case 4:$type="admin";break;
		case 5:$type="register";break;
		case 6:$type="mc";break;
		case 7:$type="judge";break;
		default:return false;}
		$ov=$this->updateUserLookup($id,$type);
		if($ov==-2)return false;
		if($ov==0)$ov=1;
		else $ov=0;		
		$db->query("update ".T_USERS." set $type=$ov where id=$id");								
		global $ret;
		if($ov==0)$ret['color']="#ff0000";
		else $ret['color']="#00ff00";
		return true;
	}
	
	function updateUserLookup($id, $v){
		global $db;
		if(!is_numeric($id))return -2;
		$r=$db->fetch_all_array("select * from ".T_USERS." where id=$id");	
		if(!is_array($r)||sizeof($r)!=1)return -2;
		return $r[0][$v];
	}

function deleteUser($d){
		global $db;		
		$i=$this->getUserInfo($d);
		if(!$i||sizeof($i)==0)return false;
		$db->query("delete from ".T_USERS." where id=$d");
		$db->query("delete from ".T_SCORES." where judge=$d");
		return true;		
	}
	

	//////////////////////////////////////////////////////////////////////////////////
	//////////////////////////////////////////////////////////////////////////////////
	function getUserInfo($id){
		global $db;
		if(!is_numeric($id))return false;
		return $db->fetch_all_array("select * from ".T_USERS." where id=".$id);
	}

}
?>