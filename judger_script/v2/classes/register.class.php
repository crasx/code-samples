<?php
class register extends main{
	
function execute(){
		global $db, $customE;
		$customE=array();
		$getC=$db->fetch_all_array("select * from ".T_CUST);
		if(sizeof($getC>0))
			foreach($getC as $rw){
				$customE[$rw['id']]=array($rw['name'],'', $rw['required']);				
			}
		$custom=$customE;
		///////////////////////////
	switch($_GET['do']){
		case "ec":
		$id=$_GET['id'];
		$usr=$this->getContestant($id);				
		if($this->getRecord($usr, $uinf)||$id==""||$id==0){
			if(isset($_GET['done'])){
				if(isset($_POST['rcust'])){
					foreach($_POST['rcust'] as $k=>$v){
						if(in_array($k, array_keys($custom)))$custom[$k][1]=$v;
					}
				}

			$body.=$this->updateContestant($id, $_GET['rname'], $custom, $_POST['rcomps']);
			}elseif($id==""||$id==0)
				$body.=$this->contestantForm();			
			else
				$body.=$this->loadForm("", $id, $custom);
		}else
			$body.=$this->contestantForm("User doesn't exist!");


	break;
	//////////////////////////////////////////////////////////////////////////
	case "dup":
		$id=$_GET['id'];
		$uif=$this->getContestant($id);
		if(is_array($uif)&&sizeof($uif)==1){
			if(requireLogin("page=reg&do=dup&id=".$cont, "page=reg&do=ec&id=$id", " to duplicate ".$uif['name']. "", "Duplicate")){
				$db->query("insert into ".T_CONTS."(name,description,image) select name, description, ''  from ".T_CONTS."  where id='".$id."'");
				$uid=mysql_insert_id();
				$db->query("insert into ".T_CUST_VALS." select ".$id." 'contestant', field, val from ".T_CUST_VALS."  where contestant='".$id."'");
				
				$body.=$this->loadForm("Contestant duplicated- editing duplicate", $uid, $custom);
			}
		}else $body.=$this->contestantForm("No such contestant");
	break;
	///////////////////////////////////////////////////////////////////////
	case "delf":
		$id=$_GET['id'];
		$uif=$this->getContestant($id);
		if(is_array($uif)&&sizeof($uif)==1){
			if(unlink($uif['image'])){
				$db->query("update ".T_CONTS." set image='' where id='".$id."'");
				$body=loadForm("Picture deleted", $id);
			}else{
				$body=loadForm( "An error occurred", $id);
			}
		}
	
	break;
	}
	return $body;
}//execute

