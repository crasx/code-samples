<?php

$sqltableprefix="";//prefix to put before each table ie if it is atr_ then the art table will be atr_art
$viewPerPage=50;//number of people to view per page




//////////////DONT EDIT ANYTHING BELOW HERE//////////////
    $currentFile = $_SERVER["SCRIPT_NAME"];
    $parts = Explode('/', $currentFile); 
    $currentFile = $parts[count($parts) - 1];
//	error_reporting(0);
if($currentFile=="cfg.php"){
header("HTTP/1.1 404 Not Found");
}
$iphone = strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
global $sqldb,$sqltableprefix,$urlToRoot,$sqltables,$aesCrypt,$httpRoot;

define ("IPHONE", $iphone);
define("MAX_FILESIZE",$maxFileSize);
define("MAX_VIEW",$viewPerPage);
if(substr($uploaddir,-1)!="/")$uploaddir.="/";
define("UPLOAD_DIR",$uploaddir);
if(substr($styleuploaddir,-1)!="/")$styleuploaddir.="/";
define("STYLE_UPLOAD_DIR",$styleuploaddir);
define("SQL_PREFIX", $sqlprefix);
define("ROOT",$httpRoot);
require_once("declaretables.php");
$connection=mysql_connect($sqlserver,$sqluser,$sqlpassword) or die("Error connecting to database-Check your configuration settings in admin/cfg.php");
mysql_select_db($sqldb) or die("Error connecting to database-Check your configuration settings in admin/cfg.php");

function sqld($arg){
return mysql_real_escape_string($arg);
}
function dsql($arg){
return stripslashes($arg);	
}
?>