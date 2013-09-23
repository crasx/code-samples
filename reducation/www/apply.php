<?php
ob_start();

define('IN_PHPBB', true);
    $phpbb_root_path ='forum/';
    $phpEx = substr(strrchr(__FILE__, '.'), 1);
    include($phpbb_root_path . 'common.' . $phpEx);

    // Start session management
    $user->session_begin();
    $auth->acl($user->data);
    $user->setup();

    page_header('Apply');

    $template->set_filenames(array(
        'body' => 'main/std.html',
    ));
	
	if(!empty($user->data['is_registered'])&&empty($user->data['is_bot'])){
		global $db;
		$regged = $db->sql_query_limit("select * from remembers where uid=".$user->data['user_id'], 1);
		if($r = $db->sql_fetchrow($regged))
		{
			echo "You are already in raven! Congrats!";	
			
		}else{
			$doAll=true;
			if(isset($_GET['a'])&&is_numeric($_GET['a'])){
						$appV = $db->sql_query_limit("select * from reappdates where id=".$_GET['a']." and end > ".time()." and begin <".time(), 1);
						if($r = $db->sql_fetchrow($appV))
						{
							$doAll=false;
					
							$fields=array(
										  
							"id"=>array("", $user->data['user_id'], 0),
							"app_id"=>array("", $_GET['a'], 0),
							"ip"=>array("", $_SERVER['REMOTE_ADDR'], 0),
							"date"=>array("", "now()", 2),
							"name"=>array("Name", $_POST['name'], 0),
							"haloName"=>array("Halo Name", $_POST['haloName'], 0),
							"xfire"=>array("X-Fire", $_POST['xfire'], 0),
							"email"=>array("Email", $_POST['email'], 0),
							"dob"=>array("Date of birth mm/dd/yyyy","", 0),
							"location"=>array("Location", $_POST['location'], 0),
							"years"=>array("How many years have you played halo?", $_POST['years'], 0),
							"server"=>array("What is your favorite server?", $_POST['server'], 0),
							"why"=>array("Why do you feel you would be a good match for RE?", $_POST['why'], 1)
							);
							if(isset($_GET['s'])){
							$ok=true;
							$err="";
							$dp=strtotime($_POST['dob']);
							if(!$dp)$err="Invalid date";
							else $fields['dob'][1]=date("Y-m-d", $dp);
							if(!is_numeric($fields['years'][1]))$err="Please enter years played in numeric form";
							if($err==""){
									foreach($fields as $k=>$v){
											if($v[1]==""){
												$ok=false;	
											}
										
									}
									if(!$ok)$err= "<h2>All fields are required</h2>";
									else{
										$sql = 'INSERT INTO request(';
										$vals='';
										foreach($fields as $k=>$v){
											$sql.=$k.", ";
											if($v[2]!=2)
												$vals.="'".$db->sql_escape($v[1])."', ";
											else 
												$vals.="".$v[1].", ";
				
										}
										$sql=substr($sql, 0, -2);
										$vals=substr($vals, 0, -2);
										$sql.=") values(".$vals.")";
										$db->sql_query("delete from request where app_id=".$_GET['a']." and id=".$user->data['user_id']);
										$db->sql_query($sql);
										echo "<h3>Thank you for applying. You will be notified of any changes to your status</h3><br />";
									}
							}//$err=""

						}//get s
							$app = $db->sql_query_limit("select * from request where app_id=".$_GET['a']." and id=".$user->data['user_id'], 1);						
							if(!isset($_GET['s']))
							if($r = $db->sql_fetchrow($app))
							{
								foreach($r as $k=>$v){
									$fields[$k][1]=$v;	
								}
							}
							createForm($_GET['a'], $fields, $err);
					}
		
				}
			}	
			if($doAll){
				$apps = $db->sql_query("select * from reappdates order by end");
				echo "<h2>Applications</h2><table><tr><td>&nbsp;</td><td>App begins</td><td>App closes</td></tr>";
				while($ra = $db->sql_fetchrow($apps))
				{	
					echo "<tr><td>";
					if($ra['end']>time()&&$ra['begin']<time()){
						echo "<a href='?a=".$ra['id']."'>Apply</a>";	
					}else echo "closed";
					echo "</td><td>".date("M-d-Y",$ra['begin'])."</td><td>".date("M-d-Y",$ra['end'])."</td></tr>";
				}
				echo "</table>";	
				
			}
		
	}else{
		echo "<h2>Login to apply to raven</h2>";
	}
function createForm($a, $arr, $err=""){
	if($err!="")echo "<center>$err</center><br />";
	echo "<form action='apply.php?s=1&a=$a' method=post ><table >\n";
	
	foreach($arr as $k=>$v){
		if($v[0]!="")
		if($v[2]==0)
			echo "<tr><td align=right >".$v[0]."</td><td><input type=text name=$k value='".$v[1]."'></td></tr>";	
		else 
			echo"<tr><td align=right >".$v[0]."</td><td><textarea name=$k rows=4 cols=80 >".htmlentities($v[1])."</textarea></td></tr>";	
	}
	
	echo "</table><center><input type=submit value='Good luck' /></center></form>";
}

$template->assign_vars(array(
	'HEADERTEXT'		=> "Apply",
	'OUTPUT'				=> ob_get_contents(),
));

ob_end_clean();
    page_footer();
?>
