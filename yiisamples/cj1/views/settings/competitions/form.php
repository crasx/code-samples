<?php $this->renderPartial('//formtools');
if(isset($error)){
	printMessageError($error);
}
	Yii::app()->clientScript->registerScriptFile(JSDIR."/settings/competition.js", CClientScript::POS_HEAD);
?>

<form action="?" onsubmit="lsFixInputs($j('#competitionForm'));" id="competitionForm" method="post" >
<fieldset>
    <legend>Basic information</legend>
	
    <p><label>Name:</label> <input class="sf" name="competition[name]" type="text" value="<?=__EF($competition->name)?>"><?php printError($competition->getError('name'))?> </p>
    <p><label>Description:</label> <textarea class="lf" name="competition[description]" type="text" ><?=__E($competition->description)?></textarea> <?php printError($competition->getError('description'))?></p>
   <p><label>Event:</label> <?php $this->renderPartial("//settings/competitions/eventSelector", array("competition"=>$competition, "name"=>"competition[event]")) ?><?php printError($competition->getError('event'))?> <img src="<?=ASSETSDIR?>icons/small_icons/Info.png" height=16px class="tooltip" title="Configure events via settings >> events" /> </p>
   <p><label>Max contestants:</label> <input class="sf" name="competition[maxContestants]" type="text" value="<?=__EF($competition->maxContestants)?>"><?php printError($competition->getError('maxContestants'))?> <img src="<?=ASSETSDIR?>icons/small_icons/Info.png" height=16px class="tooltip" title="Enter 0 or leave blank for unlimited" /> </p>
	<p><label>Competition type</label>
    <input type="radio" name="competition[type]" value="criteria" id="competition.criteria" <?= $competition->type==$competition::TYPE_CRITERIA?"checked":"" ?> /><label for="competition.criteria" class="tooltip"  >Criteria</label> <img src="<?=ASSETSDIR?>icons/small_icons/Info.png" height=16px class="tooltip" title="Give contestants scores based on criteria" />
	<?php printError($competition->getError('type'))?>
    <br /><label></label>
    <input type="radio" name="competition[type]" value="best" id="competition.best" <?= $competition->type==$competition::TYPE_BEST?"checked":"" ?> /><label for="competition.best" class="tooltip" >Best</label><img src="<?=ASSETSDIR?>icons/small_icons/Info.png" height=16px class="tooltip" title="Select best contestants" />
    <br /><label></label>
    <input type="radio" name="competition[type]" value="rank" id="competition.rank" <?= $competition->type==$competition::TYPE_RANK?"checked":"" ?>  /><label for="competition.rank" class="tooltip" >Rank</label><img src="<?=ASSETSDIR?>icons/small_icons/Info.png" height=16px class="tooltip" title="Rank all contestants from best to worst" />
    </p>
</fieldset>
<fieldset>
        <legend>Registration fields</legend>

    <?php printError($competition->getError('registration'))?>
    <?=printDescription("The fields to be used during registration. Checkboxes signify a required field.<br />Note: The only pre-added fields are an ID, name and description")?>
            <?php $this->renderPartial('//generate/listSelector', array("items"=>$competition->listAllRegistrationFields(), "selected"=>$competition->listSelectedRegistrationFields(), "checked"=>$competition->listCheckedRegistrationFields(), "name"=>"competition[registration]", "usecheckboxes"=>true ,"checkboxname"=>"required", "helpmessage"=>" Use checkboxes to mark a field as required")); ?>
    <a href="javascript:addRegistration()"><img src="<?=ASSETSDIR?>icons/small_icons/Add.png" height=16px class="tooltip" title="Add field" /></a><br />
    <a href="javascript:modifyRegistration()"><img src="<?=ASSETSDIR?>icons/small_icons/Modify.png" height=16px class="tooltip" title="Edit field" /></a><br />
    <a href="javascript:deleteRegistration()"><img src="<?=ASSETSDIR?>icons/small_icons/Delete.png" height=16px class="tooltip" title="Delete field" /></a><br />
</fieldset>

<fieldset id="criteriafields" style="display:none">
        <legend>Criteria</legend>

    <?php printError($competition->getError('criteria'))?>
            <?php $this->renderPartial('//generate/listSelector', array("items"=>$competition->listAllCriteria(), "selected"=>$competition->listSelectedCriteria(), "name"=>"competition[criteria]")); ?>
    <a href="javascript:addCriteria()"><img src="<?=ASSETSDIR?>icons/small_icons/Add.png" height=16px class="tooltip" title="Add criteria" /></a><br />
    <a href="javascript:modifyCriteria()"><img src="<?=ASSETSDIR?>icons/small_icons/Modify.png" height=16px class="tooltip" title="Edit criteria" /></a><br />
    <a href="javascript:deleteCriteria()"><img src="<?=ASSETSDIR?>icons/small_icons/Delete.png" height=16px class="tooltip" title="Delete criteria" /></a><br />
</fieldset>
<fieldset id="bestfields" style="display:none">
        <legend>Best</legend>
    	<p><label>Select best:</label><input type="text" name="competition[best][select]" value="<?= __EF($competition->bestSelect) ?>" /> <img src="<?=ASSETSDIR?>icons/small_icons/Info.png" height=16px class="tooltip" title="A number. Allows the judge to select best x contestants" /><?php printError($competition->getError('bestSelect'))?></p>

    	<p><label>Order matters:</label><input type="checkbox" name="competition[best][order]" <?= $competition->bestOrderMatters?"checked":"" ?> />Yes <?php printError($competition->getError('bestOrderMatters'))?></p>	
</fieldset>
    <p><input class="button" type="submit" value="Submit" > <input class="button" type="reset" value="Reset"><a class="button_link" href="<?=_BASE?>/settings/competitions">Cancel</a></p>
<?php $this->renderPartial('//generate/overlay');?>