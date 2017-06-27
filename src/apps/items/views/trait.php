<?php 

$item = $data['item'];
$item['RolloverText'] = str_replace('\n', '<br /> <br />', $item['RolloverText']);

$requiredlevel = $item['level']-3;

if($requiredlevel < 1)
	$requiredlevel = 1;
if($requiredlevel > 40)
	$requiredlevel = 40;

if(!isset($item['effectstrings']))
	$item['effectstrings'] = array();

if(!empty($item['levels']))
{
	$levels = '';
	$ls = explode('|', $item['levels']);
	asort($ls);
	
	foreach($ls as $level)
	{
		$level -=3;

		if($level < 1)
		{
			$levels = '?';
		}
		else 
		{
			$levels .= "<a href='/items/{$item['dbid']}/{$level}'>{$level}</a> ";
		}
		
	}
}
else
{
	$levels = '?';
}
?>

<br />
<script>
 level = <?=$item['level']?>;
 rlevel = <?=$requiredlevel?>;
 dbid = <?=$item['dbid']?>;
</script>


<div id="itemContainer" style="float:left">

<div id="item" class="tooltip" style="width: 300px;">
<div class="inside">
  <div class="header">
    <div class="type"><?=$item['type']?></div>
    <div class="rarity"><span class="<?=$item['rarity']?>"><?=$item['rarity']?></span> <br />
		
	</div>
	
  </div>
  <br><br>
  <img class="icon" src="/images/Art/<?=$item['icon']?>.png"> <span class="name <?=$item['rarity']?>"><?=$item['DisplayName']?></span>
  <div class="info">
    <div class="description">
      <span id="rlevel">Required Level: <?=$requiredlevel?></span><span id="changelevel" class="scriptlink" style="font-size: 8px"><img src="/images/edit.png" width="12px"> [edit]</span>
      <div id="showchangelevel"></div>
      <p><?=$item['RolloverText']?></p>
    </div>
    <ul id="itemeffects" class="itembonuses">
      <? foreach($item['effectstrings'] as $effect)
      {
        echo "<li class='bonus{$effect->bonus}'>{$effect}</li>";
      }?>
    </ul>
  </div>
  <div>Available at level(s): <?=$levels?></div>
  <br>
  dbid: <?=$item['dbid']?> | <a id="imglink" href="/i/<?="{$item['dbid']}/{$requiredlevel}"?>.png">img</a> | <span class="scriptlink" style="text-decoration: underline" onClick="xmldiag(this, 'trait', '<?=$item['name']?>')">xml</span> |
  <span class="scriptlink" style="text-decoration: underline" onClick="auctionSell(dbid, rlevel, '<?=$item['name']?>')">sell</span>
  <span class="scriptlink" style="float: right; font-size: 10px;" onClick="minimizeItem(dbid, rlevel)">[ - ]</span> 
</div>
<? make_tooltip(); ?>
</div>

</div>
