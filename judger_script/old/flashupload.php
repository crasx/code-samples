<?php 
require("login.php");
	if($userInfo['valid']&&$userInfo['register']){
		/**
 * Get the width and height of the destination image
 * from the POST variables and convert them into
 * integer values
 */
$w = (int)$_POST['width'];
$h = (int)$_POST['height'];

// create the image with desired width and height

$img = imagecreatetruecolor($w, $h);

// now fill the image with white since I'm not sending the 0xFFFFFF pixels
// from flash?
imagefill($img, 0, 0, 0xFFFFFF);

$rows = 0;
$cols = 0;

// now process every POST variable which
// contains a pixel color
for($rows = 0; $rows < $h; $rows++){
    // convert the string into an array of n elements
    $c_row = explode(",", $_POST['px' . $rows]);
    for($cols = 0; $cols < $w; $cols++){
        // get the single pixel color value
        $value = $c_row[$cols];
        // if value is not empty (empty values are the blank pixels)
        if($value != ""){
            // get the hexadecimal string (must be 6 chars length)
            // so add the missing chars if needed
            $hex = $value;
            while(strlen($hex) < 6){
                $hex = "0" . $hex;
            }
            // convert value from HEX to RGB
            $r = hexdec(substr($hex, 0, 2));
            $g = hexdec(substr($hex, 2, 2));
            $b = hexdec(substr($hex, 4, 2));
            // allocate the new color
            // if a color was already allocated
            // we dont need to allocate another time
            // but this is only an example
            $test = imagecolorallocate($img, $r, $g, $b);
            // and paste that color into the image
            // at the correct position
            imagesetpixel($img, $cols, $rows, $test);
        }
    }
}
ob_start();
imagejpeg($img, "", 90);
$img = ob_get_contents();
ob_end_clean();
		if($img!=""){
			if(isset($_GET['uid'])&&is_numeric($_GET['uid'])){
				$que=mysql_query("select * from ".T_CONTS. " where id='".$_GET['uid']."'");
				if($r=mysql_fetch_array($que)){
					$url=$r['image'];
					if($url=="")$url=UPLOAD_DIR.time().".jpg";
					$fh = fopen($url, 'w');
					if(!fwrite($fh, $img)){
						echo "Error opening file for save";	
					}else {
					fclose($fh);
					if(mysql_query("update ".T_CONTS." set image='".sqld($url)."' where id='".$_GET['uid']."'")){
						echo "File saved at $url";	
					}								   

					}
				}else echo "User not found";
			}else echo "error in uid";
		}
	}else echo "Insufficent privs";

//http://kevinmusselman.com/blog/2009/02/access-webcam-with-flash/
?>