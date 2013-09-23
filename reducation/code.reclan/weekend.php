<?php
	$con=mysql_connect("127.0.0.1",$dbuser,$dbpass);
	mysql_select_db($dbname);
$td=getdate();
print_r($td);
echo date('e P T Z I');
if($td['wday']==6){
echo "A";
	mysql_query(" insert into history_2 (SELECT now(), c.name, c.w, s.name, s.w, r.name, r.w, o.name, o.w, k.name, k.w
FROM (

SELECT name, ctfw w
FROM scores_2
ORDER BY ctfw DESC
LIMIT 1
)c, (

SELECT name, slayerw w
FROM scores_2
ORDER BY slayerw DESC
LIMIT 1
)s, (

SELECT name, kingw w
FROM scores_2
ORDER BY kingw DESC
LIMIT 1
)k, (

SELECT name, racew w
FROM scores_2
ORDER BY racew DESC
LIMIT 1
)r, (

SELECT name, oddballw w
FROM scores_2
ORDER BY oddballw DESC
LIMIT 1
)o )");
	$message.="
insert... ".(mysql_error()==""?"OK":mysql_error());
//===================================================================

mysql_query("update scores_2 set ctfw=0, slayerw=0, kingw=0, oddballw=0, racew=0, onlineW=0");
		$message.="
Reset weekly ... ".(mysql_error()==""?"OK":mysql_error());

	
mail("matthew@crasxit.net", "weekly stats updated", $message, "FROM: weekend@reclan.com");

}
	
?>