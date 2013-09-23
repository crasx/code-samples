<?php
class rss extends main{
	function execute(){		
		switch($_GET['do']){
			case "edit":
				$edit=$_GET['rss'];			
				$ri=$this->getRss($edit);
				if($ri===false||sizeof($ri)<1){
					$body.="<h2>Feed not found</h2>".$this->createForm()."<br><br>".$this->listFeeds();					
				}else{
					if(isset($_GET['done'])){
						$st.=$this->updateRss($ri,$_GET['rsstitle'],$_GET['rsstext'],$_GET['rssdate']);						
						if($st===true)$body.="<h1>Feed ".($edit==0?"created":"updated")."</h1>";
						if($st===false)$body.="<h2>Error ".($edit==0?"creating":"updating")."</h2>";
						else $body.=$st;
					}else{
						$body.=$this->createForm($edit, $ri['title'], $ri['text'], $ri['date']);
					}
				}
			break;
			case "delete":				
				$d=$_GET['rss'];			
				$ri=$this->getRss($d);
				$li=$this->requireLogin("page=rss&do=delete&rss=$d", "page=rss", " to delete feed", "Delete");
			if($li===true){
				$db->query("delete from ".T_RSS." where id='".$d."'");
				$body.="<h2>Feed deleted</h2>";
				$body.=$this->createForm()."<br><br>".$this->listFeeds();
			}
			break;
			default:
			$body.=$this->createForm()."<br><br>".$this->listFeeds();
		}	
		return $body;
		
	}
	
	function createForm($id=0, $text='', $title='', $date=''){
		$body.="<h1>Rss publishing</h1>
<form action='javascript:getUri(\"ajax-php.php?page=rss&do=edit&editrss=$edit&done=1&\",\"editcat\", \"page\")' id='editcat'>
<table><tr><td>Title:</td><td><input type=text value='$title' name=rsstitle /></td></tr>
<tr><td>Text:</td><td><textarea name=rsstext cols=20 rows=3 >$text</textarea></td></tr>
<tr><td>Date:</td><td><input type=text value='$date' name=rssdate /><br />
yyyy-mm-dd hh:mm:ss or now <a href='http://us2.php.net/strtotime#function.strtotime.examples' target='_blank'>guide</a></td></tr>
<tr><td colspan=2 align=center ><input type=submit value=".($edit==0?"Add":"Edit")." /></td></tr></table></form>
<br /><br />";

	}
	
	function getRss($c){
		global $db;
		if(!is_numeric($c))return false;
		return $db->fetch_all_array("select * from ".T_RSS." where id='".$c."'");
	}
	
	function updateRss($id, $title, $text, $date){
		global $db;
			$datetime =  strtotime($date);
			if($title=="")return "<h2>You need a title</h2>".$this->createForm($id, $title, $text, $date);
			elseif($text=="")return "<h2>You need text</h2>".$this->createForm($id, $title, $text, $date);
			elseif(!$datetime)return "<h2>Invalid date</h2>".$this->createForm($id, $title, $text, $date);
		$ins=array('title'=>$title, 'text'=>$text, 'date'=>$date);
		if($id==0){
			return $db->query_insert(T_RSS, $ins);
		}else{
			return $db->query_update(T_RSS, $ins, 'id=$id');	
		}
	}
	
	function listFeeds(){
		global $db;
		$rws=$db->fetch_all_array("select * from ".T_RSS);
		if(!$rws===false){
		$body.="<br />
		<br /><table border=1><tr><td>&nbsp;</td><td>&nbsp;</td><td>Title</td><td>Date</td></tr>";
			foreach($rws as $r){
				$body.="<tr><td><a href='javascript:getHttp(\"ajax-php.php?page=rss&do=edit&rss=".$r['id']."\",\"page\")'><img src='img/green_edit.jpg' /></a></td>
				<td><a href='javascript:getHttp(\"ajax-php.php?page=rss&do=delete&rss=".$r['id']."\",\"page\")'><img src='img/red_delete.jpg' /></a></td>
				<td>".$r['title']."</td>
				<td>".date("Y-m-d H:i:s",$r['date'])."</td>	
				</tr>";
			}
		$body.="</table>";
		}
		return $body;
	}
}
