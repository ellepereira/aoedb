<br />

<?php
$cost = '';

$typecivs = array('Qst' => 'Quest AI', 'Gr' => 'Greek', 'Eg' => 'Egyptian', 'Ba' => 'Babylonian', 'Ce' => 'Celtic', 'Pe' => 'Persian', 'Con' => 'Consumable', 'Pv' => 'Spartan', 'Mn' => 'Minoan', 'Cy' => 'Cypriot');
$typetypes = array('Inf' => 'Infantry', 'Cav' => 'Cavalry', 'Bldg' => 'Building', 'Arc' => 'Ranged Unit', 'Shp' => 'Ship', 'Sie' => 'Siege Unit', 'Civ' => 'Economic Unit', 'Spc' => 'Religious Unit', 'Cap' => 'Capital Building', 'WallStraight2' => 'Wall', 'WallConnector' => 'Wall', 'WallStraight1' => 'Wall', 'WallGate' => 'Wall', 'WallStraight5' => 'Wall');
$typestring = '';

$nameexplode = explode('_', $data['name']);

if (array_key_exists($nameexplode[0], $typecivs)) {
    $typestring .= $typecivs[$nameexplode[0]] . ' ';

    $typestring .= $typetypes[$nameexplode[1]];
} else {
    $typestring = $data['name'];
}

if (isset($data['CostFood'])) {
    $cost .= "{$data['CostFood']} <img src='/images/food.png' /> ";
}

if (isset($data['CostWood'])) {
    $cost .= "{$data['CostWood']} <img src='/images/wood.png' /> ";
}

if (isset($data['CostGold'])) {
    $cost .= "{$data['CostGold']} <img src='/images/gold.png' /> ";
}

if (isset($data['CostStone'])) {
    $cost .= "{$data['CostStone']} <img src='/images/stone.png' /> ";
}

if (isset($data['PopulationCount'])) {
    $cost .= "{$data['PopulationCount']} <img src='/images/pop.png' /> ";
}

if (strlen($cost)) {
    $cost = "<li>Cost: {$cost} </li>";
}

if (strlen($data['TrainPoints'])) {
    $buildtimestring = "<li>Training/Build Time: {$data['TrainPoints']}</li>";
} else {
    $buildtimestring = '';
}

if (strlen($data['LOS'])) {
    $losstring = "<li>Line-of-sight: {$data['LOS']}</li>";
} else {
    $losstring = '';
}

if (strlen($data['MaxVelocity']) && $data['MaxVelocity'] > 0) {
    $speedstring = "<li>Movement Speed: {$data['MaxVelocity']}</li>";
} else {
    $speedstring = '';
}

if ($nameexplode[1] != 'Cap') {
    $agenames = array('Copper Age (I)', 'Bronze Age (II)', 'Silver Age (III)', 'Golden Age (IV)');
    $agestring = $agenames[$data['AllowedAge']];
//$agestring = "Age: {$data['AllowedAge']+1}";
} else {
    $agestring = '';
}

$attackstring = '';
$armorstring = '';

$armortypes = array('ArmorRanged', 'ArmorHand', 'ArmorCavalry', 'ArmorSiege');
$armornames = array(
    'ArmorRanged' => 'Ranged Armor',
    'ArmorHand' => 'Melee-Infantry Armor',
    'ArmorCavalry' => 'Melee-Cavalry Armor',
    'ArmorSiege' => 'Crush Armor');

foreach ($armortypes as $armortype) {
    if ($data[$armortype]) {
        $armorstring .= '<li>' . $armornames[$armortype] . ': ' . round($data[$armortype] * 100) . '%</li>';
    }
}

if (!isset($data['attacks']) || !$data['attacks']) {
    $data['attacks'] = array();
}

foreach ($data['attacks'] as $attack) {
    if (isset($data['config']['attackTypes'][$attack['type']])) {
        $attack['type'] = $data['config']['attackTypes'][$attack['type']];
    }

    if (isset($data['config']['unitTypes'][$attack['bonusvs']])) {
        $attack['bonusvsname'] = $data['config']['unitTypes'][$attack['bonusvs']];
    } else {
        $attack['bonusvsname'] = $attack['bonusvs'];
    }

    if (strlen($attackstring) > 0) {
        $attackstring .= ', ';
    }

    $attackstring .= "{$attack['damage']} {$attack['type']} ";

    if (isset($attack['bonusvs'])) {
        $attackstring .= " <span class='bonusvs'> x{$attack['bonusvalue']} vs {$attack['bonusvsname']}</span> ";
    }

    foreach ($attack['bonuses'] as $bonus) {
        if (isset($data['config']['unitTypes'][$bonus['bonusvs']])) {
            $bonus['bonusvsname'] = $data['config']['unitTypes'][$bonus['bonusvs']];
        } else {
            $bonus['bonusvsname'] = $bonus['bonusvs'];
        }

        $attackstring .= " <span class='bonusvs'>x{$bonus['bonusvalue']} vs {$bonus['bonusvsname']}</span> ";
    }

    if (isset($attack['range'])) {
        $attackstring .= " <span class='bonusvs'>({$attack['range']} range)</span>";
    }

}

if (strlen($attackstring) > 3) {
    $attackstring = "<li>DPS: {$attackstring} </li>";
}

$traitstring = '';
$traits = array();

if (isset($data['Trait1'])) {
    $traits[] = $data['Trait1'];
}

if (isset($data['Trait2'])) {
    $traits[] = $data['Trait2'];
}

if (isset($data['Trait3'])) {
    $traits[] = $data['Trait3'];
}

if (isset($data['Trait4'])) {
    $traits[] = $data['Trait4'];
}

if (isset($data['Trait5'])) {
    $traits[] = $data['Trait5'];
}

foreach ($traits as $trait) {
    if (strpos($trait, 'Con') === 0) {
        $traitimg = 'Blank';
    } else {
        $traitimg = $trait;
    }

    $traitstring .= "<a href='/items/type/{$trait}'><img class='equipmentslot' alt='{$traitimg}Slot.png' src='/images/GearSlots/{$traitimg}Slot.png'/></a>";
}
?>

<br />
<div style="float:left">

<div class="tooltip" style="width: 300px;">
<div class="inside">
  <div class="header">
    <div class="type"><?=$typestring?></div>
    <div class="age"><?=$agestring?></div>
  </div>
  <br><br>
  <img class="icon" src="/images/Art/<?=$data['Icon']?>.png" width="64px"/> <span class="name"><?=$data['DisplayName']?></span>
  <div class="info">
    <div class="description">
      <p><?=$data['RolloverText']?></p>
    </div>
    <ul id="itemeffects" class="itembonuses">
      <li>HP: <?=$data['MaxHitpoints']?></li>
      <?=$cost?>
      <?=$buildtimestring?>
      <?=$losstring?>
      <?=$speedstring?>
      <?=$attackstring?>
      <?=$armorstring?>
    </ul>
  </div>
  <?=$traitstring?>
  <br>
  Portrait Icon: <br>
  <img src="/images/Art/<?=$data['PortraitIcon']?>.png" width="128px">
  <br>
  dbid: <a href="/units/<?=$data['DBID']?>"><?=$data['DBID']?></a> | <span style="text-decoration:none"><?=$data['name']?></span>
</div>
<?php make_tooltip(); ?>
</div>

</div>