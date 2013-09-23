<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/ajax.class.php';
$ajax=new ajaxClass;
$d=$_GET['do'];
$f=$_GET['for'];
ob_start();
switch($d){
	case "like":
		if(is_numeric($f)){
			if(!isset($_COOKIE['like_'.$f])){
				echo $ajax->incrementLikes($f);				
				setcookie("like_".$f, "1", time()+60*60*24*365*5, "/");
			}else echo "Already liked (".$ajax->loadLikes($f).")";
		}else echo "Invalid id";
	break;//end like
	case "addHaiku":
		if(is_numeric($f)){
			echo $ajax->addHaiku($f);
		}else echo "Invalid id";
	
	break; //add poem
	case "removeHaiku":
		if(is_numeric($f)){
			echo $ajax->removeHaiku($f);
		}else echo "Invalid id";
	
	break; //remove poem
	case "addAuthor":
		if(is_numeric($f)){
			echo $ajax->addAuthor($f);
		}else echo "Invalid id";
	
	break; //add author
	case "removeAuthor":
		if(is_numeric($f)){
			echo $ajax->removeAuthor($f);
		}else echo "Invalid id";
	
	break; //remove author 
	
}//end switch
ob_end_flush();

?>