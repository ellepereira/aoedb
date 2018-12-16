<?php
$rarities = array('cRarityCommon' => 'common',
                  'cRarityUncommon' => 'uncommon',
                  'cRarityRare' => 'rare',
                  'cRarityEpic' => 'epic');
?>



<br>
<h1>Materials:</h1>
<p>
Rarity: <a href="/materials/rarity/cRarityCommon">Common</a> |
<a href="/materials/rarity/cRarityUncommon">Uncommon</a> |
<a href="/materials/rarity/cRarityRare">Rare</a> |
<a href="/materials/rarity/cRarityEpic">Epic</a>
</p>

<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Icon</th><th>Name</th><th>Rarity</th><th>Tradeable</th><th>Store Value (x100)</th>
</tr>

<?php foreach ($data as $item): ?>
<tr onClick="document.location.href='/materials/<?=$item['name']?>';">
<td><img class="pic" src="/images/Art/<?=$item['icon']?>.png" width="45px"/></td>
<td><a href="/materials/<?=$item['name']?>" ><?=$item['displayname']?></a></td>
<td><span class="<?=$rarities[$item['rarity']]?>"><?=$rarities[$item['rarity']]?></span></td>
<td> <?=$item['tradeable'] ? 'True' : 'False';?></td>
<td> <?=(100 * $item['cost'])?> <img src="/images/Art/UserInterface/CapCity/Coin_ua.png" height="16"></td>
</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<?php make_tooltip(); ?>

</div>