<?php
require("lbp/cache2.inc.php");


$cache=new cache();
$img=$_GET['u'];
if($cache->inCache($img, "lbp/c2/"))$img="lbp/c2/".$img;
else exit;
// and here we send the image to the browse with all the stuff
// required for tell it to cache
header("Content-type: image/png");
header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($img)) . ' GMT');
// use ob buffering to find the image size.
$ImageDataLength=filesize($img);
header("Content-Length: ".$ImageDataLength);
$iimg=imagecreatefrompng($img);
imagepng($iimg)
////echo $img;
?>
