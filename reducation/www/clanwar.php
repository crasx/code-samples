<?php
require_once('../recaptcha/recaptchalib.php');
ob_start();
define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Clanwar');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
	$sza=array("","","","","","","");
	$size=$_POST['size'];
	if(!is_numeric($size)||($size<0||$size>7)){
		$size=0;
	}
	
		$sza[$size-1]="selected=selected";
		
	$vals=array(
				"I agree to all of the above rules"=>array("cbox", 1, 2),
				"Clan name"=>array("cname", 1, 0), 
				"Clan tag"=>array("ctag", 1, 0), 
				"Clan server names"=>array("cserver", 1, 0), 
				"Clan leader(s)"=>array("cleader", 1, 0), 
				"Xfire"=>array("xfire", 1, 0), 
				"Date of clan war"=>array("datef", 1, 0), 
				"Game size"=>array("size", 1, "<select name='size'><option value=1 $sza[0] >3v3</option><option value=2 $sza[1] >4v4</option><option value=3 >5v5</option><option value=4 >6v6</option><option value=5 >7v7</option><option value=6 >8v8</option>"), 
				"Reason for war"=>array("reason", 1, 1), 
				"Website"=>array("website", 0, 0),
				);
	foreach(array_keys($vals) as $k){
		if($k[0]=="size")		
		$vals[$k][3]=$size;
		else 
		$vals[$k][3]=$_POST[$vals[$k][0]];
	}
global $db;
	if(isset($_GET['d'])){
		foreach($vals as $k=>$v){
			if($v[2]==1&&$v[3]==""){
				$error="\"$v[0]\" is required";				
			}			
		}
		$resp = recaptcha_check_answer ($privKey,
                                $_SERVER["REMOTE_ADDR"],
                                $_POST["recaptcha_challenge_field"],
                                $_POST["recaptcha_response_field"]);

		if (!$resp->is_valid) {
		$error="Invalid captcha!";
		}
		if($error==""){
			$query="insert into clanwar(";
			$k="";$v="";
			
			foreach($vals as $iv=>$ik){
				if($ik[0]!="cbox"){
					$k.="$ik[0], ";
					$v.="\"".mysql_real_escape_string($ik[3])."\", ";
				}
			}
			$v=substr($v, 0,-2);
			$k=substr($k,0,-2);
			$query.=$k.") values($v)";
			$db->sql_query($query);
		}

		
	}
