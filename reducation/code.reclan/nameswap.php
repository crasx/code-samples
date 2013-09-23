<?php
	mysql_select_db($dbname);
	$tab=array(
			 "scores_ctf"=>array("scores", "captures", "returns", "kills", "deaths", "suicides", "betrays", "assists", "online"),
			 "scores_king"=>array("scores", "kills", "deaths", "suicides", "betrays", "assists", "online"),
			 "scores_oddball"=>array("scores", "kills", "deaths", "suicides", "betrays", "assists", "online"),
			 "scores_race"=>array( "laps", "kills", "deaths", "suicides", "betrays", "assists", "online"),
			 "scores_slayer"=>array("scores", "kills", "deaths", "suicides", "betrays", "assists", "online"));
								 
	if(isset($_GET['from'])){		$from=$_GET['from'];
		$to=$_GET['to'];
		$p=$_GET['pw'];
		if(is_numeric($from)&&is_numeric($to)&&$p=="r3cl4n"){
			
			foreach($tab as $t=>$k){
				$query="update $t t1, $t t2 set ";
				$delete="update $t set ";
				foreach($k as $c){
					$query.="t1.$c=t1.$c+t2.$c, t1.$c"."_d=t1.$c"."_d+t2.$c"."_d, t1.$c"."_w=t1.$c"."_w+t2.$c"."_w, ";	
					$delete.="$c=0, $c"."_d=0, $c"."_w=0, ";	
				}
				$query=substr($query,0,strlen($query)-2);
				$delete=substr($delete,0,strlen($delete)-2);
				$query.=" where t1.name=$to and t2.name=$from";
				$delete.=" where name=$from";
				echo $query.";<br />$delete;<br />";
			}
		}
	}
	
?>