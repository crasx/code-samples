<html><head><title>crasXIT competition Installer</title></head><body>
<center><h1>Welcome to the <a href="http://crasxit.net">crasXit </a>competition installation!</h1>
<h3>This program was developed for the <a href="http://www.hellcitytattoofest.com/">Hell city tattoo festival</a><br>
Please make sure you have edited /adm/cfg.php before continuing!</h3>
<form action="process.php" method="post">
<br />
<h4>Create default login</h4>
<?php
if(isset($_GET['err'])){
if($_GET['err']==1)echo "<font color=red>Passwords didnt match!</font><br>";
if($_GET['err']==0)echo "<font color=red>Please fill out all fields!</font><br>";

}?>
<input type="hidden" name="fromI" value="Matthew Ramir" />
<label for="name">Name</label>
<input type="text" name="name" id="name" /> <br />
<label for="Login">Username</label>
<input type="text" name="username" id="Login" /> <br />
<label for="password">Password</label>
<input type="password" name="password" id="password" /> <br />
<label for="password1">Password again</label>
<input type="password" name="password1" id="password1" /> <br />
<br />
<input type="submit" value="Setup site" />
</form>
If you have already done this then delete the install folder
</center>
</body></head>