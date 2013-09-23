<?php

require_once('cfg.php');
if(!$userInfo['judge'])die("You shouldnt be here");

if(isset($_GET['c'])){
	$c=addslashes($_GET['c']);
	if(is_numeric($c)){
		$pic="";
		if($cin=mysql_fetch_array(mysql_query("select name from ".T_COMPS." where id=$c and enabled=1 and day=curdate()"))){
			$cname=dsql($cin['name']);	
			$listppl=true;
			$body="<h1><a href='javascript:getHttp(\"ajax-php.php?page=judge&c=$c\", \"page\")' >$cname</a></h1>";
			if(isset($_GET['noshow'])){
				if(is_numeric($_GET['noshow'])){
					mysql_query("delete from ".T_SCORES." where category in (select category from ".T_CCLIST." where competition=$c)  and judge=".$userInfo['id']." and contestant=".$_GET['noshow']);
				}
			}
			if(isset($_GET['p'])){
				$p=$_GET['p'];
				if(is_numeric($p)){
					$que=mysql_query("select concat(c.name,' (',r.number,')') name, c.image, c.description, r.number from ".T_REGS." r, ".T_CONTS." c where r.contestant=c.id and r.competition=$c and  c.id=$p");
					if($que)if($r=mysql_fetch_array($que)){
						$pic=ROOT.'/'.dsql($r['image']);
						$contNum=dsql($r['number']);
						$contName=dsql($r['name']);
						$contDes=dsql($r['description']);
						$listppl=false;
					}
					if(!$listppl){
						if(isset($_GET['j'])){//submit scores
								if(isset($_GET['cat']))
								foreach($_GET['cat']  as $cid=>$score){
									if(is_numeric($score)){
									$check=mysql_query("select * from ".T_CATS." c, ".T_CCLIST." l  where l.category=$cid and competition=$c and c.id=l.category and c.min<=$score and c.max>=$score");
									if($check)if(mysql_num_rows($check)>0){
										
										mysql_query("insert into ".T_SCORES."(competition, category, judge, contestant, score) values($c, $cid, ".$userInfo['id'].", $p, $score) on duplicate key update score=$score");
										$save=true;
									}
									}
								}
					   }
				$listc=mysql_query("select a.id, a.name, a.min, a.max, ifnull(s.score, a.min) score from ".T_CCLIST." l, ".T_CATS." a left join ".T_SCORES." s on( s.contestant=$p and s.judge=".$userInfo['id']. " and s.category=a.id and s.competition=$c ) where l.competition=$c and l.category=a.id" );
				$que=mysql_query("select concat(p.name,'(', r.number,')') 'name', p.id from ".T_REGS." r, ".T_CONTS." p where r.number<$contNum and r.contestant=p.id and r.competition=$c order by r.number desc limit 1");
				if($que)if($r=mysql_fetch_array($que)){
					$body.="<a href='javascript:getHttp(\"ajax-php.php?page=judge&c=$c&p=".$r['id']."\", \"page\");waitLoadScroll();' >".dsql($r['name'])."</a> ";
				}
				$body.="<font size=+3 >$contName </font> ";
				$que=mysql_query("select concat(p.name,'(', r.number,')') 'name',  p.id from ".T_REGS." r, ".T_CONTS." p where r.number>$contNum and r.contestant=p.id and r.competition=$c order by r.number limit 1");
				$nextid=0;
				if($que)if($r=mysql_fetch_array($que)){
				$body.=" <a href='javascript:getHttp(\"ajax-php.php?page=judge&c=$c&p=".$r['id']."\", \"page\");waitLoadScroll();' >".dsql($r['name'])."</a>";
				$nextid=$r['id'];
				}
				$body.="<br /><form action='javascript:getUri(\"ajax-php.php?page=judge&c=$c&p=$p&j=1&\",\"jform\", \"page\");waitLoadScroll();' id=jform >
<table><tr><td valign=top >";
				if($save)$body.=" -- Last save: ".date("g:i:s");
				if($listc)while($tcat=mysql_fetch_array($listc)){
						$body.="<h3 style='float:left;'>".dsql($tcat['name'])."</h3>".getslider("cat".$tcat['id'], $tcat['min'], $tcat['max'], $tcat['score'],"cat[".$tcat['id']."]");
							
				}
				$body.="<br /><input type=button value='No show' onclick='javascript:getHttpWait(\"ajax-php.php?page=judge&c=$c&noshow=$p&p=$nextid\", \"page\", 1);' ><input type=submit value='Save' >".($nextid!=0?"<input type=button value='Save and next' onclick='javascript:getUri(\"ajax-php.php?page=judge&c=$c&p=$p&j=1&\",\"jform\", \"page\");getHttpWait(\"ajax-php.php?page=judge&c=$c&p=$nextid\", \"page\", 1);' >":"")."</td><td>".($pic==""?"":"<img src='$pic' />")."<br />
<br /><table border=1><tr><td>Description: </td><td>$contDes</td></tr>";
				$quei=mysql_query("select c.name, v.val from ".T_CUST." c, ".T_CUST_VALS." v where c.id=v.field and c.display=1 and v.contestant='$p'");
				if($quei)while($rx=mysql_fetch_array($quei)){
					$body.= "<tr><td>".dsql($rx['name'])."</td><td>".dsql($rx['val'])."</td></tr>";
				}
				$body.="</table></td></tr></table></form>";
				}
			}
			}
			if($listppl){//list peopls
			$ppl=mysql_query("select c.name, r.number, c.id from ".T_CONTS. " c, ".T_REGS." r where r.competition=$c and r.contestant=c.id order by r.number");
			$body.="<h2>Contestants</h2><table border=1><tr><td>Position</td><td>Name</td><td>Scored</td></tr>";
			if($ppl)while($p=mysql_fetch_array($ppl)){
				$sco=false;
				$que=mysql_query(" SELECT count( s.category ) AS sco, (SELECT count( c.category ) AS cat FROM ".T_CCLIST." c WHERE c.competition =$c) AS cat FROM ".T_SCORES." s WHERE s.contestant =".$p['id']." AND s.judge =".$userInfo['id']." AND s.competition=$c");
				if($que)while($r=mysql_fetch_array($que)){
					$sco=$r['sco']==$r['cat'];
				}
				$body.= "<td>".$p['number']."</td><td><a href='javascript:getHttp(\"ajax-php.php?page=judge&c=$c&p=".$p['id']."\", \"page\");waitLoadScroll();' >".dsql($p['name'])."</a></td><td style='background-color:#".($sco?"00ff00":"ff0000")."'>&nbsp</td></tr>";
				
			}
			$body.= "</table>";
			
			}
		}else $err="invalid competition";
	}else $err="invalid competition";
}

function getslider($name, $min, $max, $score,$htmln){
return '<br clear=all /><table><tr><td>'.$min.'</td><td>
<div class="carpe_horizontal_slider_track">
    <div class="carpe_slider_slit">&nbsp;</div>
    <div class="carpe_slider"
        id="'.$name.'s"
        orientation="horizontal"
        distance="300"
        display="'.$name.'d"
        style="left: '.(300/($max-$min))*($score-$min).'px;" >&nbsp;</div>
</div>
<div class="carpe_slider_display_holder" >
    <input class="carpe_slider_display"
        id="'.$name.'d"
        name="'.$htmln.'"
        type="text"
        from="'.$min.'"
        to="'.$max.'"
        valuecount="'.($max-$min+1).'"
        value="'.$score.'"
        typelock="off" /></div><br clear=all /></td><td>'.$max.'</td></tr></table>';
}

?>