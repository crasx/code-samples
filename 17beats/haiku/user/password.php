<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';
ob_start();

	if($user->isValid())
	 header("location: /");
		

echo "<div id=register class='white round full'  ><div class=pad >";
echo "<h2>Forgot password</h2>";
if(isset($_GET['user'])){
	if(!isset($_GET['d'])){
		$vpr=$user->validatePasswordReset();
		if($vpr===false){
			header("location: /");
		}else echo $vpr;
	}else{
		$out=$user->validatePasswordInputReset();
		if($out===true){
			echo "Password changed. You can now login with the new password.";
		}elseif($out===false){
			$vpr=$user->validatePasswordReset();
			if($vpr===false)header("location: /");
			else echo $vpr;
		}else echo $user->validatePasswordReset($out);
	}
	
}else
		if(!isset($_GET['d']))
			echo "Fill in the following information and we will send an email with instructions on how to reset your password.<br>
".$user->createForgotPasswordForm();
		else{
			$r=$user->validPasswordInput();
			if(is_array($r)){
				echo $user->resetPasswordEmail($r);	
			}else 
			echo "Fill in the following information and we will send an email with instructions on how to reset your password.<br>
".$user->createForgotPasswordForm($r);
		}

echo "</div></div>";

$smarty->assign("body", ob_get_contents());
ob_clean();
$smarty->assign("title", " | Forgot password");
$smarty->display('std.tpl');

?>
