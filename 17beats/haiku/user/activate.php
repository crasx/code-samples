<?php
require $_SERVER['DOCUMENT_ROOT'].'/../haiku_script/classes/config.php';

$usr=$_GET['user'];
$key=$_GET['act'];

ob_start();
echo "<div id=register class='white round full'  ><div class=pad >";


switch($user->activateEmail($usr, $key)){
case 1:
echo "<h2>Account activated!</h2>
Thank you for verifying your email, you will soon recieve an email with more detailed information on some of the cool features at 17beats.com!<br>
You can now <a href='/user/submit.php'>submit haiku or save drafts</a> and comment on other haiku.<br>
<br>
Have fun &hearts;";
break;
case 0:
echo "<h2>Error activating account</h2>
Oh no! Looks like there was an error with the key you provided. I hope you aren't trying to brute force your key because you'll be here a while and I may run out of bandwidth.";
break;
case -1:
echo "<h2>Account already activated!</h2>
It looks like this account was already activated! I'm not sure what you're doing here... :)";
}


echo "</div></div>";

$smarty->assign("body", ob_get_contents());
ob_clean();
$smarty->assign("category", -2);
$smarty->assign("title", " | Activate");
$smarty->display('std.tpl');

?>
