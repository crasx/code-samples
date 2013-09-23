<?php
require_once('login.php');
if(!$userInfo['admin'])die("You shouldnt be here");
if(isset($_GET['dopcs'])){
		if(isset($_GET['do'])){
		if(isset($_FILES['upload'])&& !empty($_FILES['upload']['name'])){
			if(eregi('/x-gzip', $_FILES['upload']['type'])) {
						$command = "tar xzvf ".$_FILES['upload']['tmp_name'];
					$result = exec($command, $op);
					foreach($op as $o){
					echo $o;	
					}
				echo '<br />
			<br />
			Redirecting, please wait<br />
			<META http-equiv="refresh" content="7;URL=index.php"> ';
								
				}else{
					echo "Invalid file type";
				}
		}
			
		}else{
		
		$datestamp = date("Y-m-d");      // Current date to append to filename of backup file in format of YYYY-MM-DD
		$filename= "picture-backup-$datestamp.tar.gz";   // The name (and optionally path) of the dump file
	$path= 'uploads';
	$command = "tar cf $filename $path";
	$result = exec($command);
	header("Content-type: application/x-gzip");
	 header("Content-Disposition: Attachment; filename=$filename");
	echo file_get_contents($filename);
	unlink($filename);   //delete the backup file from the server
		
		}
}else{
if(isset($_GET['do'])){
if(isset($_FILES['upload'])&& !empty($_FILES['upload']['name'])){
	$do=true;
	$lines=array();
	if(eregi('text', $_FILES['upload']['type'])) {
		$lines=file($_FILES['upload']['tmp_name']);
	}elseif(eregi('/x-gzip', $_FILES['upload']['type'])) {
		ob_start();
		passthru("gunzip -c ".$_FILES['upload']['tmp_name']);
		$out=ob_get_contents();
		ob_end_clean();
		$lines=explode("\n",$out);
	}else{
		$do=false;
		echo "Invalid file type";
	}

	if($do){
		$templine = '';
		$linez=0;
		$err=false;
		foreach ($lines as $line)
		{
			if (substr($line, 0, 2) == '--' || $line == '')
				continue;
			$templine .= $line;
			if (substr(trim($line), -1, 1) == ';')
			{
				if(!mysql_query($templine)){ echo ('Error performing query \'<strong>' . $templine . '\': ' . mysql_error() . '<br /><br />');
				$err=true;
				}else $linez++;
				$templine = '';
			}
		}
		if(!$err){
			echo "Restore sucessful- $linez commands executed";	
		}
	}
	echo '<br />
<br />
Redirecting, please wait<br />
<META http-equiv="refresh" content="7;URL=index.php"> ';
}
}else{
$datestamp = date("Y-m-d");      // Current date to append to filename of backup file in format of YYYY-MM-DD

$filename= "backup-$datestamp.sql.gz";   // The name (and optionally path) of the dump file

$command = "mysqldump -u $sqluser --password=$sqlpassword $sqldb | gzip > $filename";
$result = passthru($command);

header("Content-type: application/x-gzip");
 header("Content-Disposition: Attachment; filename=$filename");
echo file_get_contents($filename);
unlink($filename);   //delete the backup file from the server
}
}
?>