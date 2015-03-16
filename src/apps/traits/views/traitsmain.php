<h1>Items By Type:</h1>

<div class="tooltip" style="width: 720px; float: left;">

<table class="list">
<tr>
<th>Icon</th>
<th>Name</th>
</tr>

<?php foreach($data as $k=>$n):	?>
<tr onclick="document.location.href='/traits/type/<?=$k?>';">
<td><img src="/images/GearSlots/<?=$k?>Slot.png" width="45px" alt="<?=$k?>"/></td>
<td><a href="/traits/type/<?=$k?>"><?=$n?></a></td>
</tr>
<?php //<tr><td colspan="5" style="text-align:center">test</td></tr> ?>
<?php endforeach;?>
</table>

<? make_tooltip(); ?>


</div>