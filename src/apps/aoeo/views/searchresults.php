
<br />
<h1>Results for: "<?=$data['query']?>"</h1>
<div style="float: left;">
<div class="tooltip" style="width: 720px;">
<div class="inside">
<br />
<?php

	$donotlink = array('quest');
	
	foreach($data['results'] as $type=>$results)
	{
		$c = count($results);
		echo " | {$c} ".ucfirst($type)."(s)</p> <ul>";
		
		foreach($results as $k=>$result)
		{
				$d = str_replace('\n', " ", $result['description']);
				
				
				if(!in_array($type, $donotlink))
					echo "<li><a href='/{$type}s/{$result['dbid']}'>{$result['keyword']}</a> - {$d}</li>";
				else
					echo "<li>{$result['keyword']} - {$d}</li>";

				if($k == 10 && count($data['results']) > 1)
				{
					echo "<li><a href='/aoeo/search/{$data['query']}/{$type}'>More...</a></li>";
					break;
				}
		}
		
		echo '</ul>';
	}
echo '<br />'; ?>
<div>
<? make_tooltip(); ?>

</div>
</div>