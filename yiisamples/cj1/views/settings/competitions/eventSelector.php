<select name="<?=$name?>" >
    <option value=0 ></option>
<?php
    $mid=$competition->event;
    
    $events=Events::model()->findAll();
    
    foreach ($events as $e) {
        ?>
        <option value="<?=$e->id?>" <?=$mid==$e->id?"selected":"" ?>><?= __E($e->name)?></option>        
        <?
    }


?>
</select>