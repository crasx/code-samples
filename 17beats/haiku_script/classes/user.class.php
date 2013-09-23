<?php
class user{

private $regFields=array(
		"Username"=>array("username", 1, "username", "standardInput"),
		"Password"=>array("password", 3, "password", "passwordInput"),
		"Verify password"=>array("password2", 2, "password2", "passwordInput"),
		"Email"=>array("email", 1, "email", "standardInput"),
		/*"Location"=>array("location", 1, "location", "locationInput"),
		"Phone"=>array("phone", 1, "phone", "phoneInput"),
		"Timezone"=>array("timezone", 1, "timezone", "timezoneInput"),
		"Picture"=>array("picture", 1, "picture", "pictureInput"),
		"About"=>array("about", 0,"about", "aboutInput"),
		*/);
		

private $user='';
private $uid=-1;
private $userInfo=array();
private $tickets=array();
private $servers=array();
private $attempt=false;
private $captcha=false;


function doLogin(){
	session_start();
	if(isset($_GET['logout'])){	
			setcookie("persist", "", 1, "/");
			setcookie("login", "", 1, "/");
			header("Location: /");
		return;
	}
    $iv_size = mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB);
    $iv = mcrypt_create_iv($iv_size, MCRYPT_RAND);
	
	if(isset($_POST['haiku_loginusername'])){
			$_SESSION['attempt']=1;
			$this->user= $_POST['haiku_loginusername'];
			$this->password=md5($_POST['haiku_loginpassword']);
	}else{
		if(isset($_COOKIE['login'])&&isset($_COOKIE['persist'])){
			$this->user=$_COOKIE['login'];	
			$this->password=mcrypt_decrypt(MCRYPT_RIJNDAEL_256, $_SERVER['REMOTE_ADDR'].CRYPTKEY, $_COOKIE['persist'],MCRYPT_MODE_ECB, $iv);
		}
	}
	if($this->user=="")return;
	
	if($thisA){
		include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
			$this->captcha=true;
			return;
		}
	}		
	global $db;
	$this->attempt=true;

	$usr=$db->fetch_all_array("select * from ".T_USERS." where username='".$db->escape($this->user)."'");
	if(sizeof($usr)==1){		
		if(strcmp($usr[0]['password'],$this->password)!=0){
			return;			
		}
		setcookie("login", $this->user, time()+60*60*3, "/");
		setcookie("persist", mcrypt_encrypt(MCRYPT_RIJNDAEL_256, $_SERVER['REMOTE_ADDR'].CRYPTKEY, $this->password,MCRYPT_MODE_ECB, $iv), time()+60*60*3, "/");
		if ($_SERVER['HTTP_X_FORWARD_FOR']) {
		$ip = $_SERVER['HTTP_X_FORWARD_FOR'];
		} else {
		$ip = $_SERVER['REMOTE_ADDR'];
		}
		$db->query("insert into ".T_IPS." (ip) values(INET_ATON('".$ip."')) on duplicate  key update id=id");
		$db->query("insert into ".T_USER_IPS." (user, ip) (select ".$usr[0]['id']." user, id ip from ".T_IPS." where ip=INET_ATON('".$ip."')) on duplicate key update user=user");
	
		
		$_SESSION['attempt']=0;
		$this->uid=$usr[0]['id'];
		
		$this->userInfo=$usr[0];

	}

}

function create(){
	global $db;
	$insert=array();
	foreach($this->regFields as $k=>$v){
		if($v[1]!=2)
		$insert[$v[0]]=$_POST[$v[0]];	
		if($v[1]==3)		
		$insert[$v[0]]=md5($_POST[$v[0]]);	
	}
	$insert['email_auth']=rand(100000,1000000000);
	$res=$db->query_insert(T_USERS, $insert);
	if($res===false)return "<h3>There was an error creating your user.</h2>";
	$this->sendActivationEmail($insert['email'], $insert['username'],  $insert['email_auth']);
	return "<h3>User successfully created!</h3>You should recieve an email with an activation link soon. This is to verify I am able to send you emails if needed and to ensure you can recieve weekly syndications (if so desired, you can change this in the control panel).<br />
<br />While you wait click <a href='/clients'>here</a> to login and update your profile. However I will need you to verify your email before you post haiku or make drafts.";
	
}


