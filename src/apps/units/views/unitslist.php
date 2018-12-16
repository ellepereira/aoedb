<br>
<?php 
$typetypes = array(''=>'Units','inf' => 'Infantry', 'cav' => 'Cavalry', 'bldg' => 'Building(s)', 'arc' => 'Ranged Unit(s)', 'shp' => 'Ships', 'sie' => 'Siege Unit(s)', 'civ' => 'Economic Unit(s)', 'spc' => 'Religious Unit(s)', 'cap' => 'Capital Building(s)');
$civs = array(''=>'All', 'gr' => 'Greek', 'no' => 'Norse', 'eg' => 'Egyptian', 'ba' => 'Babylonian', 'ce' => 'Celtic', 'pe' => 'Persian', 'con' => 'Consumable', 'pv' => 'Spartan', 'mn' => 'Minoan', 'cy' => 'Cypriot');

?>
<h1><?php echo $civs[$data['civ']].' '.$typetypes[$data['type']] ?></h1>

<?php if(empty($data['civ'])){$data['civ'] = 'gr';}?>
<p>Civilization: 
<a href="/units/civ/gr/<?=$data['type']?>">Greek</a> | 
<a href="/units/civ/eg/<?=$data['type']?>">Egyptian</a> | 
<a href="/units/civ/pe/<?=$data['type']?>">Persian</a> |
<a href="/units/civ/ce/<?=$data['type']?>">Celtic</a> |
<a href="/units/civ/ba/<?=$data['type']?>">Babylonian</a> |
<a href="/units/civ/no/<?=$data['type']?>">Norse</a>
<br />
Type: 
<a href="/units/civ/<?=$data['civ']?>">All</a> | 
<a href="/units/civ/<?=$data['civ']?>/inf">Infantry</a> | 
<a href="/units/civ/<?=$data['civ']?>/arc">Ranged</a> | 
<a href="/units/civ/<?=$data['civ']?>/cav">Cavalry</a> | 
<a href="/units/civ/<?=$data['civ']?>/sie">Siege</a> | 
<a href="/units/civ/<?=$data['civ']?>/shp">Ships</a> | 
<a href="/units/civ/<?=$data['civ']?>/civ">Economic</a> | 
<a href="/units/civ/<?=$data['civ']?>/bldg">Buildings</a> | 
<a href="/units/civ/<?=$data['civ']?>/spc">Religious</a> | 
<a href="/units/civ/<?=$data['civ']?>/cap">Capital</a>
</p>



<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
	<tr>
	<th>Icon</th><th>Name</th><th>Type</th><th>Age</th><th>Description</th>
	</tr>
	
	<?php foreach($data['units'] as $unit){
	//I hate you dave
	// Your fault I blame the framework
	//:(
	
		if(empty($unit['name']))
		continue;
		
		$cost = '';
		
		if(isset($unit['CostFood']))
		$cost.= "{$unit['CostFood']} <img src='/images/food.png' width='10px' /> ";
		if(isset($unit['CostWood']))
		$cost.= "{$unit['CostWood']} <img src='/images/wood.png' width='10px' /> ";
		if(isset($unit['CostGold']))
		$cost.= "{$unit['CostGold']} <img src='/images/gold.png' width='10px' /> ";
		if(isset($unit['CostStone']))
		$cost.= "{$unit['CostStone']} <img src='/images/stone.png' width='10px' /> ";
		if(isset($unit['PopulationCount']))
		$cost.= "{$unit['PopulationCount']} <img src='/images/pop.png' width='10px' /> ";
		if(isset($unit['TrainPoints']))
		$cost.= "{$unit['TrainPoints']} (time) ";
		
		
		$traitstring = '';
			$traits = array();
		
		if(isset($unit['Trait1']))
			$traits[] = $unit['Trait1'];
			if(isset($unit['Trait2']))
			$traits[] = $unit['Trait2'];
			if(isset($unit['Trait3']))
			$traits[] = $unit['Trait3'];
			if(isset($unit['Trait4']))
			$traits[] = $unit['Trait4'];
			if(isset($unit['Trait5']))
			$traits[] = $unit['Trait5'];
			
		foreach($traits as $trait)
		{
		if(strpos($trait, 'Con') === 0)
		$traitimg = 'Blank';
		else
					$traitimg = $trait;
		
		$traitstring .= "<a href='/traits/type/{$trait}'><img style='width: 30px' alt='{$traitimg}Slot.png' src='/images/GearSlots/{$traitimg}Slot.png'/></a> ";
			}
	$typecivs = array('Gr' => 'Greek', 'No' => 'Norse', 'Eg' => 'Egyptian', 'Ce' => 'Celtic', 'Pe' => 'Persian', 'Con' => 'Consumable', 'Pv' => 'Spartan', 'Mn' => 'Minoan', 'Cy' => 'Cypriot');
	$typetypes = array('Inf' => 'Infantry', 'Cav' => 'Cavalry', 'Bldg' => 'Building', 'Arc' => 'Ranged Unit', 'Shp' => 'Ship', 'Sie' => 'Siege Unit', 'Civ' => 'Economic Unit', 'Spc' => 'Religious Unit', 'Cap' => 'Capital Building', 'WallStraight2' => 'Wall', 'WallConnector' => 'Wall', 'WallStraight1' => 'Wall', 'WallGate' => 'Wall', 'WallStraight5' => 'Wall');
	
	$typestring = '';
	
	$nameexplode = explode('_', $unit['name']);
	
	if (array_key_exists($nameexplode[0], $typecivs))
	$typestring .= $typecivs[$nameexplode[0]] . ' ';
	
	if (array_key_exists($nameexplode[1], $typetypes)) {
		$typestring .= $typetypes[$nameexplode[1]];
	}
	else {
		$typestring = $unit['name'];
	}
	
	if ($nameexplode[1] != 'Cap') {
		$agenames = array('Copper Age (I)', 'Bronze Age (II)', 'Silver Age (III)', 'Golden Age (IV)');
		$agestring = @$agenames[$unit['AllowedAge']];
		//$agestring = "Age: {$data['AllowedAge']+1}";
	}
	else {
		$agestring = '';
	}
		
	?>
	<tr onClick="document.location.href='/units/<?=$unit['DBID']?>';">
	<td><img class="pic" src="https://images.projectceleste.com/Art/<?=$unit['Icon']?>.png" width="45px"/></td>
	<td><a href="/units/<?=$unit['DBID']?>" ><?=$unit['DisplayName']?></a></td>
	<td><span><?=$typestring?></span></td>
	<td> <?=$agestring?></td>
	<td> <?=$unit['RolloverText'] ?></td>
	</tr>
	<?php } ?>
</table>

<?php make_tooltip(); ?>


</div>