<?php

class submit{
	
private $regFields=array(
		"Title"=>array("title", 0, "title", "titleInput"),
		"Category"=>array("category", 1, "category", "categoryInput"),
		""=>array("postas", -1, "checkbox", "postasInput"),
		"Haiku"=>array("haiku", 1, "haiku", "haikuInput"),
		"Comment"=>array("comment", 0, "comment", "haikuInput"),
		" "=>array("draft", -1, "checkbox", "draftInput"),
		"  "=>array("notify_accept", -1, "notify", "notifyInput"),
		
		/*"Location"=>array("location", 1, "location", "locationInput"),
		"Phone"=>array("phone", 1, "phone", "phoneInput"),
		"Timezone"=>array("timezone", 1, "timezone", "timezoneInput"),
		"Picture"=>array("picture", 1, "picture", "pictureInput"),
		"About"=>array("about", 0,"about", "aboutInput"),
		*/);
	
function create(){
	if(!isset($_SESSION['submit']))return "<h3>Error: You can not submit a haiku twice!</h3>";
	unset($_SESSION['submit']);
	global $db, $user;
	$insert=array();
	foreach($this->regFields as $k=>$v){
		if($v[1]>-1)
		$insert[$v[0]]=$_POST[$v[0]];	
	}
	if($user->isValid()&&(isset($_POST['postas'])&&$_POST['postas']=='on')){
		
		
	}else{
		$insert['notify_email']=$_POST['notify_email'];
			if ($_SERVER['HTTP_X_FORWARD_FOR']) {
			$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
			} else {
			$ip = $_SERVER['REMOTE_ADDR'];
			}
		$db->query("insert into ".T_IPS." (ip) values(INET_ATON('".$ip."')) on duplicate  key update id=id");
		$ipi=$db->fetch_all_array("select id from ".T_IPS." where ip=INET_ATON('".$ip."')");
		
		$insert['post_ip']=$ipi[0]['id'];
		$insert['notify_accept']=(isset($_POST['notify_accept'])&&$_POST['notify_accept']=='on')?"1":"0";
		$insert['anonymous']=1;
		$insert['creator']=-1;
		if(!$user->isValid()){
			$insert2=array("email"=>$_POST['notify_email']);
			$db->query_insert(T_GUEST_EMAILS, $insert2, "on duplicate key update id=id");	
			$gie=$db->fetch_all_array("select id from ".T_GUEST_EMAILS." where email='".$db->escape($_POST['notify_email'])."'");
			$insert['notify_email']=$gie[0]['id'];
		}else{
			$insert['creator']=$user->getInfo('id');
			$insert['anonymous']=(isset($_POST['postas'])&&$_POST['postas']=='on')?"1":"0";
		}
		
		
		$res=$db->query_insert(T_HAIKU, $insert);
		if($res===false)return "<h3>There was an error submitting your haiku.</h2>";
		return "<h3>Haiku successfully created!</h3><br />".($insert['notify_accept']?"You will be notified when a decision is made to accept or reject your haiku":"Haiku submitted! It may or may not appear in the list soon.");
	}
	return "huh?";
}

function createForm($error=""){
	global $user;
	setcookie("formsubmit", "1", time()+60*5);
	//if(!in_array($error,array_keys($this->regFields))&&$error!=""&&$error!="Humanization")echo "<font color=red >$error</font>";
	$ret="<form action='?d=1' method=post><table>\n";
	foreach($this->regFields as $k=>$v){
		if($error!=""&&strcmp($error,$k)==0)$ret.="<tr><td><b><font color=red >$k</font></b>";
		else $ret.="<tr><td valign=top><b>$k</b>";
			if($v[1]>0)$ret.="*";
			$ret.="</td><td>".($this->$v[3]($v, $error))."</td></tr>\n";
		
	}
	$_SESSION['submit']=1;
	if($error!=""&&$error!="Humanization")echo "<font color=red >$error</font>";
	$ret.=($user->isValid()?"":"<tr><td valign=top ><b>".($error=="Humanization"?"<font color=red >Humanization</font>":"Humanization").'</b>*</td><td><img id="regcaptcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" style=float:left; />
    <input type="text" name="captcha_code" size="10" maxlength="6" /> <br>
    <a href="#" onclick="document.getElementById(\'regcaptcha\').src = \'/securimage/securimage_show.php?\' + Math.random(); return false">Reload Image</a><br clear=all /><i>Login to remove captcha</i><br /><br /></td></tr>');
	if(isset($_SESSION['ignore_syllables'])){
		unset($_SESSION['ignore_syllables']);
		$ret.="<input type=checkbox name=ignore_syllables checked /><label for=ignore_syllables >Ignore syllable count</label>";
	}
	$ret.='<tr><td align=center colspan=2 ><input type=submit value="Submit" class=submit style="height: 28px; width: 65px; font-weight:bold;"/></td></tr></table></form>';
	return $ret;
	}
	
	
function validInput(){
	global $user;
	if(!$user->isValid()){
		include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
			return "Humanization";
		}
	}
	
	foreach($this->regFields as $k=>$v){
		$r=$this->$v[2]($v);
		if(!($r===true)) 
			if(!($r===false))return $r;
			else return $k;
	}
	return true;
}


