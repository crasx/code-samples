<?php
class main{
	function execute(){
		global $event;
		$event="4";
	}
	
	function requireLogin($action, $cancel, $msg, $btxt){
		$show=0;
		if(isset($_GET['vpw'])){
			$show=1;
			global $user;
			if(strcmp($user->getPassword(),md5($_GET['vpw'])==0)){
				return true;										 
			}
		}
		$r="<h2>Please enter your password $msg</h2>";
		if($show==1)$r.="<h3>Invalid password</h3>";
		$r.="<form action='javascript:getUri(\"$action&\",\"verp\", \"page\")' id=verp ><input type=password name=vpw ><br />
<input type=submit value='$btxt'><input type=button onclick='javascript:getHttp(\"$cancel\", \"page\")' value='Cancel' /></form>";	
		return $r;
		
	}		
	
	function getRecord($array, &$out){
		if(is_array($array)&&sizeof($array)==1){
			$out=$array[0];
			return true;
		}
		return false;
	}
}
?>