	function auth(){
		global $user;
		return $user->isValid()&&$user->isRegister();
	}
	
function isValidCompetition($id){
global $db;

if(!is_numeric($id))return false;
$v=$db->fetch_all_array("select c.* from ".T_COMPS." c left join ".T_COMP_GROUPS." g on g.id=c.group where c.id={$id} and c.registration=1 or (g.register=1 and c.registration=-1) order by c.name ");
return is_array($v)&&sizeof($v);
	
}
	
function getContestantInfo($id){
global $db;
if(!is_numeric($id))return false;
return $db->fetch_all_array("select * from ".T_CONTS." where id=".$id);
}

/////////////////////////////////////////
////////////////////////////////////////////
////////////////////////////////////////////
function contestantForm(){//need to code in regs
	global $db;		
	$custom=array();
	$getC=$db->fetch_all_array("select * from ".T_CUST);
	if(sizeof($getC>0))
		foreach($getC as $rw){
			$custom[$rw['id']]=array($rw['name'],'', $rw['required']);				
		}

	$retur="
	<table cellpadding='20px;' style=\"width:100%\" ><tr><td valign=top >
	<h2>Add contestants</h2>
	<form id=contestantForm action='javascript:submitForm(\"contestantForm\", \"competitionList\", function(data){createContestantCallback(data);});'>
	<table><tr><td>
Name</td><td><textarea name=rname rows=1 cols=20 ></textarea></td></tr>";
if(is_array($custom))
foreach($custom as $k=>$v){
	$retur.="<tr><td>{$v[0]}</td><td><textarea name='rcust[$k]' rows=1 cols=20 >{$v[1]}</textarea></td></tr>";
}
$retur.="<tr id=submittr ><td colspan=2 align=center ></td></tr><tr id=uploadtr ><td colspan=2 ></td></tr><tr id=loadertr align=center style=display:none; ><td colspan=2 ><br /><br /><img src='/images/loader.gif' /></td></tr></table>
</td><td valign=top >
<h2>Competitions:</h2><em>Number shown is contestant number</em><table id=competitionList class=sorted border=1 ><thead><tr><th>&nbsp;</th><th>Name</td><th>Num</th></tr></thead><tbody>";

$getc=$db->fetch_all_array("select c.id, c.name, ifnull(max(r.number),0) num from ".T_COMPS." c left join ".T_COMP_GROUPS." g on c.`group`=g.id left join ".T_REGS." r on r.competition=c.id where c.registration=1 or (g.register=1 and c.registration=-1) group by c.id");
foreach($getc as $c){
	$retur.= "<tr maxId=".($c['num']+1)." id=competitionList".$c['id']."><td ><input type=checkbox name=rcomps[] value=".$c['id']." ></td><td >".$c['name']."</td><td></td></tr>";
}

$retur.="</tbody></table>
</td><td valign=top >".$this->contestantList()."</td></tr><tr><td id=imgholder colspan=3 ></td></tr></table>
";
return $retur;
}
	function contestantList(){
		global $db;
		$body.= "<h2>Current registrations</h2><table border=1 id=contestantList class=sorted ><thead><tr><th>Added</th><th>Name</th></tr></thead><tbody>";
		$cont=$db->fetch_all_array("select p.id, p.name 'per', group_concat(concat('\"',r.competition,'\":',r.number) separator ', ') regs from ".T_CONTS." p left join ".T_REGS." r on (p.id=r.contestant and r.enabled=true) group by p.id");
		
			foreach($cont as $c){
				$ccust=$db->fetch_all_array("select field, val from ".T_CUST_VALS." where contestant=".$c['id']);
				$car=array();
				foreach($ccust as $cval){
					$car[$cval['field']]=htmlentities($cval['val']);
				}
				$comps=$db->fetch_all_array("select r.number, comp.name from ".T_REGS." r, ".T_COMPS." comp where r.competition=comp.id and r.enabled=1 and r.contestant=".$c['id']);
				$compsstr="";
				foreach($comps as $cl){
					$compsstr.=$cl['name'].": ".$cl['number']."<br />";	
				}
				 $imE=file_exists(UPLOAD_DIR."/".$c['id'])?1:0;
				$body.= "<tr id=contestantList".$c['id']." competitions='{".$c['regs']."}' custom='".addslashes(json_encode($car))."' image=$imE ><td>".$c['id']."</td><td><a href='javascript:editContestant(".$c['id'].")'>".$c['per']."</a><br />{$compsstr}<em><a href='javascript:copyContestant(".$c['id'].")'>Copy</a></em></td></tr>";
			}		
		$body.="</tbody></table>";
		return $body;
	}
		
function deleteContestant($d){
		global $db;		
		$i=$this->getContestantInfo($d);
		if(!$i||sizeof($i)==0)return false;
		global $retMax;
		$myCS=$db->fetch_all_array("select r.competition from ".T_REGS." r where r.contestant=$d");
		$myC=array();
		foreach($myCS as $m){$myC[]=$m['competition'];}
			
		$db->query("delete from ".T_REGS." where contestant=$d");
		$db->query("delete from ".T_CHECKS." where contestant=$d");
		$db->query("delete from ".T_CONTS." where id=$d"); 
		$db->query("delete from ".T_SCORES." where contestant=$d");
		if(sizeof($myC)==0)return true;
		$max=$db->fetch_all_array("select c.id, ifnull(max(r.number),0) num from ".T_COMPS." c left join ".T_REGS." r on c.id=r.competition where c.id in(".implode(",",$myC).") group by c.id");
		foreach($max as $m){
		$retMax["maxIds"][$m['id']]=$m['num']+1;
		}
		return true;		
	}	
	
	
function deleteContestantImage($d){
		global $db;		
		$i=$this->getContestantInfo($d);
		if(!$i||sizeof($i)==0)return false;
		if(file_exists(UPLOAD_DIR."/".$d))unlink(UPLOAD_DIR."/".$d);
		else return false;	
		return true;	
	}	
	
	
	function editContestant($cid=0){		
	global $db;		
		$i=$this->getContestantInfo($cid);
		if($cid!=0&&(!$i||sizeof($i)==0))return false;
		
		$custom=array();
		$getC=$db->fetch_all_array("select * from ".T_CUST);
		if(sizeof($getC>0))
			foreach($getC as $rw){
				$custom[$rw['id']]=array($rw['name'],'', $rw['required']);				
			}
		
		if(isset($_POST['rcust'])){
			foreach($_POST['rcust'] as $k=>$v){
				if(in_array($k, array_keys($custom)))$custom[$k][1]=$v;
			}
		}
			
		foreach($custom as $k=>$v){
			if($v[1]==''&&$v[2]==true) return "rcust[".$k."]";
		}
		$name=$_POST['rname'];
		if($name=='')return "rname";

		 $vals=array("name"=>$name);
		if($cid==0){
			$vals['registered']=time();
			$cid=$db->query_insert(T_CONTS, $vals);
		}else{
			$db->query_update(T_CONTS, $vals,"id='$cid'");
		}
		
		
	if(isset($_POST['rimg'])&& !empty($_POST['rimg'])){
		$of=UPLOAD_DIR."/tmp/".$_POST['rimg'];
		$nf=UPLOAD_DIR."/".$cid;
		if(file_exists($of)){
			if(file_exists($nf))unlink($nf);
			rename($of, $nf);	
		}
	}	
	global $retMax;
	$retMax=array();
	$retMax['custom']=array();	
	foreach($custom as $k=>$v){
		$ins=array("contestant"=>$cid, "field"=>$k, "val"=>$v[1]);
		$db->query_insert(T_CUST_VALS, $ins, " on duplicate key update val='".$v[1]."'");
		$retMax['custom'][$k]=$v[1];
		
	}
	$retMax["maxIds"]=array();
	 $db->query("update ".T_REGS." set enabled=0 where contestant=$cid");
	 if(isset($_POST['rcomps']))
	 foreach($_POST['rcomps'] as $v){
		 if($this->isValidCompetition($v)){
	 			$db->query("insert into ".T_REGS." (contestant, competition, number, enabled)( select '$cid' as contestant, '$v' as competition, ifnull(max(number)+1,1) number, 1 enabled from ".T_REGS." where competition='$v') on duplicate key update enabled=1");		
			$max=$db->fetch_all_array("select ifnull(max(r.number),0) num from ".T_REGS." r where competition='$v'");
			$retMax["maxIds"][$v]=$max[0]['num']+1;
		 }
	 }
	 $retMax['regData']=array();
		$cont=$db->fetch_all_array("select r.competition c, r.number n from ".T_REGS." r where r.contestant=$cid and r.enabled=true");
		foreach($cont as $c){
			$retMax['regData'][$c['c']]=$c['n'];
		}
			$comps=$db->fetch_all_array("select r.number, comp.name from ".T_REGS." r, ".T_COMPS." comp where r.competition=comp.id and r.enabled=1 and r.contestant=".$cid);
				$compsstr="";
				foreach($comps as $cl){
					$compsstr.=$cl['name'].": ".$cl['number']."<br />";	
				}
			$retMax['compList']=$compsstr;
return $cid;	
				
}
}

?>