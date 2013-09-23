<?php
require_once("adm/cfg.php");
require_once("login.php");
require("adm/functions-reg.php");
?>
<link rel="stylesheet" href="css/css2.php" />
<body onload='doReg()'><div id="framebody"><center><?php echo $body;

?>
<script type="text/javascript">
function doReg(){
window.parent.regEvent("<?php echo $event;?>");
}
</script>
</center></div></body>