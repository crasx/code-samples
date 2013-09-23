<?php
require("getcss.php");
header("Content-type: text/css");
?>
body{	
	color:#<?php echo $styles['css']['body']['text']; ?>;
	background-image:url(../<?php echo $styles['css']['body']['file']; ?>);
	background-repeat:no-repeat;
	background-color:#<?php echo $styles['css']['body']['color']; ?>;
	background-repeat:repeat;
	padding:0px;
	margin:0px;
}
#menu{
	border-right:1px solid;border-bottom:1px solid;
	border-top:1px solid;
	width:210px;
}
li{    
	width:164px;
    height:74px;
    posiition:absolute;
    margin-top:2px;
}

ul{
	list-style-type:none;
}

a{
	text-decoration:none;
	color:#<?php echo $styles['css']['a']['text']; ?>;
	
}
 
a:hover{
	color:#<?php echo $styles['css']['a']['text']; ?>;
}
iframe{
	background-color:transparent;
}
div.menuitem{
	width:138px;
	display:block;
	padding-left:10px;

}
img.mimg{
	float:left;
	
}
div.aholder{
	background:url(../<?php echo $styles['css']['Menu fill']['file']; ?>);
	width:164px;
    height:74px;
	cursor:pointer;
    text-align:center;
    vertical-align:middle;
    display:table-cell;
    top: 50%;
    overflow:hidden;
	color:#<?php echo $styles['css']['Menu fill']['text']; ?>;
}
div.menuadiv:hover{
	color:#<?php echo $styles['css']['Menu fill hover']['text']; ?>;
	
}
div.menuadiv{
position:relative;
display:table;
}