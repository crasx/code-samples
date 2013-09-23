<h1>Competitions</h1>

<?php
	if(isset($_GET['message'])){
		$this->renderPartial("//formtools");
		switch($_GET['message']){
			case "edited":
				printMessageSuccess("competition successfully edited");
			break;
			case "added":
				printMessageSuccess("competition successfully added");
			break;
			case "deleted":
				printMessageSuccess("competition successfully deleted");
			break;
			case "cleared":
				printMessageSuccess("scores successfully cleared");
			break;
			case "invalid":
				printMessageError("Invalid competition, please notify support if error persists");
			break;
		
		}
	}
	Yii::app()->clientScript->registerScriptFile(JSDIR."settings/competition.index.js", CClientScript::POS_HEAD);

	
?>
<table class="fancy fullwidth" id="competitiontable">
						<thead>
							<tr>
								<th> </th>
								<th>Name</th>
								<th>Event</th>
								<th>Type</th>
								<th>Registration fields</th>
								<th>Contestants</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach($competitions as $c):?>
							<tr class="odd">
								<td>
									<a href="<?=_BASE?>/settings/competitions/edit/<?=$c->id?>" title="<?=__T("Edit competition")?>" class="tooltip table_icon"><img src="<?=ASSETSDIR?>icons/actions_small/Pencil.png" alt=""></a> 
									<a href="<?=_BASE?>/settings/competitions/delete/<?=$c->id?>" title="<?=__T("Delete competition")?>" class="tooltip table_icon"><img src="<?=ASSETSDIR?>icons/actions_small/Trash.png" alt=""></a>
									<a href="<?=_BASE?>/settings/competitions/deletescores/<?=$c->id?>" title="<?=__T("Clear scores")?>" class="tooltip table_icon"><img src="<?=ASSETSDIR?>icons/actions_small/Note-Remove.png" alt=""></a>
								</td>
								<td><?=__E($c->name)?></td>
								<td><?=$c->Event!=null?__E($c->Event->name):"<em>none</em>"?></td>
								<td><? $this->renderPartial("//settings/competitions/typeDisplay", array("obj"=>$c))?></td>
								<td><? $this->renderPartial("//settings/competitions/fieldDisplay", array("obj"=>$c))?></td>
								<td><?=__E(sizeof($c->Entries))?></td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
					<br class=c />
					<br class=c />
		<a href="<?=_BASE?>/settings/competitions/add" class="button_link">Add competition</a>