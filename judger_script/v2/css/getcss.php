<?php
if(!$NOCFG)require("../adm/cfg.php");
$styles=array();
$que=mysql_query("select * from ".T_STYLES);
if($que)while($r=mysql_fetch_array($que)){
	$styles[$r['style']][$r['name']]['file']=$r['file'];
	$styles[$r['style']][$r['name']]['color']=$r['color'];
	$styles[$r['style']][$r['name']]['text']=$r['text'];
}
?>