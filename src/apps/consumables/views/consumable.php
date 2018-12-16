<?php
$rarities = array('cRarityCommon' => 'common',
                  'cRarityUncommon' => 'uncommon',
                  'cRarityRare' => 'rare',
                  'cRarityEpic' => 'epic');
$data['rarity'] = $rarities[$data['rarity']];


?>

<br />
<div style="float:left">

<div class="tooltip" style="width: 300px;">
<div class="inside">
  <div class="header">
    <div class="type">Consumable</div>
    <div class="rarity"><span class="<?=$data['rarity']?>"><?=$data['rarity']?></span></div>
  </div>
  <br><br>
  <img class="icon" src="https://images.projectceleste.com/Art/<?=$data['icon']?>.png"> <span class="name <?=$data['rarity']?>"><?=$data['displayname']?></span>
  <div class="info">
    <div class="description">
      <p><?=$data['rollovertext']?></p>
      <p>Sells for: <?php echo floor($data['cost']); ?> <img src="https://images.projectceleste.com/Art/UserInterface/CapCity/Coin_ua.png" height="16"> (<? echo floor($data['cost'] * $data['stacksize']); ?> per stack of <?=$data['stacksize']?>)</p>
  	</div>
  </div>

  <br>
  dbid: <a href="/consumables/<?=$data['name']?>"><?=$data['name']?></a> | <span class="scriptlink" onClick="xmldiag(this, 'consumable', '<?=$data['name']?>')">[xml]</span>
</div>
<?php make_tooltip(); ?>
</div>

</div>