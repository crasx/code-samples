<?php /* Smarty version 2.6.25, created on 2010-04-19 05:07:58
         compiled from login.tpl */ ?>
<br />
<br />
<div id="login" class="round white center">
	<?php if ($this->_tpl_vars['user']->isValid()): ?>
    Logged in!<br />
    <a href="/submit">Submit</a><br />
    <a href="/user/drafts.php">Drafts</a><br />
	<a href="/users/<?php echo $this->_tpl_vars['username_url']; ?>
">Your haiku</a><br />
	<a href="/favorite/authors/<?php echo $this->_tpl_vars['username_url']; ?>
">Favorite authors</a><br />
	<a href="/favorite/haiku/<?php echo $this->_tpl_vars['username_url']; ?>
">Favorite haiku</a><br />
	    -<br />
	<a href="/user/profile.php">Profile</a><br />
	<a href="?logout=1">Logout</a>
    <?php else: ?>
    <font color="red"><?php echo $this->_tpl_vars['user_loginerror']; ?>
</font>
    <form method="post" name="login" action="" >
			<input type="text" onfocus="if (this.value == 'Username') { this.value = ''; }" value="Username" name="haiku_loginusername" />
			<input type="password" onfocus="if (this.value == 'Password') { this.value = ''; }" value="Password" name="haiku_loginpassword" />
           
			<?php if ($this->_tpl_vars['user_attempted'] == 1): ?>
            <img id="logincaptcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
    <input type="text" name="captcha_code" size="10" maxlength="6" /><br>
    <a href="#" onclick="document.getElementById('logincaptcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">Reload Image</a><br>
    		<?php endif; ?>
			<input type="submit"  value="Login" class="submit" /><br />
			<a href="/user/register.php"><b>Sign up</b></a> || <a href="/user/password.php">Password</a>
		</form>
     <?php endif; ?>
</div>