	function titleInput($arr, $e){
		return "<input type=text value='".htmlentities($_POST[$arr[0]], ENT_QUOTES)."'	name=".$arr[0]." style='width:300px;' /><br /><br />";
	}
	function categoryInput($arr, $e){
		global $loader;
		$rrr=$loader->loadCategories();
		$ret="<select name='".$arr[0]."'>";
		foreach($rrr as $r){
			if(isset($_POST[$arr[0]])&&$_POST[$arr[0]]==$r['id'])$s="selected";
			$ret.="<option value=$r[id] $s >$r[name]</option>";
		}
		$ret.="</select>";
		
		return $ret;
	}
	
	
	function postasInput($arr, $e){
		global $user;
		return "<input type=checkbox name=".$arr[0]." ".($user->isValid()?(isset($_POST[$arr[0]])&&$_POST[$arr[0]]=='on'?"checked":""):"checked disabled")." /><label for=".$arr[0]." >Post as anonymous</label> ".($user->isValid()?"":"<br />
<i>Login to post as non-anonymous</i>");
	}

	function notifyInput($arr, &$e){
		global $user;
		$r= "<input type=checkbox name=".$arr[0]." ".(isset($_POST[$arr[0]])&&$_POST[$arr[0]]=='on'?"checked":"")." /><label for=".$arr[0]." >Email me if accepted</label> ".($user->isValid()?"":"<br />".($e=="Email"?"<font color=red><b>Email</b></font> ":"Email ").
"<input type=text name=notify_email value='".htmlentities($_POST['notify_email'], ENT_QUOTES)."'><br /><br />")."";
		if($e=="Email")$e="";
		return $r;
	}

	function draftInput($arr, $e){
		global $user;
		return "<input type=checkbox name=".$arr[0]." ".($user->isValid()?(isset($_POST[$arr[0]])&&$_POST[$arr[0]]=='on'?"checked":""):" disabled")." /><label for=".$arr[0]." >Save as draft</label> ".($user->isValid()?"":"<br />
<i>Login to save draft</i><br /><br />")."";
	}

	function haikuInput($arr, $e){
		return "<textarea name=".$arr[0]." rows=3 cols=30 >".htmlentities($_POST[$arr[0]])."</textarea>";
	}
	
	function notify($arr){
		global $user;
		if(isset($_POST['notify'])&&$_POST['notify']=='on'){
			if($user->isValid()) return true;			
			$e=$_POST['notify_email'];
			if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $e)){
				return "Email";
			}
		}
		return true;
	}
	
	function title($arr){
		$title= $_POST[$arr[0]];	
		if(strlen($title)>255)return "Title too long!";
		return true;
	}
	function checkbox($arr){
		return true;
	}

