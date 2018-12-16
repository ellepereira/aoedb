<?php
$rarities = array('cRarityCommon' => 'common',
                  'cRarityUncommon' => 'uncommon',
                  'cRarityRare' => 'rare',
                  'cRarityEpic' => 'epic');
                  
$offertypes = array('eOfferCivGreek' => 'Greek',
                    'eOfferCivEgyptian' => 'Egyptian',
                    'eOfferGreekVanity1' => 'Greek Vanity',
                    'eOfferGreekVanity2' => 'Greek Vanity',
                    'eOfferEgyptianVanity1' => 'Egyptian Vanity',
                    'eOfferEgyptianVanity2' => 'Egyptian Vanity'

);

$data['rarity'] = $rarities[$data['rarity']];

$data['rollovertext'] = str_replace('\n', '<br /> <br />', $data['rollovertext']);
$data['rollovertext'] = str_replace('<color color= "1.0,1.0,0.0">', '', $data['rollovertext']);
$data['rollovertext'] = str_replace('</color>', '', $data['rollovertext']);
?>

<br />
<div style="float:left">

<div class="tooltip" style="width: 300px;">
<div class="inside">
  <div class="header">
    <div class="type"><?php
    if (array_key_exists($data['offertype'], $offertypes))
        echo " {$offertypes[$data['offertype']]} Blueprint";
     else
        echo "Blueprint";
   ?></div>
    <div class="rarity"><span class="<?=$data['rarity']?>"><?=$data['rarity']?></span></div>
  </div>
  <br><br>
  <img class="icon" src="/images/Art/<?=$data['icon']?>.png"> <span class="name <?=$data['rarity']?>"><?=$data['displayname']?></span>
  <div class="info">
    <div class="description">
      <p><?=$data['rollovertext']?></p>
      <p>Creates: <a href="/units/<?=$data['DBID']?>"><?=$data['displayname']?></a></p>
      <?php if (isset($data['materials'])) { ?>
      <br>
      Required Materials:<br>
	    <?php foreach ($data['materials'] as $material) {
	    echo "<img src='/images/Art/{$material['icon']}.png' height='32'> {$material['count']}x <a href='/materials/{$material['name']}' style='text-decoration: none'><span class='itemname {$rarities[$material['rarity']]}rarity'>{$material['displayname']}</span></a><br>";
	    }?>
	  <?php }
	  else {
	    	echo "<br>No materials required for construction."; 
	  }?>
    </div>
      <br>
  </div>
  Portrait Icon: <br>
  <img src="/images/Art/<?=$data['PortraitIcon']?>.png" width="128px">
  <br>
  <br>
  dbid: <a href="/blueprints/<?=$data['DBID']?>"><?=$data['DBID']?></a> | <span class="scriptlink" onClick="xmldiag(this, 'blueprint', '<?=$data['name']?>')">[xml]</span> | <span style="text-decoration:none"><?=$data['name']?></span>
</div>
<?php make_tooltip(); ?>
</div>

</div>

