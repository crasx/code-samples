<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';
ob_start();


echo "<div id=register class='white round full'  ><div class=pad >";
	if($user->isValid()&&!$user->isEmailVerified()){
		echo "<h3>Submit haiku</h3><br />
Please confirm email.<br />
What if we need your contact?<br />
Resend email <a href='/user/resend.php'>here</a>";
	}else
		if(!isset($_GET['d']))
		echo "<h3>Submit haiku</h3><br>
".$submit->createForm();
		else{
			$r=$submit->validInput();
			if($r===true){
				echo $submit->create();	
			}else echo "<h3>Submit haiku</h3>".$submit->createForm($r);
		}

echo "</div></div>";

$smarty->assign("body", ob_get_contents());
ob_clean();
$smarty->assign("category", -2);
$smarty->assign("title", " | Submit");
$smarty->display('std.tpl');

?>
