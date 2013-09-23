<?php
class user{

private $user='';
private $password='';
private $uid=-1;
private $contact='';
private $name='';
private $admin=false;
private $mc=false;
private $register=false;
private $judge=false;
private $attempt=false;
private $captcha=false;
private $requireCaptcha=false;


function attemptedLogin(){
	return $this->attempt;
}
function invalidCaptcha(){
	return $this->captcha;
}
function requireCaptcha(){
	return $this->requireCaptcha;
}

function isValid(){
	return $this->uid!=-1;	
}
function isAdmin(){
	return $this->admin;
}
function isJudge(){
	return $this->judge;
}
function isRegister(){
	return $this->register;
}
function isMc(){
	return $this->mc;
}
function getName(){
	return $this->name;	
}
function getId(){
	return 	$this->uid;
}

function getPassword(){
	return $this->password;	
}
	
	
function doLogin(){
	session_start();
	if(isset($_GET['logout'])){	
			setcookie("persist", "", 1, "/");
			setcookie("login", "", 1, "/");
			session_destroy();
			header("Location: ".BASE);
		return;
	}
	$this->requireCaptcha=isset($_SESSION['requireCaptcha']);
	$thisA=isset($_SESSION['attempt'])||isset($_SESSION['requireCaptcha']);
	unset($_SESSION['attempt']);
	$posted=false;
	if(isset($_POST['loginusername'])){
			$this->requireCaptcha=1;
			$_SESSION['requireCaptcha']=1;
			$_SESSION['attempt']=1;
			if(isset($_POST['loginremember'])) $_SESSION['persist_login']=1;
			$this->user= $_POST['loginusername'];
			$this->password=md5($_POST['loginpassword']);
			$posted=true;
	}else{
		if(isset($_SESSION['login'])&&!empty($_SESSION['login'])&&isset($_SESSION['persist'])){
			$this->user=$_SESSION['login'];	
			$this->password=$_SESSION['persist'];
			
		}elseif(isset($_COOKIE['login'])&&isset($_COOKIE['persist'])){
			$this->user=$_COOKIE['login'];	
			$this->password=$this->XORDecrypt($_COOKIE['persist'], IP);
		}
	}
	if($this->user=="")	return;
	
	
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
		if(isset($_SESSION['persist_login'])){
			setcookie("login", $this->user, time()+60*60*24*14, "/");
			setcookie("persist", $this->XOREncrypt($this->password,IP), time()+60*60*24*14, "/");
		}
			unset($_SESSION['requireCaptcha']);
			unset($_SESSION['attempt']);
		
			$_SESSION['login']=$this->user;	
			$_SESSION['persist']=$this->password;
			

		unset($_SESSION['persist_login']);
		
		$this->uid=$usr[0]['id'];
		$this->contact=$usr[0]['contact'];
		$this->name=$usr[0]['name'];
		$this->password=$usr[0]['password'];
		$this->mc=$usr[0]['mc'];
		$this->judge=$usr[0]['judge'];
		$this->register=$usr[0]['register'];
		$this->admin=$usr[0]['admin'];
	}

}

/**
 * XOR encrypts a given string with a given key phrase.
 *
 * @param     string    $InputString    Input string
 * @param     string    $KeyPhrase      Key phrase
 * @return    string    Encrypted string    
 */    
function XOREncryption($InputString, $KeyPhrase){
     $KeyPhraseLength = strlen($KeyPhrase);
     // Loop trough input string
    for ($i = 0; $i < strlen($InputString); $i++){
         // Get key phrase character position
        $rPos = $i % $KeyPhraseLength;
         // Magic happens here:
        $r = ord($InputString[$i]) ^ ord($KeyPhrase[$rPos]);
         // Replace characters
        $InputString[$i] = chr($r);
    }
     return $InputString;
}
 
// Helper functions, using base64 to
// create readable encrypted texts:
 
function XOREncrypt($InputString, $KeyPhrase){
    $InputString = $this->XOREncryption($InputString, $KeyPhrase);
    $InputString = base64_encode($InputString);
    return $InputString;
}
 
function XORDecrypt($InputString, $KeyPhrase){
    $InputString = base64_decode($InputString);
    $InputString = $this->XOREncryption($InputString, $KeyPhrase);
    return $InputString;
}
}
?>