<?php
require_once('cfg.php');
if(!$userInfo['admin'])die("You shouldnt be here");
	$body="<h1>Backup server</h1><br /><a href='backup.php'>Download backup</a><br>
<br>
<h2>Restore</h2>
<h3>WARNING: THIS WILL ERASE ALL CURRENT DATA</h3>
<form action='backup.php?do=upload' enctype='multipart/form-data'  method=post >
Upload: <input type=file name=upload ><br>
<input type=submit value='Restore'></form><br />
<hr><br />
<h1>Backup Images</h1><br /><a href='backup.php?dopcs=1'>Download backup</a><br>
<br>
<h2>Restore</h2>
<h3>WARNING: THIS WILL OVERWRITE EXISTING PICTURES</h3>
<form action='backup.php?dopcs=1&do=upload' enctype='multipart/form-data'  method=post >
Upload: <input type=file name=upload ><br>
<input type=submit value='Restore'></form><br />";




?>