function getInfo($id){
	if(!in_array($id, array_keys($this->userInfo)))return "";
	else{
		return $this->userInfo[$id];
	}
	
}

function attemptedLogin(){
	return $this->attempt;
}
function invalidCaptcha(){
	return $this->captcha;
}

function isAdmin(){
	return $this->getInfo("admin")==1;
}

function isEmailVerified(){
	return $this->getInfo("email_auth")==0;
}

function activateEmail($user, $key){
	global $db;
	$user=strtolower($user);
	$ppl=$db->fetch_all_array("select * from ".T_USERS." where  username='".$db->escape($user)."'");
	if(sizeof($ppl)!=1)return 0;
	$smarty=new smarty;
	if(strcmp($key, $ppl[0]['email_auth'])==0){
		$emailHash=rand(100000, 999999);
		$db->query("update ".T_USERS." set email_auth=0, email_hash=$emailHash where username='".$db->escape($user)."'");
			$smarty->left_delimiter = '{{{';
			$smarty->right_delimiter = '}}}';
			$smarty->template_dir = PATHTOROOT.'templates';
			$smarty->compile_dir = PATHTOROOT.'templates_c';
			$smarty->cache_dir = PATHTOROOT.'cache';
			$smarty->config_dir = PATHTOROOT.'configs';
			$smarty->assign('username',$ppl[0]['username']);
			$smarty->assign('email_hash',$emailHash);	
			$emailText=$smarty->fetch("email/activated.tpl");
			sendmail($ppl[0]['email'], "noreply@17beats.com", "No reply", "More info from 17beats.com", $emailText);
			return 1;
	}else if($ppl[0]['email_auth']==0)return -1;
	return 0;
}

function isValid(){
	return $this->uid!=-1;	
}

function createRegisterForm($error=""){
	if(!in_array($error,array_keys($this->regFields))&&$error!=""&&$error!="Humanization")echo "<font color=red >$error</font>";
	$ret="<form action=?d=1 method=post><table>\n";
	foreach($this->regFields as $k=>$v){
		if($error!=""&&strcmp($error,$k)==0)$ret.="<tr><td><b><font color=red >$k</font></b>";
		else $ret.="<tr><td><b>$k</b>";
			if($v[1]>0)$ret.="*";
			$ret.="</td><td>".($this->$v[3]($v))."</td></tr>\n";
		
	}
	return $ret.'<tr><td><b>'.($error=="Humanization"?"<font color=red >Humanization</font>":"Humanization").'</b>*</td><td><img id="regcaptcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
    <input type="text" name="captcha_code" size="10" maxlength="6" /><br>
    <a href="#" onclick="document.getElementById(\'regcaptcha\').src = \'/securimage/securimage_show.php?\' + Math.random(); return false">Reload Image</a><br></td><tr><td colspan=2 align=center>By registering you agree to the <a href="/about#terms" target=_blank >Terms and conditions</a><br />
<input type=submit value="Register" class="submit" /></td></tr></table></form>';
	}
	
	function sendActivationEmail($email, $username, $email_auth){
	$smarty = new Smarty;
	
	$smarty->left_delimiter = '{{{';
	$smarty->right_delimiter = '}}}';
	$smarty->template_dir = PATHTOROOT.'templates';
	$smarty->compile_dir = PATHTOROOT.'templates_c';
	$smarty->cache_dir = PATHTOROOT.'cache';
	$smarty->config_dir = PATHTOROOT.'configs';

	$smarty->assign('username',$username);
	$smarty->assign('username_safe',urlencode($username));
  	$smarty->assign('email_auth',$email_auth);	
	$emailText=$smarty->fetch("email/activate.tpl");
	sendmail($email, "noreply@17beats.com", "No reply", "Welcome to 17beats.com!", $emailText);
	
	}


