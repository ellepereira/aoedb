<br />
<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Icon</th><th>Name</th><th>Levels</th><th>Rarity</th><th>Type</th>
</tr>

<?php foreach($data as $item):
	$levels = '';
	$ls = explode('|', $item['levels']);
	asort($ls);
	foreach($ls as $level)
	{
		$level -=3;
		
		if($level < 1)
		{
			$levels = 'Unknown';
			$maxlevel = 40;
		}
		else
		{
			$levels .= "<a href='/items/{$item['dbid']}/{$level}'>{$level}</a> ";
			$maxlevel = $level;
		}
		
		
	}
	
	
?>
<tr onclick="document.location.href='/items/<?=$item['dbid']?>';">
<td><img src="/images/Art/<?=$item['icon']?>.png" alt="<?=$item['DisplayName']?>" width="45px" /></td>
<td><a href="/items/<?=$item['dbid']?>/<?=$maxlevel?>" ><?=$item['DisplayName']?></a></td>
<td><span><?=$levels?></span></td>
<td> <span class="<?=$item['rarity']?>"><?=$item['rarity'] ?></span></td>
<td> <?=$item['type'] ?></td>
</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<? make_tooltip(); ?>


</div>