	function category($arr){
		global $loader;
		$rrr=$loader->loadCategories();
		$sel= $_POST[$arr[0]];	
		foreach($rrr as $r){
			if($r['id']==$sel)return true;	
		}
		return false;
	}
	function comment($arr){
		return true;		
	}
	function haiku($arr){
		global $user;
		if($user->isValid()&&isset($_POST['draft'])&&$_POST['draft']=='on')return true;
		$haiku= $_POST[$arr[0]];	
		$output = preg_replace('/\s\s+/', ' ', $haiku);
		$total_syllables = 0;
		$word_array = explode(" ",$output);
		if (count($word_array)>0 && !empty($output)){
			foreach($word_array as $key=>$value){
				$total_syllables +=$this->count_syllables($value);
			}
		}
		if(isset($_POST['ignore_syllables'])&&$_POST['ignore_syllables']=='on'){}
		else
		if($total_syllables!=17){
			$_SESSION['ignore_syllables']=1;
			return "Error: I counted $total_syllables syllables. <br />If this is wrong feel free to submit again and the limit will be ignored";
		}
		return true;
	}

//http://www.russellmcveigh.info/content/html/syllablecounter.php
function count_syllables($word) {
global $split_array;
$subsyl = array(
'cial',
'tia',
'cius',
'cious',
'uiet',
'gious',
'geous',
'priest',
'giu',
'dge',
'ion',
'iou',
'sia$',
'.che$',
'.ched$',
'.abe$',
'.ace$',
'.ade$',
'.age$',
'.aged$',
'.ake$',
'.ale$',
'.aled$',
'.ales$',
'.ane$',
'.ame$',
'.ape$',
'.are$',
'.ase$',
'.ashed$',
'.asque$',
'.ate$',
'.ave$',
'.azed$',
'.awe$',
'.aze$',
'.aped$',
'.athe$',
'.athes$',
'.ece$',
'.ese$',
'.esque$',
'.esques$',
'.eze$',
'.gue$',
'.ibe$',
'.ice$',
'.ide$',
'.ife$',
'.ike$',
'.ile$',
'.ime$',
'.ine$',
'.ipe$',
'.iped$',
'.ire$',
'.ise$',
'.ished$',
'.ite$',
'.ive$',
'.ize$',
'.obe$',
'.ode$',
'.oke$',
'.ole$',
'.ome$',
'.one$',
'.ope$',
'.oque$',
'.ore$',
'.ose$',
'.osque$',
'.osques$',
'.ote$',
'.ove$',
'.pped$',
'.sse$',
'.ssed$',
'.ste$',
'.ube$',
'.uce$',
'.ude$',
'.uge$',
'.uke$',
'.ule$',
'.ules$',
'.uled$',
'.ume$',
'.une$',
'.upe$',
'.ure$',
'.use$',
'.ushed$',
'.ute$',
'.ved$',
'.we$',
'.wes$',
'.wed$',
'.yse$',
'.yze$',
'.rse$',
'.red$',
'.rce$',
'.rde$',
'.ily$',
//'.ne$',
'.ely$',
'.des$',
'.gged$',
'.kes$',
'.ced$',
'.ked$',
'.med$',
'.mes$',
'.ned$',
'.sed$',
'.nce$',
'.rles$',
'.nes$',
'.pes$',
'.tes$',
'.res$',
'.ves$',
'ere$'
// ORIGINALLY ONLY 'are' appeared below 
/*
'abe',
'ace',
'ade',
'age',
'ale',
'ate',
*/
//'are'
 );
 
$addsyl = array(
'ia',
'riet',
'dien',
'ien',
'iet',
'iu',
'iest',
'io',
'ii',
'ily',
'.oala$',
'.iara$',
'.ying$',
//'.reer$',
'.earest',
/*
'.aber',
'.acer',
'.ader',
'.ager',
'.aler',
'.arer',
'.ater',
*/
'.arer',
'.aress',
//
'.eate$',
'.eation$',
'[aeiouym]bl$',
'[aeiou]{3}',
'^mc','ism',
'^mc','asm',
'([^aeiouy])\1l$',
'[^l]lien',
'^coa[dglx].',
'[^gq]ua[^auieo]',
'dnt$'
   );
  // UBER EXCEPTIONS - WHOLE WORDS THAT SLIP THROUGH THE NET OR SOMEHOW THROW A WOBBLY
$exceptions_one = array(
"abe",
"ace",
"ade",
"age",
"ale",
"are",
"use",
"ate",
"one"
);
   // Based on Greg Fast's Perl module Lingua::EN::Syllables
  $word = preg_replace('/[^a-z]/is', '', strtolower($word));
   $word_parts = preg_split('/[^aeiouy]+/', $word);
   foreach ($word_parts as $key => $value) {
   if ($value <> '') {
   $valid_word_parts[] = $value;
//
  }
  }
   
  $syllables = 0;
   // Thanks to Joe Kovar for correcting a bug in the following lines
  foreach ($subsyl as $syl) {
  $syllables -= preg_match('~'.$syl.'~', $word);
  }
   foreach ($addsyl as $syl) {
   $syllables += preg_match('~'.$syl.'~', $word);
  }
 if (strlen($word) == 1) {
  //$syllables++;
  }
  // UBER EXCEPTIONS - WORDS THAT SLIP THROUGH THE NET
  if (in_array($word,$exceptions_one,true)){
  $syllables -= 1;
  }
  //
  $syllables += count($valid_word_parts);
  $syllables = ($syllables == 0) ? 1 : $syllables;
  if ($syllables>1){
//
  }
  return $syllables;
  }
}
?>