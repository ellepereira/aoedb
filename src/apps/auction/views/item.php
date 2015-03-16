<script>
	dbid = <?=$data['item']['dbid']?>;
	rlevel = <?=$data['item']['level']?>;
	$(function() {
		 minimizeItem(dbid,rlevel);
	});
</script>

<style>
#popup input[type="text"]
{
	width: 80px;
}
</style>

<div id="itemContainer"></div>
<br />

<form>
<table>
<tr><td><label>Starting Price: </label></td>
<td><input type="text"></input></td>
</tr>
<tr>
<td><label>List for: </label></td>
<td><input type="text"></input><select><option>day(s)</option><option>hour(s)</option><option>week(s)</option></select> </td>
</tr>
<tr>
<td><input type="checkbox"></input><label>Buyout Price </label></td>
<td><input type="text"></input> </td>
</tr>
<tr>
<td colspan="2">
<input type="button" style="width: 300px" value="List"></input>
</td>
</tr>
</table>
</form>
