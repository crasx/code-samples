<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';
ob_start();

	if($user->isValid())
	 header("location: /");
		

echo "<div id=register class='white round full'  ><div class=pad >";
		if(!isset($_GET['d']))
		echo "<h3>Create account</h3>".$user->createRegisterForm();
		else{
			$r=$user->validInput();
			if($r===true){
				echo $user->create();	
			}else echo "<h3>Create account</h3>".$user->createRegisterForm($r);
		}

echo "</div></div>";

$smarty->assign("body", ob_get_contents());
ob_clean();
$smarty->assign("title", " | Register");
$smarty->display('std.tpl');

?>
