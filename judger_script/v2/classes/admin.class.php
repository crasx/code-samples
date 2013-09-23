<?php
	
require CLASSES."admin.visibility.php";	
class admin extends adminVisibility{
	///////////////////////////////////////////////////////////////////////////
	///////////////////////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////


///////////////////////////////////////

}

function execute(){
switch($_GET['sub']){
	case "custom":
	define("LOADADMINCUJS", 1);
	$cfg=new admin;
	echo $cfg->createCustomForm();
	break;
	case "criteria":
	define("LOADADMINCRJS", 1);
	$cfg=new admin;
	echo $cfg->createCriteriaForm();	
	break;
	case "competitions":
	define("LOADADMINCOJS", 1);
	$cfg=new admin;
	echo $cfg->createCompetitionForm();
	break;
	case "groups":
	define("LOADADMINGJS", 1);
	$cfg=new admin;
	echo $cfg->createGroupForm();
	break;
	case "users":
	define("LOADADMINUJS", 1);
	$cfg=new admin;
	echo $cfg->initUser();	 
	break;
	case "visibility":
	define("LOADADMINVJS", 1);
	$cfg=new admin;
	echo $cfg->initVisibility();	 
	break;
	default:header("Location: ".BASE);
}
}
?>