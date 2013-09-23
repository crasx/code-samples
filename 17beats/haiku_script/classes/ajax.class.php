<?php
class ajaxClass{
	function incrementLikes($id){
		if(!is_numeric($id))return "Invalid id";
		global $db;
		$db->query("update ".T_HAIKU." set likes=likes+1 where id=$id");
		return $this->loadLikes($id);
	}
	function loadLikes($id){
		if(!is_numeric($id))return "Invalid id";
		global $db;
		$likes=$db->fetch_all_array("select likes from ".T_HAIKU." where id=$id");
		if(sizeof($likes)!=1)return"Invalid id";
		$li=$likes[0]['likes'];
		return $li." like".($li==1?"":"s");
	}	
	
	function addHaiku($id){
		global $db, $user;
		if(!$user->isValid())return "Not logged in";
		if(!is_numeric($id))return "Invalid id";
		$dat=$db->fetch_all_array("select * from ".T_HAIKU." where id=$id");
		if(sizeof($dat)!=1)return "Invalid id";
		$db->query("insert into ".T_FAVORITE_HAIKU."(user, haiku) values(".$user->getInfo('id').", $id) on duplicate key update haiku=$id");
		return "Remove favorite haiku";
	}
	
	
	function removeHaiku($id){
		global $db, $user;
		if(!$user->isValid())return "Not logged in";
		if(!is_numeric($id))return "Invalid id";
		$dat=$db->fetch_all_array("select * from ".T_HAIKU." where id=$id");
		if(sizeof($dat)!=1)return "Invalid id";
		$db->query("delete from ".T_FAVORITE_HAIKU." where user=".$user->getInfo('id')." and haiku=$id");
		return "Add favorite haiku";
	}
	
	function addAuthor($id){
		global $db, $user;
		if(!$user->isValid())return "Not logged in";
		if(!is_numeric($id))return "Invalid id";
		$dat=$db->fetch_all_array("select * from ".T_USERS." where id=$id");
		if(sizeof($dat)!=1)return "Invalid id";
		$db->query("insert into ".T_FAVORITE_AUTHORS."(user, author) values(".$user->getInfo('id').", $id) on duplicate key update author=$id");
		return "Remove favorite author";
	}
	
	
	function removeAuthor($id){
		global $db, $user;
		if(!$user->isValid())return "Not logged in";
		if(!is_numeric($id))return "Invalid id";
		$dat=$db->fetch_all_array("select * from ".T_USERS." where id=$id");
		if(sizeof($dat)!=1)return "Invalid id";
		$db->query("delete from ".T_FAVORITE_AUTHORS." where user=".$user->getInfo('id')." and author=$id");
		return "Add favorite author";
	}
	
	
	
	
	
	
}
?>