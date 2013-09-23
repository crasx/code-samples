<?php
require("getcss.php");
header("Content-type: text/css");
?>
a{
	text-decoration:none;
	color:#<?php echo $styles['css']['a']['text']; ?>;
	
}
 
 a:hover{
	color:#<?php echo $styles['css']['a hover']['text']; ?>;
}

body{
	color:#<?php echo $styles['css']['body']['text']; ?>;
	background-color:transparent;
	
} 