function validInput(){
	
		include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
		$securimage = new Securimage();
		if ($securimage->check($_POST['captcha_code']) == false) {
			return "Humanization";
		}
	
	foreach($this->regFields as $k=>$v){
		$r=$this->$v[2]($v);
		if(!($r===true)) 
			if(!($r===false))return $r;
			else return $k;
	}
	global $db;
	$rw=$db->fetch_all_array("select * from ".T_USERS." where email='".$db->escape(strtolower($_POST['email']))."'");
	if(sizeof($rw)>0)return "Email in use";
	return true;
}

	function email($arr){
		$e=strtolower($_POST[$arr[0]]);
		$_POST[$arr[0]]=$e;
		if(!preg_match('/^[^@]+@[a-zA-Z0-9._-]+\.[a-zA-Z]+$/', $e)){
			return "Invalid email";
		}
		$e=mysql_real_escape_string($e);
		global $db;
		if(sizeof($db->fetch_all_array("select * from ".T_USERS." where email='$e'"))>0)return "Email address in use";
		return true;
	}
	function username($arr){
		$e=$_POST[$arr[0]];
		if($e=="")return "Blank username";
		if(preg_match("/[^a-zA-Z0-9_-]/", $e)!=0)return "Username should only contain letters, numbers, - and _";
		
		global $db;
		if(sizeof($db->fetch_all_array("select * from ".T_USERS." where username='".$db->escape(strtolower($e))."'"))>0)return "Username in use";
		return true;
	}
	function standard($arr){
		return $_POST[$arr[0]]!="";	
	}
	function standardInput($arr){
		return "<input type=text value='".htmlentities($_POST[$arr[0]], ENT_QUOTES)."'	name=".$arr[0]." />";
	}
	
	function passwordInput($arr){
		return "<input type=password value='' name=".$arr[2]." />";		
	}
	
	function password($arr){
		if($_POST[$arr[0]]=="")return false;
		if(strlen($_POST[$arr[0]])<6)return "Password should be at least 6 characters";		
		return true;
	}
	function none(){return true;}
	
	function password2($arr){
		if($_POST[$arr[0]]=="")return false;
		if(strcmp($_POST[$arr[0]], $_POST['password'])!=0)return "Passwords didn't match";		
		return true;
	}


