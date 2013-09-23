<?php
require_once("adm/cfg.php");
if(isset( $_GET['c'])){
	$v=addslashes($_GET['c']);
	if(is_numeric($v)){
		
				if($nam=mysql_fetch_array(mysql_query("select name, description from ".T_COMPS." where id=$v and (day<=curdate() or enabled=0)"))){
					if(IPHONE)$body.="<h2><a href='javascript:getHttp(\"public-ajax-php.php?page=mc&do=resv\", \"page\")'>Back to competitions</a></h2>";		
					$body.="<div class=mtop onclick='javascript:getHttp(\"public-ajax-php.php?page=mc&do=resv&view=$v\", \"page\")'><h2>".dsql($nam['name'])."</h2></div>".dsql($nam['description']);		
						
		$winnerids=mysql_query("select r.number, p.name, concat(round((sum(s.score)/(select sum(c.max) from ".T_CATS." c where c.id in (select category from ".T_CCLIST." where competition=$v ))/(select count(distinct(judge)) from ".T_SCORES." where competition=$v and contestant=p.id))*100,2),'%') 'score' from ".T_CONTS." p, ".T_REGS." r, ".T_SCORES." s where s.contestant=p.id and s.competition='$v' and r.competition=$v and r.contestant=s.contestant group by s.contestant order by 3 desc limit 3");
		$body.="<table border=1><tr><th>Winners</th></tr>";
			$w=1;
			if($winnerids)while($war=mysql_fetch_array($winnerids)){
				$body.="<tr><td>$w. <a href='#cont".$war['number']."' >".$war['name']."</a></td></tr>";
				$w++;
			}
			$body.="</table>";
					$dov=true;
					$quelp=mysql_query("select p.id, p.name, r.number from ".T_CONTS." p, ".T_REGS." r where p.id=r.contestant and r.competition=$v order by 3");
					echo mysql_error();
					$body.="<table border=1 >";
					if($quelp)while($rlp=mysql_fetch_array($quelp)){
						$body.="<tr><td><a name='cont".$rlp['number']."' ></a><h2>".$rlp['name']."</h2>";
						$body.="Contestant number: ".$rlp['number']."<br />";
						$p++;				
						$inf=$rlp['id'];
						$que=mysql_query("select  c.name, c.image, r.number from ".T_CONTS." c, ".T_REGS." r where c.id=r.contestant and r.competition=$v and c.id=".$inf);
						if($que)if($r=mysql_fetch_array($que)){
							$body.=$r['image']==""?"-no image-":"<img src='".$r['image']."' class=hund />";
							$body.="<br /><table border=1>";
							$quei=mysql_query("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v where c.id=v.field  and v.contestant='".$inf."' and c.rss=1");
							if($quei)while($rx=mysql_fetch_array($quei)){
								$body.= "<tr><td>".dsql($rx['name'])."</td><td>".$rx['val']."</td></tr>";
							}
							$body.="</table>";
							$dov=1;
						}
						$body.="</td></tr>";
						}else $body.="Person doesn't exist";
					}
						

	}
}else echo "<h1>Welcome to Paradise</h1><h2>Use the menu to your left to begin.</h2>";


echo $body;
?>