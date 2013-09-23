<?php 
		require("../teamspeakdisplay/teamspeakdisplay.php");
				// Get the default settings
	$settings = $teamspeakDisplay->getDefaultSettings();
	$settings["serveraddress"] = "reclan.com";
	echo $teamspeakDisplay->displayTeamspeakEx($settings);	

?>