////////////////////////////////////////////////////////////////////Password
////////////////////////////////////////////////////////////////////
	function changePassword($username, $password){
		
	}
	

	function validPasswordInput(){
			include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
			$securimage = new Securimage();
			if ($securimage->check($_POST['captcha_code']) == false) {
				return "Humanization";
			}
		$u=$_POST['username'];
		$e=$_POST['email'];			  
		
		global $db;
		$rw=$db->fetch_all_array("select * from ".T_USERS." where email='".$db->escape($e)."' and username='".$db->escape($u)."'");
		if(sizeof($rw)<1)return "User/email combo not found";
		return $rw;
	}

	function validatePasswordReset($err=""){
		$u=$_GET['user'];
		$e=$_GET['act'];			  
		
		global $db;
		$rw=$db->fetch_all_array("select * from ".T_USERS." where password_forgot='".$db->escape($e)."' and username='".$db->escape($u)."'");
		if(sizeof($rw)<1)return false;
		return "<form action=?d=1  method=post>
		Enter new password for ".$rw[0]['username']."<br />".($err==""?"":"<font color=red >$err</font>")."
		<table><tr><td><b>Password</b></td><td><input type=password name=p1 /></td></tr>
		<tr><td><b>Verify password</b></td><td><input type=password name=p2 /></td></tr>
		<tr><td colspan=2 align=center ><input type=submit value=Reset class=submit /></td></tr></table></form>";
	}
	function validatePasswordInputReset(){
		$u=$_GET['user'];
		$e=$_GET['act'];			  
		
		global $db;
		$rw=$db->fetch_all_array("select * from ".T_USERS." where password_forgot='".$db->escape($e)."' and username='".$db->escape($u)."'");
		if(sizeof($rw)<1)return false;
		if(strlen($_POST['p1'])<6)return "Password should be at least 6 characters";		
		if(strcmp($_POST["p1"], $_POST['p2'])!=0)return "Passwords didn't match";		
		$p=$db->escape(md5($_POST['p1']));
		return $db->query("update ".T_USERS." set password='$p', password_forgot='' where username='$u'");
		
	}
	
	
	
	function resetPasswordEmail($ui){
	$smarty = new Smarty;
	
	$smarty->left_delimiter = '{{{';
	$smarty->right_delimiter = '}}}';
	$smarty->template_dir = PATHTOROOT.'templates';
	$smarty->compile_dir = PATHTOROOT.'templates_c';
	$smarty->cache_dir = PATHTOROOT.'cache';
	$smarty->config_dir = PATHTOROOT.'configs';
	$code = md5(uniqid(rand(), true));
	$smarty->assign('username',$ui[0]['username']);
	$smarty->assign('username_safe',urlencode($ui[0]['username']));
  	$smarty->assign('password_auth',$code);	
  	$smarty->assign('ip',$_SERVER['REMOTE_ADDR']);	
	global $db;
	$db->query("update ".T_USERS." set password_forgot='".$db->escape($code)."' where username='".$db->escape($ui[0]['username'])."'");
	
	$emailText=$smarty->fetch("email/password.tpl");
	sendmail($ui[0]['email'], "noreply@17beats.com", "No reply", "Password reset info", $emailText);
	
		return "Password reset instructions emailed to ".$ui[0]['email'];
	
	}
function createForgotPasswordForm($error=""){
	if($error!="Humanization")$ret="<br /><font color=red >$error</font><br />";
	$ret.="<form action=?d=1 method=post><table>
	<tr><td><b>Username</b></td><td><input type=text name=username /></td></tr>
	<tr><td><b>Email</b></td><td><input type=text name=email /></td></tr>";
	return $ret.'<tr><td><b>'.($error=="Humanization"?"<font color=red >Humanization</font>":"Humanization").'</b>*</td><td><img id="regcaptcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
    <input type="text" name="captcha_code" size="10" maxlength="6" /><br>
    <a href="#" onclick="document.getElementById(\'regcaptcha\').src = \'/securimage/securimage_show.php?\' + Math.random(); return false">Reload Image</a><br></td><tr><td colspan=2 align=center>
<input type=submit value="Submit" class="submit" /></td></tr></table></form>';	
}

private $favoriteAuthors=array();
function hasAuthorFavorited($author){	
	if(defined("GOTFAVA")) return in_array($author, $this->favoriteAuthors);
	global $db;
	$find=$db->fetch_all_array("select * from ".T_FAVORITE_AUTHORS." where user=".$this->uid."");
	foreach($find as $f){
		$this->favoriteAuthors[]=$f['author'];	
	}
	define("GOTFAVA", 1);
	return $this->hasAuthorFavorited($author);
	
}
private $favoriteHaiku=array();
function hasHaikuFavorited($haiku){
	if(defined("GOTFAVH")) return in_array($haiku, $this->favoriteHaiku);
	global $db;
	$find=$db->fetch_all_array("select * from ".T_FAVORITE_HAIKU." where user=".$this->uid."");
	foreach($find as $f){
		$this->favoriteHaiku[]=$f['haiku'];	
	}
	define("GOTFAVH", 1);
	return $this->hasHaikuFavorited($haiku);
	
}
}
?>