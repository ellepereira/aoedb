<br />
<div class="tooltip" style="margin-left: auto; margin-right: auto; width: 600px">
<div class="inside">

<h1>User Registration</h1>
<form name="register" action="/aoeo/register" method="POST">
<fieldset class="reg">
<legend>IMPORTANT BITS</legend>
<table>
<tr> <td> <label>User name: </label></td><td> <input type="text" name="username" id="username" placeholder="Username" required /> <br /> <span class="reqReg">Required. Your user name, doh. Recommend using your gamertag.</span></td></tr>
<tr> <td><label>Email:</label></td><td> <input type="email" name="email" id="email" placeholder="Email address" required/> <br /><span class="reqReg">Required. Please use a valid email.</span></td></tr>
<tr> <td style="width: 180px"><label>Password: </label></td><td> <input type="password" name="password" id="password" required/> <br /><span class="reqReg">Required. Anything longer than 6 characters.</span></td></tr>
</table>
</fieldset>
<br />
<fieldset id="auctionreg" class="reg">
<legend>AUCTION HOUSE</legend>
<table>
<tr> <td style="width: 180px"> <label>Gamertag</label></td><td> <input type="text" name="gamertag" id="gamertag" placeholder="Gamer Tag"/> <br /><span class="reqReg">Type it CORRECTLY. Typos will result in you not getting items.</span></td></tr>
<tr> <td><label>Preferred Server: </label><br /></td>
<td> <select name="prefserver">
<option value="marathon">Marathon</option>
<option value="athens">Athens</option>
<option value="alexandria">Alexandria</option>
<option value="attica">Attica</option>
<option value="corinth">Corinth</option>
<option value="delphi">Delphi</option>
<option value="gesa">Gesa</option>
<option value="heraction">Heraction</option>
<option value="abydos">Abydos</option>
<option value="apollonia">Apollonia</option>
<option value="arcadia">Arcadia</option>
<option value="argos">Argos</option>
<option value="ithaca">Ithaca</option>
<option value="khnum">Khnum</option>
</select>
</td></tr>
<tr><td colspan="2"><input type="checkbox" id="emailopt" name="emailopt" checked/> <label>Email OPT-IN!</label> - <span class="reqReg">It's OKAY to email me (when I win, etc).</span></td></tr>
</table>
<p>PLEASE NOTE: I am not responsible if you somehow get scammed selling/buying items using this website. Use at your own peril. Please understand this is a service I am providing as a THIRD party and do NOT have ways to enforce anything in-game.</p>
</fieldset>

<br />
<input type="submit" value="Register" />
</form>

</div>
<?php make_tooltip(); ?>
</div>
