<?php
   set_time_limit(0);
   $bdir="/home/crasxit0/public_html/fast/downloads/amLEUF3RPQ/haloce";
   $dir_handle = @opendir("$bdir/maps") or die("Unable to open map list");
   $filenames=array();
while ($file = readdir($dir_handle)) {
	if(strlen($file)>4&&substr($file, strlen($file)-4, 4)==".rar"){
	$filenames[]=$file;
	}
}
closedir($dir_handle);
natcasesort($filenames);
//echo "<a href='http://fastclan.org/allmaps.php'>Click here to download all maps</a><br />";
if(file_exists($bdir."/allmaps.tgz"))unlink($bdir."/allmaps.tgz");
$files=implode(" ", $filenames);
chdir("$bdir/maps");
echo exec("dir");
echo $files;
echo exec("tar czvf $bdir/allmaps.tar.gz $files");
?>