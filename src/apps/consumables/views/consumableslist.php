<?
$rarities = array('cRarityCommon' => 'common',
                  'cRarityUncommon' => 'uncommon',
                  'cRarityRare' => 'rare',
                  'cRarityEpic' => 'epic');

$consumables = array();

foreach ($data as $k=>$n)
{
	if(is_file("images/Art/{$n['icon']}.png"))
	{
		$consumables[$n['displayname']] = $n;
	}
}

?>

<h1>Consumables:</h1>
<p>
Rarity: <a href="/consumables/rarity/cRarityCommon">Common</a> | 
<a href="/consumables/rarity/cRarityUncommon">Uncommon</a> | 
<a href="/consumables/rarity/cRarityRare">Rare</a> | 
<a href="/consumables/rarity/cRarityEpic">Epic</a> 
</p>


<br>
<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Icon</th><th>Name</th><th>Rarity</th><th>Stack Size</th><th>Store Value</th>
</tr>

<?php foreach($consumables as $item): ?>
<tr onClick="document.location.href='/consumables/<?=$item['name']?>';">
<td><img class="pic" src="/images/Art/<?=$item['icon']?>.png" width="45px"/></td>
<td><a href="/consumables/<?=$item['name']?>" ><?=$item['displayname']?></a></td>
<td><span class="<?=$rarities[$item['rarity']]?>"><?=$rarities[$item['rarity']]?></span></td>
<td> <?=$item['stacksize']?></td>
<td> <?=$item['cost']?> <img src="/images/Art/UserInterface/CapCity/Coin_ua.png" height="16"></td>
</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<? make_tooltip(); ?>

</div>