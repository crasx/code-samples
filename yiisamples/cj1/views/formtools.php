<?php
	if(!defined('FORMTOOLS')){
		function printError($obj){
				?>
				<span class="validate_error"><?=$obj?></span><?
		}
		function printSuccess($obj){
				?>
				<span class="validate_success"><?=$obj?></span><?
			}
		function printDescription($obj){
				?>
				<span class="field_desc"><?=$obj?></span><?
			
		}
		function printMessageSuccess($obj){
			?><div class="notification success" style="cursor: auto; "> 
							<span></span> 
							<div class="text"> 
								<p><strong><cufon class="cufon cufon-canvas" alt="<?=__T("Success!")?>" style="width: 77px; height: 22px; "><canvas width="94" height="23" style="width: 94px; height: 23px; top: -1px; left: -1px; "></canvas><cufontext><?=__T("Success!")?></cufontext></cufon></strong> <?=$obj?></p> 
							</div> 
						</div><?
		}	
		function printMessageError($obj){
			?><div class="notification error" style="cursor: auto; "> 
							<span></span> 
							<div class="text"> 
								<p><strong><cufon class="cufon cufon-canvas" alt="<?=__T("Error!")?>" style="width: 77px; height: 22px; "><canvas width="94" height="23" style="width: 94px; height: 23px; top: -1px; left: -1px; "></canvas><cufontext><?=__T("Error!")?></cufontext></cufon></strong> <?=$obj?></p> 
							</div> 
						</div><?
		}
		define("FORMTOOLS", 1);
	}
?>