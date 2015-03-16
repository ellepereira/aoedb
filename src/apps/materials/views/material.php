<?
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
    <div class="type">Material</div>
    <div class="rarity"><span class="<?=$data['rarity']?>"><?=$data['rarity']?></span></div>
  </div>
  <br><br>
  <img class="icon" src="/images/Art/<?=$data['icon']?>.png"> <span class="name <?=$data['rarity']?>"><?=$data['displayname']?></span>
  <div class="info">
    <div class="description">
      <p><?=$data['rollovertext']?></p>
      <p>Sells for: <? echo floor($data['cost']); ?> <img src="/images/Art/UserInterface/CapCity/Coin_ua.png" height="16"> (<? echo floor($data['cost'] * $data['stacksize']); ?> per stack of <?=$data['stacksize']?>)</p>
    
     <? if (count($data['blueprints']) > 0) { ?>
      <p>Required for the following blueprints:</p>
      <ul class="itembonuses">
      <? foreach ($data['blueprints'] as $blueprint) {
        $rarity = $rarities[$blueprint['rarity']];
        echo "<li><a href='/blueprints/{$blueprint['dbid']}' style='text-decoration: none'><span class='{$rarity}'>{$blueprint['displayname']}</span></a>";
        if (array_key_exists($blueprint['offertype'], $offertypes))
          echo " ({$offertypes[$blueprint['offertype']]})";

        echo "</li>";
      } ?>
      </ul>
      <? } else { ?>
      <p>Not required for any blueprint.</p>
    <? } ?>
    
    <? if (count($data['designs_traits']) > 0) { ?>
    <p>Required for the following items:</p>
    <ul class="itembonuses">
    <? foreach ($data['designs_traits'] as $design) {
      $rarity = $rarities[$design['rarity']];
      echo "<li><a href='/designs/{$design['name']}' style='text-decoration: none'><span class='{$rarity}'>{$design['displayname']}</span></a>";

      echo "</li>";
    } ?>
    </ul>
    <? } else { ?>
      <p>Not required for any items.</p>
    <? } ?>
    
    <? if (count($data['designs_consumables']) > 0) { ?>
    <p>Required for the following consumables:</p>
    <ul class="itembonuses">
    <? foreach ($data['designs_consumables'] as $design) {
      $rarity = $rarities[$design['rarity']];
      echo "<li><a href='/designs/{$design['name']}' style='text-decoration: none'><span class='{$rarity}'>{$design['displayname']}</span></a>";

      echo "</li>";
    } ?>
    </ul>
    <? } else { ?>
      <p>Not required for any consumables.</p>
    <? } ?>   
    </div>
  </div>
 
  <br>
  dbid: <a href="/materials/<?=$data['name']?>"><?=$data['name']?></a> | <span class="scriptlink" onClick="xmldiag(this, 'material', '<?=$data['name']?>')">[xml]</span>
</div>
<? make_tooltip(); ?>
</div>

</div>
