<?php
	if(!IPHONE){
			$menu.="Competitons<br />";
			$arr=mysql_query("Select id,  name, day from ".T_COMPS." where finished!=0 order by day desc");
			$co=0;
			$day="";
			if($arr)while($a=mysql_fetch_array($arr)){
				if($day!=$a['day']){
					if($day!="")$menu.="</ul>";
					$menu.= $a['day'];
					$menu.= "<ul>";
					$day=$a['day'];
				}
				$menu.="<li><div class=menuadiv onclick='javascript:getHttp(\"public-ajax-php.php?c=".$a['id']."\", \"page\");'><div class=aholder >".dsql($a['name'])."</div></div></li>";
				$co=1;
			}
			if(!$co)$menu.="<center>No finished competitions</center>";
			$menu.="</ul>";		
	}else{//iphone
			$menu.="Competitons<br />";
			$arr=mysql_query("Select id,  name, day from ".T_COMPS." where finished!=0 order by day desc");
			$co=0;
			$day="";
			if($arr)while($a=mysql_fetch_array($arr)){
				if($day!=$a['day']){
					if($day!="")$menu.= "</ul>";
					$menu.= $a['day'];
					$menu.= "<ul>";
					$day=$a['day'];
				}
			$menu.= "<li class=mbod ><div class=aholder onclick='javascript:getHttp(\"public-ajax-php.php?c=".$a['id']."\", \"page\")' >".dsql($a['name'])."</div></li>";
				$co=1;
			}
			if(!$co)$menu.="<center>No finished competitions</center>";
				
		
	}
echo $menu;
?>