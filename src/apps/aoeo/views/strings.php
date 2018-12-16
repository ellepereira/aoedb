
<ul>
<?php foreach($data as $key=>$string){ ?>
<li>
	<b><?=$string['stringid']?></b> : <?=$string['string']?>. <i><?=$string['comment']?></i>.
</li>
<?php } ?>
</ul>