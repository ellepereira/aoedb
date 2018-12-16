<?php
$advisors = array();
foreach ($data['advisors'] as $k => $n) {
    // if(is_file("images/Art/{$n['icon']}.png"))
    // {
    $advisors[$n['displayname']] = $n;
    //}
}
?>

<h1><?=ucfirst($data['rarity'])?> Advisors:</h1>
<p>Age: <a href="/advisors/age/1/<?=$data['rarity']?>">Copper Age (I)</a> |
<a href="/advisors/age/2/<?=$data['rarity']?>">Bronze Age (II)</a> |
<a href="/advisors/age/3/<?=$data['rarity']?>">Silver Age (III)</a> |
<a href="/advisors/age/4/<?=$data['rarity']?>">Golden Age (IV)</a>
<br />
Rarity: <a href="/advisors/age/<?=$data['age']?>/common">Common</a> | <a href="/advisors/age/<?=$data['age']?>/uncommon">Uncommon</a> | <a href="/advisors/age/<?=$data['age']?>/rare">Rare</a> |
<a href="/advisors/age/<?=$data['age']?>/epic">Epic</a> | <a href="/advisors/age/<?=$data['age']?>/legendary">Legendary</a></p>


<br />

<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
	<tr>
	<th>Icon</th><th>Name</th><th>Age</th><th>Rarity</th><th>Description</th><th>Store Value</th>
	</tr>

	<?php foreach ($advisors as $item): ?>
	<tr onClick="document.location.href='/advisors/<?=$item['name']?>';">
	<td><img class="pic" src="/images/Art/<?=$item['icon']?>.png" width="45px"/></td>
	<td><a href="/advisors/<?=$item['name']?>" ><?=$item['displayname']?></a></td>
	<td><?=($item['age'] + 1)?></td>
	<td><span class="<?=$item['rarity']?>"><?=$item['rarity']?></span></td>
	<td> <?=$item['displaydescription']?></td>
	<td> <?=$item['cost']?> <img src="/images/Art/UserInterface/CapCity/Coin_ua.png" height="16"></td>
	</tr>
	<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
	<?php endforeach;?>
</table>

<?php make_tooltip();?>


</div>