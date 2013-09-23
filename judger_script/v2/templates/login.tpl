<center><br />
<br />

    <h2>Login to  {{$competitionTitle}}</h2>
    <font color="red">{{$user_loginerror}}</font>
    <form method="post" name="login" action="" >
			<input type="text" onfocus="if (this.value == 'Username') { this.value = ''; }" value="Username" style="margin-top:10px;border:1px solid black;" name="loginusername" /><br />
			<input type="password" onfocus="if (this.value == 'Password') { this.value = ''; }" style="margin-top:10px;border:1px solid black;" value="Password" name="loginpassword" /><br />
           <input type="checkbox" name="loginremember" />Remember me<br />
			{{if $user_requirecaptcha eq 1 }}
            <img id="logincaptcha" src="/securimage/securimage_show.php" alt="CAPTCHA Image" /><br>
    <input type="text" name="captcha_code" size="10" maxlength="6" /><br>
    <a href="#" onclick="document.getElementById('logincaptcha').src = '/securimage/securimage_show.php?' + Math.random(); return false">Reload Image</a><br>
    		{{/if}}
			{{if $demo eq 1}}<br />
			Default login: admin/admin<br />
			<i>Note: Demo will reset every 10 minutes</i><br /><br />
			{{/if}}
			<input type="submit"  value="Login" class="submit" />
		</form><br />
<br />
<br />
<br />
<br />
<br />

</center>