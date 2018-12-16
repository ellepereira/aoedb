<?php
$rarities = array('cRarityCommon' => 'common',
    'cRarityUncommon' => 'uncommon',
    'cRarityRare' => 'rare',
    'cRarityEpic' => 'epic');
$data['rarity'] = $rarities[$data['rarity']];
if (array_key_exists($data['output']['rarity'], $rarities)) {
    $data['output']['rarity'] = $rarities[$data['output']['rarity']];
}

$outputtypes = array('trait' => 'Item',
    'material' => 'Material',
    'consumable' => 'Consumable');

switch ($data['outputtype']) {
    case 'trait':
        $level = $data['outputtraitlevel'] - 3;
        $outputlink = "/traits/{$data['output']['dbid']}/$level";
        break;
    case 'material':
        $outputlink = "/materials/{$data['output']['name']}";
        break;
    case 'consumable':
        $outputlink = "/consumables/{$data['output']['name']}";
        break;
    default:
        $outputlink = '';
}

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
$schoolstr = '';
if (isset($data['tag']) && array_key_exists($data['tag'], $tags)) {
    $schoolstr = "<p>Used at: {$tags[$data['tag']]}</p>"

    ;
}
?>

<br />
<div style="float:left">

<div class="tooltip" style="width: 300px;">
<div class="inside">
  <div class="header">
    <div class="type"><?=$outputtypes[$data['outputtype']]?> Design</div>
    <div class="rarity"><span class="<?=$data['rarity']?>"><?=$data['rarity']?></span></div>
  </div>
  <br><br>
  <img class="icon" src="/images/Art/<?=$data['icon']?>.png"> <span class="name <?=$data['rarity']?>"><?=$data['output']['displayname']?></span>
  <div class="info">
    <div class="description">
      <p><?=$data['rollovertext']?></p>
      <?=$schoolstr?>
      <p>Creates: <a href="<?=$outputlink?>" style="text-decoration: none"><span class="<?=$data['output']['rarity']?>rarity"><?=$data['output']['displayname']?></span></a></p>
     <?php if (isset($data['materials'])) {?>
      <br>
      Required Materials:<br>
	    <?php foreach ($data['materials'] as $material) {
    echo "<img src='/images/Art/{$material['icon']}.png' height='32'> {$material['count']}x <a href='/materials/{$material['name']}' style='text-decoration: none'><span class='itemname {$rarities[$material['rarity']]}rarity'>{$material['displayname']}</span></a><br>";
}?>
	  <?php } else {
    echo "<br>No materials required for construction.";
}?>
    </div>
      <br>
  </div>
  <br>
  dbid: <a href="/designs/<?=$data['name']?>"><?=$data['name']?></a> | <span class="scriptlink" onClick="xmldiag(this, 'econdesign', '<?=$data['name']?>')">[xml]</span>
</div>
<?php make_tooltip();?>
</div>

</div>