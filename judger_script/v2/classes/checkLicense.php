<?php

if(!$config->competitionEnabled()){
	$smarty->display('lockout.tpl');
	exit;
}

?>