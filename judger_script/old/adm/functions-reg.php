<?php
/*
*/
//custom fields
require_once('cfg.php');
if(!$userInfo['register'])die("You shouldnt be here");
if(isset($_GET['do'])){	
		$customE=array();
		$getC=mysql_query("select * from ".T_CUST);
		while($rw=mysql_fetch_array($getC)){
			$customE[$rw['id']]=array($rw['name'],'');	
		}
		$custom=$customE;
		$id=isset($_GET['id'])?addslashes($_GET['id']): 0;
	if($_GET['do']=="ec"){
		if(isset($_GET['done'])){
		$oid=$id;
		$name=sqld($_POST['rname']);
		$descr=sqld($_POST['rdes']);
		if(isset($_POST['rcust'])){
			foreach($_POST['rcust'] as $k=>$v){
				if(in_array($k, array_keys($custom)))$custom[$k][1]=$v;
			}
		}
		$comp=array();
		if(!isset($_POST['rcomps']))
		$comp=array();
		else if(!is_array($_POST['rcomps']))$comp[]=$_POST['rcomps'];
		else $comp=$_POST['rcomps'];
		if(!is_numeric($id)){
			$regErr="User doesnt exist";
		}else
		if($id!=0){
			$trr=mysql_query("select * from ".T_CONTS." where id='$id'");
			if(!$trr){
			$regErr="User doesn't exist!";			
			}else{
				$uarr=mysql_fetch_array($trr);
				$userEdit=array('image'=>$uarr['image'], 'name'=>$uarr['name'],'description'=>$uarr['description']);
			}
		}else $userEdit=array('image'=>'', 'name'=>$name,'description'=>$descr);
		
		if(!isset($regErr)){
			if($descr=="")$regErr="User must have description";	
			else $userEdit['description']=$descr;
			if($name=="")$regErr="User must have name";	
			else $userEdit['name']=$name;
				///===========================file=====================
			if($id!=0||($id==0&&$regErr=="")){
				if(isset($_FILES['rimg'])&& !empty($_FILES['rimg']['name'])){
					if(!eregi('image/', $_FILES['rimg']['type'])) {
						$fileErr="Please upload images only";
					} else
						if($_FILES['rimg']['error']!=0){
							$fileErr=file_upload_error_message($_FILES['rimg']['error']);
						}else{		
							if($userEdit['image']!="")$filePath=$userEdit['image'];
							else{
							$filePath=addslashes(UPLOAD_DIR.time().".".end(explode(".", strtolower($_FILES['rimg']['name']))));
							}

							if(!move_uploaded_file($_FILES['rimg']['tmp_name'], $filePath)) {
								$fileErr= "There was an error uploading the file, please try again!";
							}else $userEdit['image']=$filePath;
						}
							
				}	
			
				//==========================contestant update=======================			
				if($id==0){
				if(!mysql_query("insert into ".T_CONTS." (name, description, image,registered) values('".$userEdit['name']."','".$userEdit['description']."','".$userEdit['image']."', ".time()." )")){
				$regErr="Error adding to database...".mysql_error();
				}else{
				$id=mysql_insert_id();
				}
				}else{
				if(!mysql_query("update ".T_CONTS." set name='".$userEdit['name']."', description='".$userEdit['description']."', image='".$userEdit['image']."' where id='$id'")){
						$regErr="Error updating contestant in database... ".mysql_error(); 
					}
				}
			
				//================================custom===============================
				
				foreach($custom as $k=>$v){
					if(!mysql_query("insert into ".T_CUST_VALS." (contestant, field, val) values('$id', '$k','".$v[1]."') on duplicate key update val='{$v[1]}'")){
						$fileErr="Error updating custom fields<br />".mysql_error();
					}	
				}
				//=======================================competitions==========================================
					if($id!=0){
						$del="";$new="";
						 $num=mysql_query("select * from ".T_REGS." where contestant='$id'");
						 $old=array();
						 if($num)while($n=mysql_fetch_array($num)){
							if(!in_array($n['competition'], $comp)){
								$del.=$n['competition'].",";
							}else{
								unset($comp[array_search($n['competition'],$comp)]);
							}
						 }
					 $del=substr($del,0,-1);
					 mysql_query("delete from ".T_REGS." where contestant=$id and competition in($del)");
				
					 foreach($comp as $k=>$v){
							if(!mysql_query("insert into ".T_REGS." (contestant, competition, number) select '$id' as contestant, '$v' as competition, ifnull(max(number)+1,1) from ".T_REGS." where competition='$v'")){
								$fileErr="Error inserting into registration ".mysql_error();
							}				
							
						}
					}
				}
			}//=====================================end competitions================================
			if(!isset($fileErr)){
			if($oid==0){
			$body=registerForm("User sucessfully added to database");
			}else{
			$body=registerForm("User sucessfully edited");			
			}
			}else{
			 $body=registerForm($fileErr, $oid, dsql($userEdit['name']), dsql($userEdit['description']), $custom, dsql($userEdit['image']));
			 }
		$event=2;		
		}else{//not done
		if($id==0)		
		$fnd=false;
		if($lu=mysql_query("select * from ".T_CONTS." where id='$id'")){
			if($ar=mysql_fetch_array($lu)){
			$fnd=true;
			$fcus=mysql_query("select * from ".T_CUST_VALS." where contestant='$id'");
			while($car=mysql_fetch_array($fcus)){
				$custom[$car['field']][1]=dsql($car['val']);
			}
			$body="".registerForm("", $id, dsql($ar['name']), dsql($ar['description']), $custom, dsql($ar['image']));
			}
		}
		$event=1;	
		if(!$fnd)
		$body=registerForm('',0,'','',$custom);			
		}
		
	}else if($_GET['do']=="dup"){
	$event=1;
		if(isset($_GET['id'])){
			if($_GET['id']!=0){

				$q=mysql_query("select * from ".T_CONTS." where id='".addslashes($_GET['id'])."'");
				if($ro=mysql_fetch_array($q)){				
				if(mysql_query("insert into ".T_CONTS."(name,description,image) select name, description, ''  from ".T_CONTS."  where id='".addslashes($_GET['id'])."'")){
				$id=mysql_insert_id();
				mysql_query("insert into ".T_CUST_VALS." select ".$id." 'contestant', field, val from ".T_CUST_VALS."  where contestant='".addslashes($_GET['id'])."'");
				$message="Duplicated-now editing duplicate";
				$q=mysql_query("select * from ".T_CUST_VALS." where contestant='".$id."'");
				while($r=mysql_fetch_array($q)){
					$customE[$r['field']][1]=$r['val'];	
				}
				}else $message="Couldnt duplicate because because ".mysql_error();
			$body=registerForm($message,$id, $ro['name'], $ro['description'], $customE,'');
			}else{
				
				$body=registerForm( "An error occurred");
			}
		}
		}
	}else if($_GET['do']=="del"){
		if(is_numeric($id)){
			$que=mysql_query("select name, image from ".T_CONTS." where id=$id");
			if($que)if($r=mysql_fetch_array($que)){
				$user=dsql($r['name']);
				$image=dsql($r['image']);
			}
			$ok=false;
			if(isset($_GET['sure'])){						   
			if(mysql_query("delete from ".T_CONTS." where id=$id limit 1")){
				if(mysql_query("delete from ".T_REGS." where contestant=$id")){
				if(mysql_query("delete from ".T_SCORES." where contestant=$id")){
					if($image!=""){if(unlink($image))
					$ok=true;
					}else $ok=true;
				}
				}
			}
			$body=registerForm($ok?"User deleted":"Error deleting user ".mysql_error());
			}else{
				$body="<h2>Deleting $user</h2>Are you sure you want to delete $user?<br />
<strong>Note:</strong> This will also delete existing scores!<br />
<a href='javascript:parent.setFrameLoc(\"registration.php?&do=del&id=$id&sure=1\")' >Yes</a> ------- <a href='javascript:parent.setFrameLoc(\"registration.php?&do=ec\")'>No</a>";
			}
		}
		$event=1;
	}else if($_GET['do']=="delf"){
		if(isset($_GET['id'])){
			if($_GET['id']!=0){
				$q=mysql_query("select * from ".T_CONTS." where id='".addslashes($_GET['id'])."'");
				$r=mysql_fetch_array($q);
				
				if(unlink($r['image'])){
					if(mysql_query("update ".T_CONTS." set image='' where id='".addslashes($_GET['id'])."'")){
																														   					$r['image']='';
					$message="Picture deleted";
				}else $message="Couldnt delete picture because ".mysql_error();
				}else $message="Couldnt delete picture because ";
				$body=registerForm($message,$_GET['id'], $r['name'], $r['description'], $r['image']);
			}else{
				$body=registerForm( "An error occurred");
			}
		}
	}
}else if(isset($_GET['list'])){
		$body= "<h3>Current Registrations</h3>";
		$sort="";
		$page=0;
		if(isset($_GET['pge'])){
			if(is_numeric($_GET['pge'])){
				$page=$_GET['pge'];
			}
		}
		$tpage=$page;
		if($page>0)$page-=1;
		else $tpage=1;
		$page=$page*MAX_VIEW;
		$n="11";
		$c="22";
		$sort=" order by p.id desc " ;
		if(isset($_GET['sort'])){
		switch(substr($_GET['sort'],0,1)){
			case "1":
				$sort=" order by p.name ";
				$n="1";
			break;
			case '2':
				$sort=" order by c.name ";
				$c="2";
			break;
		}
		$search="";
		if(strlen($_GET['sort'])==1){$sort.="desc";		
		$n="11";
		$c="22";
		}
		}
		
		if(isset($_GET['search'])){
		$search=" where p.name like '%".addslashes($_GET['search'])."%'";
		}		
		$body.="<form id=searchf action='javascript:getUri(\"ajax-php.php?page=reg&list=1&\", \"searchf\", \"clist\")' ><input type=text name=search value='".htmlentities($_GET['search'],ENT_QUOTES)."' ><input type=submit value=Search >".(isset($_GET['search'])?"<input type=button value='Show all' onclick='javascript:getHttp(\"ajax-php.php?page=reg&list=1\", \"clist\")' >":"")."</form><br />";
		 
		$body.= "<table border=1 ><tr><td><a href='javascript:getHttp(\"ajax-php.php?page=reg&list=1&sort=$n\", \"clist\")'>Name</a></td><td><a href='javascript:getHttp(\"ajax-php.php?page=reg&list=1&sort=$c\", \"clist\")'>Competition</a></td><td>Position</td></tr>";
	
	if($cont=mysql_query("select p.id, p.name 'per',r.number,  ifnull(c.name, 'None') 'com' from ".T_CONTS." p left join ".T_REGS." r on p.id=r.contestant left join ".T_COMPS." c on c.id=r.competition  $search $sort limit $page,".MAX_VIEW)){
			while($c=mysql_fetch_array($cont)){
				$body.= "<tr><td><a href='javascript:setFrameLoc(\"registration.php?&do=ec&id=".$c['id']."\")'>".dsql($c['per'])."</a></td><td>".dsql($c['com'])."</td><td>".$c['number']."</tr>";
			}
		}		
		$body.="</table>";
		$rws=mysql_num_rows(mysql_query("select p.name 'per', c.name 'com' from ".T_REGS." r left join ".T_CONTS." p on p.id=r.contestant left join ".T_CONTS." c on c.id=r.competition $search"));
		$paginator=ceil($rws/MAX_VIEW);
		$body.="<br />Page:";
		for($i=1;$i<=$paginator;$i++){
			if($i==$tpage){
				$body.=" ".$i." ";
			}else $body.=" <a href='javascript:getHttp(\"ajax-php.php?page=reg&list=1&pge=$i".(isset($_GET['sort'])?"&sort=".$_GET['sort']:"").(isset($_GET['search'])?"&search=".urlencode($_GET['search']):"")."\", \"clist\")'>$i</a> ";
			if($i%10==0)$body.="<br />";
			
		}
}

