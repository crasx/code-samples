<?php
	switch($obj->type){
		case $obj::TYPE_CRITERIA:
			$criteria="";
			foreach($obj->CompetitionCriteria as $c){
				$criteria.=__E($c->Criteria->name)." [".$c->Criteria->min." to ".$c->Criteria->max."]<br />";
			}
		?>
			Criteria <a href="#" onClick="showSibling(this);" style="text-decoration:none;" >[+]</a><br />
			<span class="criteria" style="display:none"><?= $criteria ?></span>
		<?php
		break;                
		case $obj::TYPE_BEST:?>
		Best<br /><em>Select top <?= $obj->bestSelect ?>. <?=$obj->bestOrderMatters?"Order matters.":"Order does not matter."?></em>
		<?
		break;
		case $obj::TYPE_RANK:
			echo 'Rank ';
		break;
		default:
			echo 'Unknown';
	}
 ?>
 