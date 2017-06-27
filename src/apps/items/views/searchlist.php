<h1>Results</h1>
<?php foreach($data as $item){ 
	start_tooltip();
	?>

<div class="trait">
	<img src="/images/Art/<?=$item['icon']?>.png" style="float:left; margin-right: 5px; width:50px" /> 
	 <a href="/<?=$item['type']?>s/<?=$item['dbid']?>"  class="<?=$item['rarity']?>rarity" style="font-size: 14px"><?=$item['keyword']?></a>  <br /><?=$item['description']?> (@ level 40)
	 <div style="text-align: left; margin-left:45px; font-size:12px"><a href="/<?=$item['type']?>s/<?=$item['dbid']?>">[Full info]...</a></div>
</div>

<?php end_tooltip(); 
echo '<br />';
}?>