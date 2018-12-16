<br />
<div class="tooltip" style="width: 600px; margin-left: auto; margin-right: auto;">
<div class="inside">
<h1>Login</h1>
<form id="login" name="login" action="/aoeo/login" method="POST">
<fieldset>
<legend>USER INFORMATION</legend>
<table>
<tr>
<td><label>User Name: </label></td> <td><input type="text" name="uname" id="uname" placeholder="User name" required /> </td>
<tr>
<td><label>Password: </label></td><td><input type="password" name="password" id="password" required/> </td></tr>
</table>
</fieldset>
<br />
<input type="submit" value="Submit" />
</form>
<p>We don't have a "forgot your password" fancy shmancy thingie yet - so just email us if you do forget your password. Actually, try not forgetting it. Yeah, that usually works. Oh and we don't keep 
plaintext passwords so you'll have to make a new one.</p>
</div>
<?php make_tooltip(); ?>
</div>