<?php
$rarities = array('cRarityCommon' => 'common',
    'cRarityUncommon' => 'uncommon',
    'cRarityRare' => 'rare',
    'cRarityEpic' => 'epic');

$tags = array('[WoodDesigns1]' => 'Logger\'s Workshop',
    '[StoneDesigns1]' => 'Mason\'s Workshop',
    '[MetalDesigns1]' => 'Smelter\'s Workshop',
    '[LeatherDesigns1]' => 'Skinner\'s Workshop',
    '[FarmDesigns1]' => 'Farmer\'s Workshop',
    '[AlchemyDesigns1]' => 'Alchemist\'s Workshop',
    '[ToolDesigns1]' => 'Toolmaker\'s Workshop',
    '[GemDesigns1]' => 'Gem Cutter\'s Workshop',
    '[ClothDesigns1]' => 'Weaver\'s Workshop',
    '[LoreDesigns1]' => 'Scrivener\'s Workshop',
    'Religion' => 'Religion School',
    'Craftsmen' => 'Craftsmen School',
    'Engineering' => 'Engineering School',
    'Construction' => 'Construction School',
    'MilitaryCollege' => 'Infantry School',
    'HorseBreeding' => 'Cavalry School',
    'Woodscraft' => 'Woodscraft School',
    'MetalWorking' => 'Metalworking School',
);

?>
<br />

<h1>Recipes</h1>
<p>Type:
<a href="/designs/type/material">Materials</a> |
<a href="/designs/type/trait">Items</a> |
<a href="/designs/type/consumable">Consumables</a> |
</p>



<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Icon</th><th>Name</th><th>Rarity</th><th>Used at</th><th>Store Value</th>
</tr>

<?php foreach ($data as $item): ?>
<tr onClick="document.location.href='/designs/<?=$item['name']?>';">
<td><img src="/images/Art/<?=$item['icon']?>.png" width="45px"/></td>
<td><?=$item['displayname']?></td>
<td> <span class="<?=$rarities[$item['rarity']]?>"><?=$rarities[$item['rarity']]?></span></td>
<td><?=$tags[$item['tag']]?></td>
<td> <?=$item['cost']?> <img src="/images/Art/UserInterface/CapCity/Coin_ua.png" height="16"></td>
</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<?php make_tooltip(); ?>


</div>