/////////functionz//////////////
function registerForm($message="", $id=0, $name="", $description="", $custom=0, $file=""){

	if($custom==0){
		global $customE;
		$custom=$customE;
	}
	$retur= ($message==""?"":$message."<br />")."	<form action='registration.php?do=ec&done=1".($id==0?"":"&id=$id")."' method=post enctype='multipart/form-data' id=upform >
	<table><tr><td valign=top >
	<h2>Register Contestants</h2>
	<table><tr><td>
Name</td><td><textarea name=rname rows=1 cols=40 >$name</textarea></td></tr>
<tr><td>Description</td><td>
<textarea name=rdes rows=2 cols=40 >$description</textarea></td></tr>";
foreach($custom as $k=>$v){
	$retur.="<tr><td>{$v[0]}</td><td><textarea name='rcust[$k]' rows=1 cols=40 >{$v[1]}</textarea></td></tr>";
}
$retur.="</table>
</td><td>
<h3>Competitions:</h3><table border=1><tr><th>&nbsp;</th><th>Name</td><th>Date</th><th>Num</th></tr>";

$getc=mysql_query("select * from ".T_COMPS. " where day>=curdate() and enabled=1");
while($c=mysql_fetch_array($getc)){
$retur.= "<tr><td ><input type=checkbox name=rcomps[] value=".$c['id'];
$maxP=0;
if($id!=0){
$inc=mysql_query("select * from ".T_REGS." where contestant=$id and competition=".$c['id']);
if(mysql_num_rows($inc)>0)$retur.=" checked ";
}
$maxnumque=mysql_query("select max(number) 'num' from ".T_REGS." where competition=".$c['id']);
if($maxnumque)while($maxnumr=mysql_fetch_array($maxnumque)){
	$maxP=$maxnumr['num'];
}
$retur.=" ></td><td style='width:100px;'>".dsql($c['name']). "</td><td>".$c['day']."</td><td>".$maxP."</td></tr>";
}
/*/$tempup='		<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="300" height="500" id="webcamvid" align="middle">
	<param name="allowScriptAccess" value="sameDomain" />
	<param name="allowFullScreen" value="false" />
	<param name="movie" value="flash/webcamvid.swf?Surl='.urlencode("flashupload.php?uid=$id").'" /><param name="quality" value="high" /><param name="bgcolor" value="#ffffff" />	<embed src="flash/webcamvid.swf?Surl='.urlencode("flashupload.php?uid=$id").'" quality="high" bgcolor="#ffffff" width="300" height="500" name="webcamvid" align="middle" allowScriptAccess="sameDomain" allowFullScreen="false" type="application/x-shockwave-flash" pluginspage="http://www.adobe.com/go/getflashplayer" />
	</object>
';*/
$retur.="</table>
<input type=button  id=upbutton value=".($id==0?"Add contestant":"Update contestant")." >".($id==0?"":"<br /><input type=button value=Duplicate onclick='javascript:document.location=\"registration.php?do=dup&id=$id\"' > <br />-or-<br /> <a href='javascript:parent.setFrameLoc(\"registration.php?do=del&id=$id\")'>Delete contestant</a>")."
</td></tr><tr><td colspan=2><h3>Picture</h3> "./*($id!=0?"<h3>Capture from webcam</h3>note: you will not see change untill refresh<br />".$tempup."<h3>Or upload</h3>":"You need to save before using flash uploader.<br />").*/($file==""?"":"<br /><img src='$file'> <br />")."
<input type=file name=rimg ><br />
".($file==""?"":"(overwrite) or <a href=registration.php?do=delf&id=$id >Delete file</a>")."</td></tr></table>
</form>";
return $retur;
}

function file_upload_error_message($error_code) {
    switch ($error_code) {
        case UPLOAD_ERR_INI_SIZE:
            return 'The uploaded file exceeds the upload_max_filesize directive in php.ini';
        case UPLOAD_ERR_FORM_SIZE:
            return 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form';
        case UPLOAD_ERR_PARTIAL:
            return 'The uploaded file was only partially uploaded';
        case UPLOAD_ERR_NO_FILE:
            return 'No file was uploaded';
        case UPLOAD_ERR_NO_TMP_DIR:
            return 'Missing a temporary folder';
        case UPLOAD_ERR_CANT_WRITE:
            return 'Failed to write file to disk';
        case UPLOAD_ERR_EXTENSION:
            return 'File upload stopped by extension';
        default:
            return 'Unknown upload error';
    }
} 
?>