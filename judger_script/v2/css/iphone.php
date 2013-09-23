<?php
require("getcss.php");
header("Content-type: text/css");
?>
body{	
	color:#<?php echo $styles['iphone']['body']['text']; ?>;
	padding:0px;
	margin:0px;
	background-color:#<?php echo $styles['iphone']['body']['color']; ?>;
}
li{
	border-bottom:1px solid #<?php echo $styles['iphone']['li']['color']; ?>;
	font-size:20px;
	font-weight:bold;
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
	margin:0;
	padding:0;
	
}
td.mtop{
	border-bottom:1px solid #<?php echo $styles['iphone']['td']['color']; ?>;
	font-size:20px;
	font-weight:bold;
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
	margin:0;
	padding:0;
	
}
ul{
	display:block;
	left:0;
	margin:0;
	min-height:418px;
	padding:0;
	position:absolute;
	top:41px;
	width:100%;
}

a{
	text-decoration:none;
	color:#<?php echo $styles['iphone']['a']['text']; ?>;
}
 
 a:hover{
	color:#<?php echo $styles['iphone']['a hover']['text']; ?>;
}
iframe{
	background-color:transparent;
}
img.mimg{
	float:left;
	
}
div.aholder{
	background-color:#<?php echo $styles['iphone']['a holder']['color']; ?>;	
	text-align:left;	
    color:#<?php echo $styles['iphone']['a holder']['text']; ?>;
	font-size:20px;	
	padding:10px 32px 10px 8px;
    border-bottom:1px #000000;
}
input.isubmit{
	font-size:20px;
}
img.hund{
	width:100%;
}