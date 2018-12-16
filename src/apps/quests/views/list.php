<br />

<h1>Quests</h1>
<p>Type: 
<a href="/designs/type/material">Materials</a> | 
<a href="/designs/type/trait">Items</a> | 
<a href="/designs/type/consumable">Consumables</a> | 
</p>



<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Name</th><th>Civ/Booster</th><th>Type</th><th>Description</th>
</tr>

<?php foreach($data as $item): ?>
<tr onClick="document.location.href='/quests/<?=$item['uniqueid']?>';">
<td><?=$item['DisplayName']?></td>
<td><?=$item['questline']?></td>
<td><?=$item['type']?></td>
<td><?=$item['Description']?></td>

</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<? make_tooltip(); ?>


</div>