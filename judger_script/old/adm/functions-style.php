<?php 
require_once('cfg.php');
$body="<h1>Design</h1>";
if(!$userInfo['admin']){$body=("You shouldnt be here");return;}
//0=name
//1=val
//2=color
//3=text color
//4=file err
//5=color err
//6=text color err

$files=array();
if($q=mysql_query("select * from ".T_STYLES." order by style, name asc")){
	while($r=mysql_fetch_array($q)){
		$files[$r['id']]=array();
		$files[$r['id']][0]=$r['name'];
		$files[$r['id']][1]=($r['disablefile']!=1?$r['file']:false);
		$files[$r['id']][2]=($r['disablecolor']!=1?$r['color']:false);
		$files[$r['id']][3]=($r['disabletext']!=1?$r['text']:false);
		$files[$r['id']][4]="";
		$files[$r['id']][5]="";
		$files[$r['id']][6]="";
		$files[$r['id']][7]=$r['style'];
		if(isset($_FILES['file'.$r['id']])&&$_FILES['file'.$r['id']]['tmp_name']>''){
			$error="";
			if(upload('file'.$r['id'],$r['file'])){
				mysql_query("update ".T_STYLES." set file='".sqld($error)."' where id='".$r['id']."'");
				$files[$r['id']][3]=false;
				$files[$r['id']][1]=$error;
			}else  $files[$r['id']][3]=$error;
		}
		if(!$r['disablecolor'])
		if(isset($_POST['color'.$r['id']])){
			$hx=$_POST['color'.$r['id']];
			if(substr($hx,0,1)=="#")$hx=substr($hx,1);
			if(preg_match("/[A-Fa-f0-9]{6}/i",$hx)){
				mysql_query("update ".T_STYLES." set color='".sqld($hx)."' where id='".$r['id']."'");
				$files[$r['id']][2]=$hx;
			}else $files[$r['id']][5]="Invalid hex character";

		}
		if(!$r['disabletext'])
		if(isset($_POST['text'.$r['id']])){
			$hx=$_POST['text'.$r['id']];
			if(substr($hx,0,1)=="#")$hx=substr($hx,1);
			if(preg_match("/[A-Fa-f0-9]{6}/i",$hx)){
				mysql_query("update ".T_STYLES." set text='".sqld($hx)."' where id='".$r['id']."'");
				$files[$r['id']][3]=$hx;
			}else $files[$r['id']][6]="Invalid hex character";

		}
	
	}
 }
//logo
//bg
//menu t,m,b
$type="";
	$form="<form action='skinner.php' method=post enctype='multipart/form-data'><table border=1 >";
	foreach(array_keys($files) as $f){
	if($type!=$files[$f][7]){
		if($type!="")$form.="<tr><td colspan=4 align=center ><input type=submit value=Update ></td></tr></table>";
		$form.="<h2>".$files[$f][7]." style</h2>";
		$form.="<table border=1 ><tr><th>&nbsp;</th><th>File</th><th>Color</th><th>Text color</th><tr>";
		$type=$files[$f][7];
	}
	$form.="<tr><td valign=top >".$files[$f][0]."</td>
	
	<td valign=top align=center >".($files[$f][1]===false?"-":"<input type=file name='file".$f."' /><br />".$files[$f][1]).($files[$f][4]!=false?"<br />".$files[$f][4]:"")."</td>
	
	<td valign=top align=center >".($files[$f][2]===false?"-":"<input type=text name='color".$f."' value='#".$files[$f][2]."' />").($files[$f][5]!=false?"<br />".$files[$f][5]:"")."</td>
	
	<td valign=top align=center >".($files[$f][3]===false?"-":"<input type=text name='text".$f."' value='#".$files[$f][3]."' />").($files[$f][6]!=false?"<br />".$files[$f][6]:"")."</td></tr>";
	}
	$form.="<tr><td colspan=4 align=center ><input type=submit value=Update ></td></tr></table></form>";
	
	$body=$form;
	
	
	
	
	
	
	
	
	
	
	function upload($file,$old){
		global $error;
		if(isset($_FILES[$file])){
		if ($_FILES[$file]["error"] > 0)
			  {
			  $error= "Error: " . file_upload_error_message($_FILES[$file]["error"]) ;
			  return false;
			  }	
			  if($old!=""&&file_exists($old))unlink($old);
			 if(!stristr($_FILES[$file]['type'], "image/")){
				 $error= "Please only upload images";
				 return false;
			 }
			 $loc=STYLE_UPLOAD_DIR.$_FILES[$file]['name'];
			 $fix=0;
			 while(file_exists($loc)){
				$loc=STYLE_UPLOAD_DIR.($fix++).$_FILES[$file]['name'];
			 }
			if(!move_uploaded_file($_FILES[$file]['tmp_name'],$loc)){
				$error= "Error moving uploaded file";
				return false;
			}else{
				$error=$loc;
				return true;
			}
		}else{
			$error="No file uploaded";
			return false;
		}
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