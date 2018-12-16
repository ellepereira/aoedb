
<?php 
$givenRewards = array();
$pickReward = array();

foreach($data->info['rewards'] as $k=>$reward)
{
	if($reward['pickone'] == 0)
		$givenRewards[] = $reward;
	else
		$pickReward[] = $reward;
}

function spew_reward($reward, $data)
{
	switch($reward['name'])
	{
		case 'cCapResCoin':
			echo "<tr><td><img alt='xp' src='/images/quests/coins.png' /></td><td><span class='reward'>{$reward['value']} Coins</span></td></tr>";
			break;
		case 'cCapResFactionPoints3':
			echo "<tr><td><img alt='xp' src='/images/quests/ep.png' /></td><td><span class='reward'>{$reward['value']} Empire Points</span></td></tr>";
			break;
		case 'loottable':
			echo "<tr><td><img alt='xp' src='/images/quests/chest.png' /></td><td><span class='reward'>".ucfirst($reward['value'])." loot table chest level {$data->level}</span></td></tr>";
			break;
	}
}
?>

<br />

<div class="tooltip" style="width: 240px; float: left;">
	<div id="questLeft" class="inside">
		<ul>
		<li>Given by: <span class="infoValue"><?=$data->questgivers?></span></li>
		<li>Civ/Booster: <span class="infoValue"><?=$data->questline?></span></li>
		<li>Repeatable: <span class="infoValue"><?=($data->repeatable==0 ? 'false': 'true')?></span></li>
		<li>Co-op: <span class="infoValue"><?=($data->canplaycoop==1 ? 'yes': 'no');($data->cooprequired==1 ? ' required': '')?></span></li>
		</ul>
		<br /><br />
		<pre><?php print_r($data->info); ?></pre>
		
	</div>
<? make_tooltip(); ?>
</div>

<div class="tooltip" style="width: 640px; float: right;">
	<div id="questMain" class="inside">
		<h1><?=$data->DisplayName?></h1>
		<p><?=$data->SummaryText?></p>
		
		<h2>Rewards: </h2>
		<table>	
		<?php 
			echo ($data->xp > 0 ? "<tr><td><img alt='xp' src='/images/quests/xp.png' /></td><td><span class='reward'>{$data->xp} XP</span></td></tr>" : '');
			
			foreach($givenRewards as $reward)
				spew_reward($reward, $data);
		?>
		</table>
		<?php if(count($pickReward) > 0)
		{?>
		<h2>Pick-one: </h2>
		<table>
		
		</table>
		<?php } ?>

	</div>
<? make_tooltip(); ?>
</div>
