<?
$rarities = array('cRarityCommon' => 'common',
                  'cRarityUncommon' => 'uncommon',
                  'cRarityRare' => 'rare',
                  'cRarityEpic' => 'epic');
?>



<br>
<h1>Blueprints:</h1>
<p>
Rarity: <a href="/blueprints/rarity/cRarityCommon">Common</a> | 
<a href="/blueprints/rarity/cRarityUncommon">Uncommon</a> | 
<a href="/blueprints/rarity/cRarityRare">Rare</a> | 
<a href="/blueprints/rarity/cRarityEpic">Epic</a> 
</p>


<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Icon</th><th>Name</th><th>Creates</th><th>Rarity</th><th>Store Value</th>
</tr>

<?php foreach($data as $item): ?>
<tr onClick="document.location.href='/blueprints/<?=$item['DBID']?>';">
<td><img class="pic" src="/images/Art/<?=$item['icon']?>.png" width="45px"/></td>
<td><a href="/blueprints/<?=$item['DBID']?>" ><?=$item['displayname']?></a></td>
<td><a href="/units/<?=$item['DBID']?>"><?=$item['displayname']?></a></td>
<td><span class="<?=$rarities[$item['rarity']]?>"><?=$rarities[$item['rarity']]?></span></td>
<td> <?=$item['cost']?> <img src="/images/Art/UserInterface/CapCity/Coin_ua.png" height="16"></td>
</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<? make_tooltip(); ?>

</div>