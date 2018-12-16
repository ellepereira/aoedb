
<?php 
$cost = '';

if(isset($data['CostFood']))
	$cost.= "{$data['CostFood']} food ";
if(isset($data['CostWood']))
	$cost.= "{$data['CostWood']} wood ";
if(isset($data['CostGold']))
	$cost.= "{$data['CostGold']} gold ";
if(isset($data['CostStone']))
	$cost.= "{$data['CostStone']} stone ";

?>

<div class="unit">
<img class="icon" src="images/<?=$data['Icon']?>.png" />
<h1><?=$data['DisplayName']?></h1>

<p><?=$data['RolloverText']?></p>
<ul>
	<li>HP: <?=$data['MaxHitPoints']?></li>
	<li>LOS: <?=$data['LOS']?></li>
	<li>Pop: <?=$data['PopulationCount']?></li>
	<li>Train Time: <?=$data['TrainPoints']?></li>
	<li>Cost: <?=$cost?></li>
	<li>Age: <?=($data['AllowedAge']+1)?></li>
	<li>Armor: </li>
	<li>Can wear: </li>
</ul>
<h1>Proto Actions</h1>
<hr> <br />
<pre><?php print_r($data['protoactions']);?></pre>
</div>