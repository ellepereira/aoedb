<?php
$rarities = array('cRarityCommon' => 'common',
                  'cRarityUncommon' => 'uncommon',
                  'cRarityRare' => 'rare',
                  'cRarityEpic' => 'epic');
?>



<h1>Blueprints:</h1>
<table>
<?php
$i = 0;

foreach ($data as $k=>$n) { 
	if($i == 0){
	?>
	<tr><?}?>
	<td>
	<?php start_tooltip();?>
<div style="width: 200px; vertical-align: middle">
	<a href="/blueprints/<?=$n['DBID']?>">
		<img src="https://images.projectceleste.com/Art/<?=$n['icon']?>.png" style="float: left; width:40px; margin-right: 5px">
	</a>
	<a href="/blueprints/<?=$n['DBID']?>" style="font-size: 16px; text-decoration: none"><span class="itemname <?=$rarities[$n['rarity']]?>rarity"><?=$n['displayname']?></span></a>
</div>
<?php end_tooltip(); ?>
</td>
<?php if($i==2){?>
	</tr>
	<?php $i =0; } else {$i++;}
	
}?>
</table>