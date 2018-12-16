<br />
<div style="float:left">

<div class="tooltip" style="width: 300px;">
<div class="inside">
	<div class="header">
		<div class="type"><?=$data['displayname']?></div>
		<div class="rarity"><span class="<?=$data['rarity']?>"><?=$data['rarity']?></span></div>
	</div>
	<br>
	<br>
	<div style="text-align: center;">
		<img src="https://images.projectceleste.com/Art/<?=$data['icon']?>.png" width="256">
		<br>
		<p style="font-size: 16px"><?=$data['displaydescription']?></p>
	</div>
	dbid: <?=$data['name']?>
</div>
<?php make_tooltip(); ?>
</div>

</div>
