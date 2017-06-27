<?php 

$item = $data['item'];
$item['RolloverText'] = str_replace('\n', '<br /> <br />', $item['RolloverText']);

$requiredlevel = $item['level']-3;

if($requiredlevel < 1)
	$requiredlevel = 1;
if($requiredlevel > 40)
	$requiredlevel = 40;
?>
<script>
 level = <?=$item['level']?>;
 rlevel = <?=$requiredlevel?>;
 dbid = <?=$item['dbid']?>;
</script>

<br />
<div id="itemContainer" style="float:left">

<div id="item" class="tooltip" style="width: 300px; height: 45px">
<div class="inside" style="height: 35px">
<img class="icon" src="/images/Art/<?=$item['icon']?>.png" style="height: 35px">
  <div class="header">
    <span class="name <?=$item['rarity']?>" style="font-size: 14px"><?=$item['DisplayName']?></span><br />
     <div class="type" style="font-size: 12px;">Level <?=$item['level']?> <?=$item['type']?></div> 
     <span class="scriptlink" style="float: right; font-size: 10px; margin-top:5px" onClick="expandItem(dbid, rlevel, '<?=$item['name']?>')">[ + ]</span> 
  </div>
</div>
<? make_tooltip(); ?>
</div>

</div>
