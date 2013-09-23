<?php
require_once('cfg.php');
if(!$userInfo['admin'])die("You shouldnt be here");
if(isset($_GET['do'])){
	if($_GET['do']=="edu"){
		if(isset($_GET['sort'])){
		$body=listUsers();
		}else{		
		if(!isset($_GET['userR'])){
			$body.=createEditUserForm();		
		}else{
			if(isset($_GET['d'])){
				$oldu=$_GET['userR'];
				$name=$_GET['name'];
				$login=$_GET['login'];
				$contact=$_GET['contact'];
				$pass=$_GET['p1'];
				if($name=="")$err.="Name can't be blank<br />";
				if($login=="")$err.="Login can't be blank<br />";
				if(strcmp($_GET['p1'],$_GET['p2'])!=0)$err.="Passwords dont match<br />";
				else if($_GET['p1']==""&&$oldU!=0)$err.="Password can't be blank<br />";		
						$privs=0;
				if(isset($_GET['privs'])){
					if(is_array($_GET['privs'])){
					foreach($_GET['privs'] as $k=>$v){
						if($v=="a")$privs+=1;
						if($v=="r")$privs+=2;
						if($v=="j")$privs+=4;	
						if($v=="m")$privs+=8;					}
					}
						if($oldu==$userInfo['id']&&$privs==0)$err.="You must remain an admin<br />";
				}
				if($oldu==$userInfo['id']&&$privs==0)$err.="You must remain an admin<br />";
				if($privs==0)$err.="User must have privileges";						
				if(isset($err))	$body.=createEditUserForm($name, $login, $contact, $privs,$err,$oldu);
				else{//alles gut
					if($oldu==0){if(addUser($login, $name, $contact, $pass, $privs)){
					$body.="<h3>User sucessfully added!</h3>".createEditUserForm();
					}}elseif(editUser($login,$name,$contact,$pass,$privs,$oldu)){
					$body.="<h3>User sucessfully edited!</h3>".createEditUserForm();
					}else $body.=createEditUserForm($name, $login, $contact, $privs, "There was an error editing:".mysql_error(),$oldu);
				}
		}else{		//not edited yet
			$found=false;
			if(is_numeric($_GET['userR'])){
				if($_GET['userR']!=0){
					$rs=mysql_query("select * from ".T_USERS." where id=".addslashes($_GET['userR']));
					$ret=array();
					if($rs)if($r=mysql_fetch_array($rs)){
						$name=$r['name'];
						$login=$r['username'];
						$privs=$r['privs'];
						$contact=$r['contact'];
						$found=true;
						$body.=createEditUserForm($name, $login, $contact, $privs, "", $_GET['userR']);
					}
				}else{
				$found=true;
				$body.=createEditUserForm();
				}
			}
			if(!$found){// no such user
			$body.="<h3>An error occurred finding user</h3>".createEditUserForm();
			}		
		}
	}//end etit user selected	
		$body.=listUsers();
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////
	}else if($_GET['do']=="dupcomp"){
		$found=0;
		if(isset($_GET['dup'])){
			if(is_numeric($_GET['dup'])){
				$id=$_GET['dup'];
				$que=mysql_query("select * from ".T_COMPS." where id=$id");
				if($que)if($r=mysql_fetch_array($que)){
					$found=1;
							 
				}
			}
		}	
		if($found){
			$que=mysql_query("insert into ".T_COMPS." (name, day, enabled) select name, day, enabled from  ".T_COMPS." where id=$id");
			$iid=mysql_insert_id();
			if($que)$que=$que&&mysql_query("insert into ".T_CCLIST." (competition, category) select $iid as competition, category from ".T_CCLIST." where competition=$id");		
			if($que){
				$body.="<h2>Competition duplicated.</h3>";
			}else $body="<h2>Error duplicatng:".mysql_error()."</h2>";
		}else $body="<h2>Error finding competition</h2>";
	}else if($_GET['do']=="delcomp"){
		$found=0;
		$name="";
		if(isset($_GET['dc'])){
			if(is_numeric($_GET['dc'])){
				$id=$_GET['dc'];
				$que=mysql_query("select * from ".T_COMPS." where id=$id");
				if($que)if($r=mysql_fetch_array($que)){
					$found=1;
					$name=$r['name'];
							 
				}
			}
		}
		if($found){
			if(isset($_GET['sure'])){
				if(strcmp(md5($_GET['sure']), $userInfo['password'])==0){
				$que=mysql_query("delete from ".T_COMPS." where id=$id");
				$que=$que&&mysql_query("delete from ".T_SCORES."  where category in(select category from ".T_CCLIST ." where competition=$id)");		
				$que=$que&&mysql_query("delete from ".T_CCLIST." where competition=$id");
				$que=$que&&mysql_query("delete from ".T_REGS." where competition=$id");				
				if($que){
					$body.="<h2>Competition deleted</h2>";
				}else{		
				$body="Error deleting because ".mysql_error();
				}
				}else $body="<h2>Invalid password</h2>";
			}else{
				$body.="<h2>Please enter your password</h2>	<h3>Note: This will also delete scores and registrations to $name</h3>";
				$body.="<form action='javascript:getUri(\"ajax-php.php?page=admin&do=delcomp&dc=".$id."&\",\"delf\", \"page\")' id=delf ><input type=password name=sure ><br />
<input type=submit value='Delete $name'></form>";
			}
		}else $body="<h2>Category not found</h2>" ;
	}else if($_GET['do']=="edc"){
		
		$competition=0;
			if(isset($_GET['edit'])){
				if(is_numeric($_GET['edit'])){
					$competition=$_GET['edit'];
					$que=mysql_query("select * from ".T_COMPS." where id=$competition");
					if($que)if($r=mysql_fetch_array($que)){
						$name=dsql($r['name']);
						$date=$r['day'];
						$enabled=dsql($r['enabled']);
						$desc=dsql($r['description']);
						$finished=dsql($r['finished']);
						
					}
				}
			}
		$body.=$competition==0?"":"<h2>Edit competition</h2>";
			if(isset($_GET['eif'])){
				if(isset($_GET['cname'])){
				if(isset($_GET['cdes'])){
				if(isset($_GET['compDate'])){
					$char="/";
					if(!stristr($_GET['compDate'], $char)){
					$char="-";	
					}
					$dar=split($char, $_GET['compDate']);
					$enab=isset($_GET['cenabled'])&&$_GET['cenabled']=='on';
					$fin=(isset($_GET['cfinished'])&&$_GET['cfinished']=='on')?time():"0";
					if(sizeof($dar==3)){
					$cd=$char=="/"?checkdate($dar[0], $dar[1], $dar[2]): checkdate($dar[1], $dar[2], $dar[0]);
					if($cd){
						if($fin!=0&&$finished!=0)$fin=$finished;
					$datea=$char=="/"?$dar[2]."-".$dar[0]."-".$dar[1]:$_GET['compDate'];
					if($competition==0){
						$err=createCompetition( sqld($_GET['cname']),$datea , $enab, sqld($_GET['cdes']), $fin);
						if(is_numeric($err)){
						$dat="";
						$err="<h3>Competition added</h3>";
						$new=1;
						}								
					}else{
						$err=editCompetition($competition, sqld($_GET['cname']), $datea, $enab,sqld($_GET['cdes']), $fin);
						$name=dsql($_GET['cname']);
						$date=$datea;
						$enabled=$enab;
						$desc=$_GET['cdes'];
						$finished=$fin;
						$body.=$err==""?"Updated sucessfully at ".date("h:m:s"):"";
					}
					}else $err="Invalid date";
					}else $err="Invalid date";					
				}else $err="No competition date specified";
				}else $err="No description";
				}else $err="No category name specified";
			}else if($competition==0){
				$body.="<table width=100% ><tr><td valign=top align=center ><div id=ccomp >";
			}
			
$body.=$competition==0?"<h2 align=center >Add competition</h2>":"";

			$body.="<form action='javascript:".($competition!=0?"getUri(\"ajax-php.php?page=admin&do=editcat&edit=".$competition."&\",\"catlistf\", \"catlist\");":"")."getUri(\"ajax-php.php?page=admin&do=edc&edit=".$competition."&eif=1&\",\"addc\", \"ccomp\");' id=addc  >$err<br /><table><tr><td align=right >
							Competition name:</td><td><input type=text name=cname value='".htmlspecialchars($name, ENT_QUOTES)."' /></td></tr><tr><td align=right valign=top >
							Date of competition:</td><td><input name='compDate' value='".$date."' ><br /><input type=button value=\"select\" onclick=\"displayDatePicker('compDate', this, 'ymd', '-');\"></td></tr><tr><td align=right >
							Description for rss:</td><td><textarea rows=2 cols=40 name='cdes'>".$desc."</textarea></td></tr><tr><td align=right >
							Enabled</td><td><input type=checkbox name=cenabled ".($enabled?"checked":"")." ></td></tr><tr><td align=right >
							Publish Rss</td><td><input type=checkbox name=cfinished ".($finished!=0?"checked":"")." ></td></tr><tr><td colspan=2 align=center >
							<input type=submit value='".($competition==0?"Add":"Update")."'>".($competition==0?"":"-------<a href='javascript:getHttp(\"ajax-php.php?page=admin&do=delcomp&dc=$competition&\",\"page\");'>Delete</a><br /><br /><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=dupcomp&dup=$competition&\",\"page\");'>Duplicate</a>")."</td></tr></table><br />
<br />
							</form><div id=lcomps >".listCompetitions()."</div>";
		if($competition==0&&!$new){
			$body.="</div></td><td valign=top ><div id=catlist ><br /><br /><br />
You need to create the competition before adding criteria</div><div id=ccat ></div></td></tr></table>";
		}
	}else if($_GET['do']=="editcat"){
			$competition=0;
			if(isset($_GET['edit'])){
				if(is_numeric($_GET['edit'])){
					$competition=$_GET['edit'];
					$que=mysql_query("select * from ".T_COMPS." where id=$competition");
					if($que)if($r=mysql_fetch_array($que)){
					}else $competition=0;
				}
			}
			if($competition==0)$body.="Error: No competition defined";
			else{
				mysql_query("delete from ".T_CCLIST." where competition=$competition");
				if(isset($_GET['slist'])){
					$err="";
					foreach($_GET['slist']	as  $k=>$v){
						if($v=="")continue;
						if(is_numeric($v)){
						if(!mysql_query("insert into ".T_CCLIST."(competition, category) values($competition, $v) on duplicate key update category=category"))$err.=mysql_error();
						}else $err.="Invalid category:$v";
					}
					if($err!="")$err="There was one or more errors in updating criteria:$err";
					$body.=listCategories($competition, $err);
				}
			}
	}else if($_GET['do']=="cat"){
				$body="";
					if(isset($_GET['adc'])){//add category				
							if(isset($_GET['catname']))$cnam=sqld($_GET['catname']);
							else $err.="Invalid name<br />";
							if(isset($_GET['mscore'])&&is_numeric($_GET['mscore']))$min=sqld($_GET['mscore']);
							else $err.="Invalid min score<br />";
							if(isset($_GET['mxscore'])&&is_numeric($_GET['mxscore']))$max=sqld($_GET['mxscore']);
							else $err.="Invalid max score<br />";
						if(!isset($err)){
							if(mysql_query("insert into ".T_CATS."(name, min, max) values('$cnam', '$min', '$max')")){
								$body="Category added!<br />";
								$body.=createCategoryForm(0,"", $min,$max);
							}else $body="Error:"+mysql_error()."<br />".createCategoryForm();
						}else{
							$body="Error:$err<br />".createCategoryForm();
						}
					}else if(isset($_GET['edc'])){//editing category	
					$edit=$_GET['edc'];
					if(is_numeric($edit)){	
						if(isset($_GET['d'])){
							if(isset($_GET['catname']))$cnam=sqld($_GET['catname']);
							else $err.="Invalid name<br />";
							if(isset($_GET['mscore'])&&is_numeric($_GET['mscore']))$min=sqld($_GET['mscore']);
							else $err.="Invalid min score<br />";
							if(isset($_GET['mxscore'])&&is_numeric($_GET['mxscore']))$max=sqld($_GET['mxscore']);
							else $err.="Invalid max score<br />";
							if(!isset($err)){	
								if(mysql_query("update ".T_CATS." set  name='$cnam', min='$min', max='$max' where id='$edit'")){
									$body="Category edited!<br />";
									$body.=createCategoryForm(0, "", $min, $max);
								}else $body="Error:"+mysql_error();
													}else{
							$body="Error:$err<br />".createCategoryForm();
						}
							}else{
								$que=mysql_query("select * from ".T_CATS." where id=$edit");
								if($que)if($r=mysql_fetch_array($que)){
									$body.=createCategoryForm($edit, dsql($r['name']),$r['min'], $r['max']);
								}else $body.="Category doesn't exist!<br />".createCategoryForm();
							}					
							}else $body.="Category doesn't exist!<br />".createCategoryForm();
					
					}else if(isset($_GET['dc'])){
						if(is_numeric($_GET['dc'])){
							if(isset($_GET['sure'])){
								$id=$_GET['dc'];
							$body="";
							$que=mysql_query("delete from ".T_CATS." where id='$id' limit 1");
							$que=$que&&mysql_query("delete from ".T_CCLIST." where category=$id");
							$que=$que&&mysql_query("delete from ".T_SCORES." where category=$id");
							
							
								if($que){
								$body.="Category deleted<br />you need to refresh list [ie:click name]<br />";			
								$body.=createCategoryForm();
								}else $body.="There was an error deleting:  ".mysql_error()."<br />".createCategoryForm();
							}else{
								$que=mysql_query("select name from ".T_CATS." where id=".$_GET['dc']);
								if($que)if($r=mysql_fetch_array($que)){
									$name=$r['name'];								
									$body.="Are you sure you want to delete $name?<br />(Will also delete existing scores in category)<br />";
									$body.="<a href='javascript:getHttp(\"ajax-php.php?page=admin&do=cat&dc=".$_GET['dc']."&sure=1\", \"ccat\")'>Yes</a><br /><br />
									<a href='javascript:getHttp(\"ajax-php.php?page=admin&do=cat&edc=".$_GET['dc']."\", \"ccat\")'>No</a>
";
								}else $body.="Category doesn't exist!<br />".createCategoryForm();
							}
						}
					}else{  
							$body=createCategoryForm();
					}

	}else if($_GET['do']=="listc"){//list competitions
		if(isset($_GET['enchecks'])){
			$enc=$_GET['enchecks'];
			$allok=true;
			if(is_array($enc)){
				foreach($enc as $compc=>$check){
					if(is_numeric($compc)){
						if(is_array($check)&&sizeof($check)==2){
							$allok=$allok&&mysql_unbuffered_query("update ".T_COMPS." set enabled='".($check[0]=="on"?1:0)."', finished='".($check[1]=="on"?time():0)."' where id=$compc");
						}
					}
				}
			}
			if($allok)$body.="Checks updated";
			else $body.="There was an error updating one or more checks";
		}
		$body.=listCompetitions();
	}else if($_GET['do']=="listcat"){
			$c=$_GET['cat'];
			if(!is_numeric($c))$c=0;
		$body.=listCategories($c);
	}else if($_GET['do']=="del"){
		if(isset($_GET['uid'])){
			$u=addslashes($_GET['uid']);
			if(is_numeric($u)){
				$hasscores=0;
			if($userInfo['id']==$u)$body="<h2>You can not delete yourself</h2>";
			else if(isset($_GET['verify'])){
				if(mysql_query("delete from ".T_USERS." where id='$u' limit 1")&&mysql_query("delete from ".T_SCORES." where judge='$u' "))$body="<h1>User deleted</h1>".createEditUserForm().listUsers();
				else $body="Error deleting user".mysql_error();
			}else{
				$q=mysql_query("select username from ".T_USERS." where id='$u'");
				$que=mysql_query("select * from ".T_SCORES." where judge='$u'");
				if($que)if($r=mysql_fetch_array($que)){
					$hasscores=1;
				}
				if($z=mysql_fetch_array($q)){
				$body="<h2>Are you sure you want to delete ".$z['username']."?</h2>
				".($hasscores?"(Also will delete scores)<br />":"")."
				<a href='javascript:getHttp(\"ajax-php.php?page=admin&do=del&uid=$u&verify=1\", \"page\")'>Yes</a><br />
<br />
<a href='javascript:getHttp(\"ajax-php.php?page=admin\", \"page\")'>No</a>";
				}
			}
						  }
		}
	}
}
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function createCompetition($name, $date, $enab, $desc, $fin){
mysql_unbuffered_query("insert into ".T_COMPS."(name, day, enabled, description, finished) values('$name', '$date', '$enab', '$desc', $fin)");
if(mysql_errno()!=0)return "An Error occurred ".mysql_error();
else return mysql_insert_id();
}
function editCompetition($id, $name, $date, $enab, $desc, $fin){
mysql_unbuffered_query("update ".T_COMPS." set name='$name', day='$date', enabled='$enab', description='$desc', finished=$fin where id=$id");
if(mysql_errno()!=0)return "An Error occurred ".mysql_error();
else return "";
}
function addUser($username,$name, $contact, $password,$privs){
	$check=mysql_query("select * from ".T_USERS." where username ='".addslashes($username)."'");
	if($check)if(mysql_num_rows($check)>0)return false;
	return mysql_query("insert into ".T_USERS."(name,username,contact,password,privs) values ('".addslashes($name)."','".addslashes($username)."', '".addslashes($contact)."', '".addslashes(md5($password))."', $privs )");

}
function editUser($username,$name, $contact, $password,$privs,$oldu){
if(!is_numeric($oldu))return;

	$check=mysql_query("select * from ".T_USERS." where username = '".addslashes($username)."' and id!='".addslashes($oldu)."'");

	if($check)if(mysql_num_rows($check)>0)return false;

if($password!="")	return mysql_query("update ".T_USERS." set name='".addslashes($name)."',username='".addslashes($username)."',contact='".addslashes($contact)."', password='".addslashes(md5($password))."', privs=$privs where id='".addslashes($oldu)."'");
else	return mysql_query("update ".T_USERS." set name='".addslashes($name)."',username='".addslashes($username)."',contact='".addslashes($contact)."', privs=$privs where id='".addslashes($oldu)."'");

}
function createEditUserForm($name="", $login="",$contact="", $privs=0,$err="", $uid=0){
	$m=0;$j=0;$r=0;$a=0;
	if($privs>=8){
		$m=1;
		$privs-=8;
	}
	if($privs>=4){
		$j=1;
		$privs-=4;
	}
	if($privs>=2){
		$r=1;
		$privs-=2;
	}	
	if($privs){
		$a=1;
	}
	
return ($uid==0?"<h2>Add user</h2>":"<h2>Edit user $name</h2>").($err!=""?"<font color=red >$err</font>":"")."<form action='javascript:getUri(\"ajax-php.php?page=admin&do=edu&d=1&userR=$uid&\", \"userForm\", \"page\")' id=userForm >
				<table><tr>
				<td align=right >Name:</td><td><input type=text name=name value='$name' /></td></tr><tr>
				<td align=right >Login:</td><td><input type=text name=login value='$login' /></td></tr><tr>
				<td align=right >Contact:</td><td><input type=text name=contact value='$contact' /></td></tr><tr>
				<td align=right >Password:</td><td><input type=password name=p1 /></td></tr><tr>
				<td align=right >Password again:</td><td><input type=password name=p2 /></td></tr><tr>
				<td align=center colspan=2 >Privileges</td></tr><tr>
				<td align=right >Admin:</td><td><input type=checkbox name=privs[] value=a ".($a?"checked=checked ":"")."/></td></tr><tr>
				<td align=right >MC:</td><td><input type=checkbox name=privs[] value=m ".($m?"checked=checked ":"")." /></td></tr><tr>
				<td align=right >Registration:</td><td><input type=checkbox name=privs[] value=r ".($r?"checked=checked ":"")."/></td></tr><tr>

				<td align=right >Judge:</td><td><input type=checkbox name=privs[] value=j ".($j?"checked=checked ":"")." /></td></tr><tr>
				<td align=center colspan=2 >
				<input type=submit value='".($uid==0?"Add":"Update"). " user' /></td></tr></table></form>";
}
function listUsers(){
global $userInfo; 
$rotr="";
$order="";
$nn='nr';
$jj="jr";
$rr="rr";
$m="mm";
$aa="aa";
$order="name";
if(isset($_GET['sort'])){
switch(substr($_GET['sort'], 0, 1)){
case 'n':
$order="name";
break;
case 'j':
$order="5";
break;
case 'r':
$order="4";
break;
case 'a':
$order="3";
case 'm':
$order="6";
break;
default:
$order='name';
}
if(strlen($_GET['sort'])==2){
	$order.=" desc";
switch(substr($_GET['sort'], 0, 1)){
case 'n':
$nn='n';
break;
case 'j':
$jj='j';
break;
case 'r':
$rr='r';
break;
case 'a':
$aa='a';
case 'm':
$aa='m';
break;
default:

}	
}
}else $order="name desc";
$rs=mysql_query("select name, id, case when (privs mod 2)=1 then 1 else 0 end 'admin', case when privs in(2,3,6,7,10,11,14,15) then 1  else 0 end'reg', case when privs in(4,5,6,7,12,13,14,15) then 1 else 0 end 'judge', case when privs>7 then 1 else 0 end 'mc' from ".T_USERS." order by  ".$order);
$rotr.= "<div id=lusers ><table border=1 ><tr><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&sort=$nn\", \"lusers\")'>Name</a></td><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&sort=$aa\", \"lusers\")'>Admin</a></td><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&sort=$mm\", \"lusers\")'>MC</a></td><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&sort=$rr\", \"lusers\")'>Registration</a></td><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&sort=$jj\", \"lusers\")'>Judge</a></td><td>&nbsp;</td></tr>";
if($rs)while($r=mysql_fetch_array($rs)){
$rotr.="<tr><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=edu&userR=".$r['id']."&\", \"page\")'>".$r['name']."</a></td>";
$rotr.=$r['admin']?"<td bgcolor='#00ff00'>&nbsp;</td>":"<td bgcolor='#ff0000'>&nbsp;</td>";
$rotr.=$r['mc']?"<td bgcolor='#00ff00'>&nbsp;</td>":"<td bgcolor='#ff0000'>&nbsp;</td>";
$rotr.=$r['reg']?"<td bgcolor='#00ff00'>&nbsp;</td>":"<td bgcolor='#ff0000'>&nbsp;</td>";
$rotr.=$r['judge']?"<td bgcolor='#00ff00'>&nbsp;</td>":"<td bgcolor='#ff0000'>&nbsp;</td>";
$rotr.="<td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=del&uid=".$r['id']."\", \"page\")'>Delete</a></td>";
$rotr.="</tr>";
}
return $rotr."</table></div>";
}
function createCategoryForm($id=0, $cname="", $cmin="", $cmax=""){						
	$return="<h2>".($id==0?"Add":"Edit")." criteria</h2><form action='javascript:getUri(\"ajax-php.php?page=admin&do=cat&".($id==0?"adc=":"d=1&edc=")."$id&\",\"addca\", \"ccat\");getUri(\"ajax-php.php?page=admin&do=listcat&\",\"catlistf\", \"catlist\");' id=addca >
						<table border=1><tr><td>Name </td><td> <input type=text name=catname value='".htmlentities($cname,ENT_QUOTES)."' ></td></tr>
						<tr><td>Min score </td><td> <input type=text name=mscore value='".$cmin."' ></td></tr>
						<tr><td>Max score </td><td> <input type=text name=mxscore value='".$cmax."' ></td></tr>
						  <tr><td colspan=2 align=center ><input type=submit value='".($id==0?"Add":"Update")."' >".($id==0?"":"-------<a href='javascript:getHttp(\"ajax-php.php?page=admin&do=cat&dc=$id\", \"ccat\");'>Delete</a>")."</td></tr></table>";
						$return.="</table>";
						return $return;
}
function listCategories($comp=0, $message=""){
		$she=array("n"=>"n", "x"=>"x", "m"=>"m");
		if(isset($_GET['sort'])){
		$s=$_GET['sort'];
		if(strlen($s)==1){
		if(in_array($s, $she)){
			$she[$s].=$s;
		}
		}
		}
		$ret="<h2>Current criteria</h2>$message<form id=catlistf ><table border=1 ><tr><td>&nbsp;</td><th><a href='javascript:getUri(\"ajax-php.php?page=admin&do=listcat&cat=".$comp."&sort=".$she['n']."&\",\"catlistf\", \"catlist\")'>Name</a></th><th><a href='javascript:getUri(\"ajax-php.php?page=admin&do=listcat&cat=".$comp."&sort=".$she['m']."&\",\"catlistf\", \"catlist\")'>Min</a></th><th><a href='javascript:getUri(\"ajax-php.php?page=admin&do=listcat&cat=".$comp."&sort=".$she['x']."&\",\"catlistf\", \"catlist\")'>Max</a></th></tr>";
			$list=array();	
			if($comp!=0&&is_numeric(0)){
				$que=mysql_query("select category from ".T_CCLIST." where competition=$comp");
				if($que)while($r=mysql_fetch_array($que)){
					$list[]=$r['category'];
				}
			}
		$sorts=array("n"=>"order by name", "nn"=>"order by name desc", "m"=>"order by min", "mm"=>"order by min desc", "x"=>"order by max", "xx"=>"order by max desc");
			$sort="";
			if(in_array($s, array_keys($sorts)))$sort=$sorts[$s];
		$que=mysql_query("select * from ".T_CATS." $sort");
		
		if($que)while($r=mysql_fetch_array($que)){
			$ret.="<tr><td><input type=checkbox name='slist[]' value='".$r['id']."' ".(in_array($r['id'],$list)?"checked":"")." ></td><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=cat&edc=".$r['id']."\", \"ccat\")'>".dsql($r['name'])."</a></td><td>".$r['min']."</td><td>".$r['max']."</td></tr>";
		}
		$ret.="</table></form>";
		
		return $ret;
	
}
function listCompetitions(){
	$n='n';$d='d';
	if(isset($_GET['sort'])){
	switch($_GET['sort']){
		case "n":
		$sort=" order by name";
		$n="nn";
		break;
		case "nn":
		$sort=" order by name desc";
		$n="n";
		break;
		case "d":
		$sort=" order by day";
		$d="dd";
		break;
		case "dd":
		$sort=" order by day desc";
		$d="d";
	}
	}
		$list=mysql_query("select id, name, day, enabled, finished from ".T_COMPS. " $sort");
				$ret.="<h2>Current competitions</h2><form action='javascript:getUri(\"ajax-php.php?page=admin&do=listc&\",\"checkform\", \"lcomps\")' id=checkform ><input type=submit value='Update checks' ><table border=1 ><tr><th><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=listc&sort=$n\", \"lcomps\")'>Name</a></th><th><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=listc&sort=$d\", \"lcomps\")'>Date</a></th><th>Enabled</th><th>Rss</th></tr>";
				if(mysql_num_rows($list)>0)while($com=mysql_fetch_array($list)){
					$ret.= "<tr><td><a href='javascript:getHttp(\"ajax-php.php?page=admin&do=listcat&cat=".$com['id']."\", \"catlist\");getHttp(\"ajax-php.php?page=admin&do=cat\", \"ccat\");getHttp(\"ajax-php.php?page=admin&do=edc&edit=".$com['id']."\", \"ccomp\");'>".dsql($com['name'])."</a></td><td>".$com['day']."</ td><td><input type=checkbox ".($com['enabled']?"checked":"")." name='enchecks[".$com['id']."][0]' /></td><td><input type=checkbox ".($com['finished']?"checked":"")." name='enchecks[".$com['id']."][1]' /></td></tr>";	
					}
else $ret.= "<tr><td colspan=4 >No competitions</td></tr>";
$ret.="</table><input type=submit value='Update checks' ></form>";
			return $ret;
}

?>