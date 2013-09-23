<?php
class ajax{
	function execute(){
	ob_start();
			
	switch($_GET['page']){
		case "criteria":
		require(CLASSES."/admin.class.php");
		$adm=new admin;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "delete":
					echo $this->tFSIH($adm->deleteCriteria($_GET['for']));
				break;
				case "create":
					echo $this->tFSIH($adm->editCriteria());
				break;
				case "edit":
					echo $this->tFSIH($adm->editCriteria($_GET['for']));
					
				break;
					
			}
			
		break;
		case "competition":
		require(CLASSES."/admin.class.php");
		$adm=new admin;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "delete":
					echo $this->tFSIH($adm->deleteCompetition($_GET['for']));
				break;		
				case "create":
					$r=$adm->editCompetition();		
					if(is_array($r))
					echo $this->tFSIH($r[0], array("categories"=>implode(",", $r[1]), "view"=>$r[3]));
					else echo $this->tFSIH($r);
				break;
				case "edit":
					$r=$adm->editCompetition($_GET['for']);		
					if(is_array($r))
					echo $this->tFSIH($r[0], array("categories"=>implode(",", $r[1]),  "view"=>$r[3]));
					else echo $this->tFSIH($r);
					
				break;
					
			}
		break;
		case "visibility":
		require(CLASSES."/admin.class.php");
		$adm=new admin;
		if(!$adm->auth())break;
			switch($_GET['action']){		
				case "updateCompI":
				global $ret;
				 $ret=array();
				 $x=$adm->updateCompI($_GET['for'], $_GET['c']);				 
				 echo $this->tFSIH($x, $ret);		
				break;	
				case "allUseG":
				global $ret;
				 $ret=array();
				 $x=$adm->makeAllUseGroup($_GET['for']);				 
				 echo $this->tFSIH($x, $ret);		
				break;
			}
		break;
		case "user":
		require(CLASSES."/admin.class.php");
		$adm=new admin;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "delete":
					echo $this->tFSIH($adm->deleteUser($_GET['for']));
				break;
				case "create":
				 echo $this->tFSIH($adm->editUser());		
				break;
				case "edit":
				 echo $this->tFSIH($adm->editUser($_GET['for']));						
				break;
				case "check":
				global $ret;
				 $ret=array();
				 $x=$adm->updateUserCheck($_GET['for'],$_GET['row']);				 
				 echo $this->tFSIH($x, $ret);					
				break;
					
			}
			
		break;
		case "custom":
		require(CLASSES."/admin.class.php");
		$adm=new admin;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "delete":
					echo $this->tFSIH($adm->deleteCustom($_GET['for']));
				break;
				case "create":
				$res=$adm->editCustom();
				 echo $this->tFSIH($res[0], array("clist"=>$res[1]));		
				break;
				case "edit":
				$res=$adm->editCustom($_GET['for']);
				 echo $this->tFSIH($res[0], array("clist"=>$res[1]));		
					
				break;
				case "check":
				global $ret;
				 $ret=array();
				 $x=$adm->updateCustomCheck($_GET['for'],$_GET['row']);				 
				 echo $this->tFSIH($x, $ret);					
				break;
					
			}
			
		break;
		case "contestant":
		require(CLASSES."/register.class.php");
		$adm=new register;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "delete":
				global $retMax;
				 $retMax=array();
				 $x=$adm->deleteContestant($_GET['for']);				 
				 echo $this->tFSIH($x, $retMax);		
				break;
				case "deleteImage":
					echo $this->tFSIH($adm->deleteContestantImage($_GET['for']));
				break;
				case "create":
				global $retMax;
				 $retMax=array();
				 $x=$adm->editContestant();				 
				 echo $this->tFSIH($x, $retMax);		
				break;
				case "edit":
				global $retMax;
				 $retMax=array();
				 $x=$adm->editContestant($_GET['for']);
				 echo $this->tFSIH($x, $retMax);		
					
				break;
					
			}
			
		break;
		case "mc":
		require(CLASSES."/mc.class.php");
		$adm=new mc;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "updateCalled":
				global $ret;
				 $ret=array();
				 $x=$adm->updateCalled($_GET['for'],$_GET['c']);				 
				 echo $this->tFSIH($x, $ret);		
				break;
				case "updateLine":
				global $ret;
				 $ret=array();
				 $x=$adm->updateLine($_GET['for'],$_GET['c']);				 
				 echo $this->tFSIH($x, $ret);		
				break;
					
			}
			
		break;
		case "judge":
		require(CLASSES."/judge.class.php");
		$adm=new judge;
		if(!$adm->auth())break;
		global $ret;
		$ret=array();
		 $x=$adm->updateScore();				 
		 echo $this->tFSIH($x, $ret);	
		break;
		case "group":
		require(CLASSES."/admin.class.php");
		$adm=new admin;
		if(!$adm->auth())break;
			switch($_GET['action']){				
				case "create":
				global $ret;
				 $ret=array();
				 $x=$adm->editGroup();				 
				 echo $this->tFSIH($x, $ret);		
				break;
				case "edit":
				global $ret;
				 $ret=array();
				 $x=$adm->editGroup($_GET['for']);				 
				 echo $this->tFSIH($x, $ret);		
				break;
				case "delete":
				global $ret;
				 $ret=array();
				 $x=$adm->deleteGroup($_GET['for']);				 
				 echo $this->tFSIH($x, $ret);		
				break;
				case "check":
				global $ret;
				 $ret=array();
				 $x=$adm->updateGroupCheck($_GET['for'],$_GET['row']);				 
				 echo $this->tFSIH($x, $ret);					
				break;
					
					
			}
			
		break;
	}
	ob_end_flush();	
	}
	
	//true false id handeler
	function tFSIH($e, $a=array()){
		$r=array("success"=>($e===true||is_numeric($e)?true:"False"));		
		if(is_numeric($e))$r['id']=$e;
		else if(!($e===true))$r['error']= $e;		
		return json_encode(array_merge($r,$a));
	}
}

?>