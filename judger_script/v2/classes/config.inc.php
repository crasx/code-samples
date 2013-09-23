<?php
class config extends main{
	
	function execute(){
		$body="<h1>Custom field configuration</h1>";		
		switch($_GET['do']){
			case 'editc':
				$e=$_GET['cat'];
				$ci=$this->loadCat($e);				
				if($ce===false||sizeof($ci)!=1){
					$body.='<h2>Couldn\'t find category</h2>';
					$body.=$this->createForm();
				}else{
					if(isset($_GET['done'])){
						$ud=$this->updateCat($e,$_GET['showinjudge'],$_GET['showinreport'],$_GET['showinrss']);
						if($ud===true)
							$body.="<h2>Category ".($e==0?"added":"updated")."</h2>".$this->createForm();
						elseif($ud===false)
							$body.="<h2>Error ".($e==0?"adding":"updating")." category</h2>".$this->createForm($cat['id'],$cat['name'],$cat['display'],$cat['report'],$cat['rss']);	
						else $body.=$ud;						
					}else{
						$body.=$this->createForm($cat['id'],$cat['name'],$cat['display'],$cat['report'],$cat['rss']);	
					}
				}
		break;
		case 'delc':		
				$e=$_GET['cat'];
				$ci=$this->loadCat($e);
				if($ce===false||sizeof($ci)!=1){
					$body.='<h2>Couldn\'t find category</h2>';
					$body.=$this->createForm();
				}else{
					$li=$this->requireLogin("page=config&do=delc&cat=$e", "page=config", " to delete category", "Delete");
					if($li===true){
						$db->query("delete from ".T_CUST." where id='".$_GET['delcat']."'");
						$db->query("delete from ".T_CUST_VALS." where field='".$_GET['delcat']."'");
						$body.="<h2>Category deleted</h2>";
						$body.=$this->createForm();
					}
				}
				
		break;
		default:
			$body.=$this->createForm();
		}
		return $body;
}
	
	
	function updateCat($cid, $name, $judge, $report, $rss){
		if(!$judge==0||!$judge==1) $judge=0;
		if(!$report==0||!$report==1) $report=0;
		if(!$rss==0||!$rss==1) $rss=0;
		if($name=='')return "<h2>Please enter name</h2>".$this->createForm($cid, $name, $judge, $report, $rss);
		global $config,$db;
		$ins=array('judge'=>$judge, 'report'=>$rss);
		if(RSS){
			$ins['rss']=$rss;
		}
		if($cid==0){
			return $db->query_insert(T_CUST, $ins);
		}else{
			return $db->query_update(T_CUST, $ins, 'id=$cid');
		}
		
	}
	
	function createForm($cid=0, $name='', $judge=0, $report=0, $rss=0){
		global $config;
			return "<form action='javascript:getUri(\"ajax-php.php?page=config&do=editc&cat=$editc&".($cid!=0?"done=1&":"")."\",\"editcat\", \"page\")' id='editcat'><table><tr><td>Custom field name:</td><td><input type=text value='$name' name=custname /></td></tr><tr><td>Include on judge screen</td><td><input type=checkbox name=showinjudge value=1 ".($judge==1?"checked=checked":"")." ></td></tr><tr><td>Include on report</td><td><input type=checkbox name=showinreport value=1 ".($report==1?"checked=checked":"")." ></td></tr>".(RSS?"<tr><td>Include on rss feed</td><td><input type=checkbox name=showinrss value=1 ".($rss==1?"checked=checked":"")." ></td></tr>":"")."<tr><td colspan=2 align=center ><input type=submit value=".($cid==0?"Add":"Edit")." /></td></tr></table></form>";	
	}
	
	function loadCat($c){
		global $db;
		if(!is_numeric($c))return false;
		return $db->fetch_all_array("select * from ".T_CUST." where id='".$c."'");
	}
	
	function listCats(){
		global $db, $config;
		$rws=$db->fetch_all_array("select * from ".T_CUST);
		if(sizeof($rows)>0){
			$body.="<br />
			<br /><table border=1><tr><td>&nbsp;</td><td>&nbsp;</td><td>Name</td><td>Show to judge</td><td>Show on report</td>";
			if(RSS)$body.="<td>Show on rss</td>";
			$body.="</tr>";
			foreach($rows as $r){
				$body.="<tr><td><a href='javascript:getHttp(\"ajax-php.php?page=config&editcat=".$r['id']."\",\"page\")'><img src='img/green_edit.jpg' /></a></td>
				<td><a href='javascript:getHttp(\"ajax-php.php?page=config&delcat=".$r['id']."\",\"page\")'><img src='img/red_delete.jpg' /></a></td>
				<td>".$r['name']."</td>
				<td>".($r['display']?"Yes":"No")."</td>
				<td>".($r['report']?"Yes":"No")."</td>";
				if(RSS)
				$body.="<td>".($r['rss']?"Yes":"No")."</td>";
				$body.="</tr>";
			}
			$body.="</table>";	
		}
		return $body;
		
	}
}

?>