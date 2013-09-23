<?php

define("DBU", "C:\\root\\judger.clients\\base\\upgrade\\sql.sql");
define("SCRIPTU", "C:\\root\\judger.clients\\base\\upgrade\\c.htaccess");

global $config;
$dbversion=$config->getInfo("dbversion", 0);
$scrversion=$config->getInfo("scriptversion", 0);

$uc=1;
while(file_exists(DBU.($uc+$dbversion))){
	$mysql="C:\\xampp\\mysql\\bin\\mysql.exe";
	$sql=file_get_contents(DBU.($uc+$dbversion));
	$temp_file = tempnam(sys_get_temp_dir(), 'sql');
	$sql="
	use `cj_".URI."`;
	".$sql;
	$fp = fopen($temp_file, 'w');
	fwrite($fp, $sql);
	fclose($fp);
	system($mysql." -uroot -phimal952 < ".$temp_file);
	$uc++;
	
}
$uc--;
$db->query("update ".T_CFG." set value=".($uc+$dbversion)." where `key`='dbversion'");
$uc=1;
while(file_exists(SCRIPTU.($uc+$scrversion))){
	copy(SCRIPTU.($uc+$scrversion), CPATH."\\.htaccess");
	$uc++;
}
$uc--;
$db->query("update ".T_CFG." set value=".($uc+$scrversion)." where `key`='scriptversion'");

?>