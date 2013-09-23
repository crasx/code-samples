<?php
if(!isset($_POST['fromI']))header("Location: index.php");
if(strcmp($_POST['password'],"")!=0&&strcmp($_POST['username'],"")!=0&&strcmp($_POST['name'],"")!=0){
if(strcmp($_POST['password'],$_POST['password1'])!=0)header("Location: index.php?err=1");
require("../adm/functions-sql.php");
//----------------------users
if(!mysql_query("CREATE  TABLE IF NOT EXISTS `".T_USERS."` (
   `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `username` varchar(255) default NULL,
  `contact` text NOT NULL,
  `password` varchar(255) default NULL,
  `privs` int(2) default NULL,
  PRIMARY KEY  (`id`) )"))die("ERROR CREATING ".T_USERS. " ".mysql_error());
if(!mysql_query("CREATE  TABLE IF NOT EXISTS `".T_CONTS."` (
    `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `description` text NOT NULL,
  `contact` text NOT NULL,
  `pic` blob NOT NULL,
  `type` varchar(255) NOT NULL,
  PRIMARY KEY  (`id`))"))die("ERROR CREATING ".T_CONTS. " ".mysql_error());
if(!mysql_query("CREATE  TABLE IF NOT EXISTS `".T_COMPS."` (
  `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `day` date default NULL,
  `enabled` tinyint(1) NOT NULL default '1',
  PRIMARY KEY  (`id`) ,
)"))die("ERROR CREATING ".T_COMPS. " ".mysql_error());
if(!mysql_query("CREATE  TABLE IF NOT EXISTS `".T_CATS."` (
   `id` int(11) NOT NULL auto_increment,
  `name` varchar(255) default NULL,
  `min` int(11) default NULL,
  `max` int(11) default NULL,
  PRIMARY KEY  (`id`)
  )"))die("ERROR CREATING ".T_CATS. " ".mysql_error());
if(!mysql_query("CREATE  TABLE IF NOT EXISTS ".T_SCORES." (
  `competition` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  `judge` int(11) NOT NULL,
  `contestant` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  UNIQUE KEY `competition` (`competition`,`category`,`judge`,`contestant`)
  )"))die("ERROR CREATING ".T_SCORES. " ".mysql_error());
if(!mysql_query("CREATE  TABLE IF NOT EXISTS ".T_REGS." (
   `contestant` int(11) NOT NULL,
  `competition` int(11) NOT NULL,
  `number` int(11) NOT NULL
  )"))die("ERROR CREATING ".T_REGS. " ".mysql_error());

if(!mysql_query("CREATE  TABLE IF NOT EXISTS ".T_CCLIST." (
  `competition` int(11) NOT NULL,
  `category` int(11) NOT NULL,
  UNIQUE KEY `competition` (`competition`,`category`)
  )"))die("ERROR CREATING ".T_CCLIST. " ".mysql_error());
if(!mysql_query("CREATE  TABLE IF NOT EXISTS ".T_CHECKS." (
  `competition` int(11) NOT NULL,
  `contestant` int(11) NOT NULL,
  `line` tinyint(1) NOT NULL,
  `called` tinyint(1) NOT NULL,
  PRIMARY KEY  (`competition`,`contestant`)
  )"))die("ERROR CREATING ".T_CHECKS. " ".mysql_error());



mysql_query("insert into ".T_USERS."(name,username,password,privs) values ('".addslashes($_POST['name'])."','".addslashes($_POST['username'])."',AES_ENCRYPT('".addslashes($_POST['password'])."','".AES_STRING."'),7 )")or die("Error inserting into database. Check your config ".mysql_error());

echo "<center><br /><br/>Thank you for your information<br />You can now login at <a href='".$httpRoot."'>".$httpRoot."</a> using the username and the password you set";
}else header("Location: index.php?err=0");

?><title>Processing installation</title>
