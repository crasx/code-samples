<?php 
class configure{
private $iphone=false;
private $allowIphone=false;
private $rss=false;
private $public=false;
private $url='';
private $javascript=false;
private $compId=0;
private $license=0;
private $cfg=array();

function configure(){
	$this->iphone=strpos($_SERVER['HTTP_USER_AGENT'],"iPhone");
/*	$this->rss=$rss;
	$this->public=$public;
	$this->begin=$begin;
	$this->end=$end;
	*/
	$this->loadAllCfg();
}

function getInfo($key, $default){
	if(in_array($key, array_keys($this->cfg)))return $this->cfg[$key];
	return $default;
	
}

function loadAllCfg(){
	global $db;
	$loadComp=$db->fetch_all_array("select * from cj_drupal.cj_competition where name='".$db->escape(URI)."'");
	$this->compId=0;
	if(sizeof($loadComp)!=0){
		$this->compId=$loadComp[0]['id'];
		$loadLicense=$db->fetch_all_array("select * from cj_drupal.cj_license where (begin_use<".time()." and end_use>".time()." or model=-1) and `for`={$this->compId}");
		if(sizeof($loadLicense)!=0){
			$this->license=$loadLicense[0]['id'];
		}
		$loadAddon=$db->fetch_all_array("select * from cj_drupal.cj_addons where  `for`={$this->compId}");
		foreach($loadAddon as $la){
			if(strcmp($la['model'], "PP")==0){
				$this->public=true;	
			}
			if(strcmp($la['model'], "IP")==0){
				$this->allowIphone=true;	
			}
			if(strcmp($la['model'], "RSS")==0){
				$this->rss=true;	
			}
		}
		
		
	}

	$all=$db->fetch_all_array("Select * from ".T_CFG);
	if(is_array($all)){
		foreach($all as $a){
			$this->cfg[$a['key']]=$a['value'];	
		}
	}
}

function getRemoteTime(){
	$otz=date_default_timezone_get();
	$tz=$this->getInfo("timezone",  date_default_timezone_get());
	date_default_timezone_set($tz);
	$r=date("U");
	date_default_timezone_set($otz);
	return $r;
}

function hasRss(){
	return $this->rss;	
}

function showIphone(){
	return $this->iphone&&$this->allowIphone;	
}
function hasPublic(){
	return $this->public;	
}
function competitionEnabled(){
	return $this->license!=0;	
}

function checkCSS(){
	global $db;
	$sql = 'SELECT data FROM `cj_drupal`.cache where cid="variables"';
	$results = $db->fetch_all_array($sql); // Run the query
	if(!sizeof($results))return;
	$data=unserialize($results[0]['data']);
	$css=$data['color_pixture_reloaded_stylesheets'][0];
	define("PIXTURECSS", $css);
}

}
?>