?>
<div style="padding-left:10px">
<h2>Before requesting a clanwar with us you must agree to all of the following:</h2>
<ol><li>Each clan is to pick a single representitive to act as leader in the rule setting process and war itself.</li>
<li>Both teams have the chance to host half of the matches held on their server, each clan only host 1 server per round.</li>
<li>A clan war must consist of atleast 3 matches, the amount of matches is to be determinded by the leaders of both clans (standard amount of matches per round is 5). number of matches must be odd.</li>
<li>Since there will always be an odd number of matches per round the last match shall be hosted on a clan's server using the gametype picked and created by the second. who does what is to be decided by the leader of the clans.</li>
<li>A clan war cannot consist of less then 6 players. any amount under 3v3 is not allowed.</li>
<li>Once the clan war begins each clan must not have an amount of players on the server at once which is greater than the preagreed amount once the war begins.</li>
<li>The clan war does not begin until each team has its desired players in the first match.</li>
<li>Once the 1st match begins the clan war has begun and cannot be postpownd or ended until a victory for every match has been decided or a clan forfiets.</li>
<li>Only the designated clan representitive can decide to forfiet.</li>
<li>The clan representitive does not have to partake in the matches of the clan war.</li>
<li>If at anytime either clan has a member leave the server, they can have another player substitube for them.</li>
<li>The clan war will continue reguardless of how many players are left in play from either clan.</li>
<li>If both clans are each hosting half of the round then a no more then 15 minute delay is allowed between switch from one server to another.</li>
<li>Both clans are required to know both rcon and password for every server they are to play on.</li>
<li>If rcon is abused the password can be changed by the second clan, however proof of this abuse must be recorded. this is how -> http://i212.photobucket.com/albums/cc310/japaneseanime8/np_cheat.jpg</li>
<li>If the clan who is hosting the server that is currently being fought on abuses use of rcon then the defending clan can declare round victory.</li>
<li>If both clans are each hosting half the round then the clan that sent their gametype to the other clan for use in the last match is allowed to test set gametype on the other clans server prior to the start of the clan war. if the gametype is not satisfactery at that time the clan that created it can have it modified until they are satified. after each modification the author clan has the right to test it on the second clans server. max number of edits from author clan is 2.</li>
<li>Once the clan war has begun niether the mapcycle nor gametype(s) can be altered and no map(s) or gametype(s) can be added or removed.</li>
<li>Each clan is allowed the oportunity to have an extra player sit in for the round for the sole purpose of sightjacking. each team has the chance to excerise this right before the clan war begins. if during the server switch (assuming both clans are hosting half of the round) a clan or both clans can decide to add an extra player for sighjacking purposes if the other clan permits it. if this right has not been used then the other players participating in the clan war can sighjack for the sole, only, purpose of detecting bots. max numbers of designated sitejackers per clan is 1.max numbers of designated </li>
<li>The designated sitejacker(s) are to move to and stay stationary in a single prechoosen location (choosen by both teams) that is furtherest from the fighting, in the most remote location on each map. once each match begins each perdeterminded sighjacker is to immediately move to the designated sighjacking area, once they have moved there the match is to be reset via rcon. -> sv_map_reset</li>
<li>Once all of the members have joined the first server the match is to be reset via rcon. -> sv_map_reset</li>
<li>During a server switch the match is be to reset via rcon once every member as joined the server. -> sv_map_reset</li>
<li>During end game score for every death a designated sighjacker has -1 points is to be added to the other teams score (slayer and juggernaut only) also for every kill done by the sighjacker(s) -2 points is to be added to that teams score (slayer and juggernaut only).</li>
<li>For every kill done by the sighjacker(s) -1 point is to be added to that teams score (CTF only) also if if the sighjacker score in a flag in CTF the other team clan declare match victory.</li>
<li>For every sec pointed by the sighjacker(s) -3 secs is to be added to that teams score (king of the hill and oddball only).</li>
<li>For every point socred by the sighjacker(s) -2 points is to be added to that teams score (race only).</li>
<li>Either clan can forfiet the round at any time.</li>
<li>The date of the clan war can be rescheduled until a date that is agreed on by both selected representives is reached. rescheduling time can not exceed 7 days from first scheduled match.</li>
<li>The clan that wins the most matches wins the round, one round victory constitutes as a clan war victory. -> one round per clan war. clans can declare war on eachother more than once.</li>
<li>Once the clan war begins neither clan can add any new rules to the currently round.</li>
<li>If any changes are made to either clan's rules after the scheduling of a date for the first match the editing clan is required to inform the other clan of the set change(s).</li>
<li>If at any moment during the clan war if a rule is not followed then the other clan can declair round victory.</li>
<li>If a clan has any questions or concerns reguarding any of the rules set by the other clan they have to the right inquire about them.</li>
<li>A screen shot(s) of each match's post game score is to be taken and uploaded onto a locaton where both clans can view it.</li>
<li>If a server crashes leaders decide the proper coarse of actions that is to be taken.</li>
<li>All players participating in a clan war must wear clan tag. if a player is not wearing clan their membership in the clan must be documemented on the clans website or xfire community.</li></ol>
</div><br /><br /><hr /><br />
<h2>Apply for a clan war</h2>
<?php echo "<h2>$error</h2>"; ?>
<form action="clanwar.php?d=s" method=post >
<table>
<?php
foreach($vals as $k=>$c){
	echo "<trr><td>$k".($c[1]==1?"*":"")."</td><td>";
	if(is_numeric($c[2])){
	switch($c[2]){
	case 0:
	echo "<input type=text name=$c[0]  value='".addslashes($c[3])."' />";
	break;
	case 1:
	echo "<textarea rows=6 cols=50 name=$c[0] >".htmlentities($c[3])."</textarea>";
	break;
	case 2:
	echo "<input type=checkbox name=$c[0] ".($_POST[$c[0]]==1?"checked=checked":"")." />";
	break;
	default: 
	}
	}else echo $c[2];
echo "</td></tr>";
}
echo "<tr><td>Humainzator</td><td>";
echo recaptcha_get_html($pubKey);
echo "</td></tr>";
echo "<tr><td colspan=2 align=center>*=required<br />We will review your challenge and contact you soon<br /><input type=submit value=Challenge /></td></tr></table>";
?>
</form>
<?php

$template->assign_vars(array(
	'HEADERTEXT'		=> "Clanwars",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();


?>