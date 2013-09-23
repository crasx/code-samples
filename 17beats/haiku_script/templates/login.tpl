<br />
<br />
<div id="login" class="round white center">
	{{{if $user->isValid()}}}
    Logged in!<br />
    <a href="/submit">Submit</a><br />
    <a href="/user/drafts.php">Drafts</a><br />
	<a href="/users/{{{$username_url}}}">Your haiku</a><br />
	<a href="/favorite/authors/{{{$username_url}}}">Favorite authors</a><br />
	<a href="/favorite/haiku/{{{$username_url}}}">Favorite haiku</a><br />
	    -<br />
	<a href="/user/profile.php">Profile</a><br />
	<a href="?logout=1">Logout</a>
    {{{else}}}
    <font color="red">{{{$user_loginerror}}}</font>
    <form method="post" name="login" action="" >
			<input type="text" onfocus="if (this.value == 'Username') { this.value = ''; }" value="Username" name="haiku_loginusername" />
			<input type="password" onfocus="if (this.value == 'Password') { this.value = ''; }" value="Password" name="haiku_loginpassword" />
           
			{{{if $user_attempted eq 1 }}}
            <img id="logincaptcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
    <input type="text" name="captcha_code" size="10" maxlength="6" /><br>
    <a href="#" onclick="document.getElementById('logincaptcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">Reload Image</a><br>
    		{{{/if}}}
			<input type="submit"  value="Login" class="submit" /><br />
			<a href="/user/register.php"><b>Sign up</b></a> || <a href="/user/password.php">Password</a>
		</form>
     {{{/if}}}
</div>