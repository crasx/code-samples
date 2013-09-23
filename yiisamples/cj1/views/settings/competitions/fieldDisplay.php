<?php
$fields="";
$af=$obj->listSelectedRegistrationFields();
foreach($af as $f){
    $fields.=__E($f['value'])."<br />";
}
echo sizeof($obj->Fields);
?> <a href="#" onClick="showSibling(this);" style="text-decoration:none;" >[+]</a><br />
            <span class="criteria" style="display:none"><?= $fields ?></span>