<h3><?=$attributes["cCustomReference"]?> - <?=$attributes["cCustomLabel"]?></h3>
<table>
<?php 
if($promoter)
{
	?>
	<tr><th>Last name</th><td><?=$promoter["last_name"]?></td></tr>
	<tr><th>First name</th><td><?=$promoter["first_name"]?></td></tr>
	<tr><th>Status message</th><td><?=$promoter["optional_status"]?></td></tr>
	<?php
}
foreach ($activities as $activity)
{?>
	<tr><th>Activity name</th><td><?=$activity["name"]?> <a href="#" onclick="removeActivityFromPromoter(<?=$promoter["ID"]?>, <?=$activity["ID"]?>, this.parentNode.parentNode);"><img src="images/delete.png" style="position: relative; top:4px;" alt="remove activity from promoter" title="remove activity from promoter" /></a></td></tr>
<?php 
}
?>
</table>
<table id="waytagdetails<?=str_replace(".", "", $wayTagID)?>" style="font-size: xx-small; display: none;">
<?php 
foreach ($attributes as $key => $value)
{
	?>
	<tr><th><?=$key?></th><td><?=$value?></td></tr>
	<?php
}
?>
</table>
<a href="#" onclick="panMapToWayTag('<?=$wayTagID?>')"><img src="images/locate.png" alt="Pan to location" title="Pan to location" style="position: relative; top:5px;" /></a>
<a href="#" onclick="$('#waytagdetails<?=str_replace(".", "", $wayTagID)?>').toggle(1000);"><img src="images/details.png" alt="Hide/show details" title="Hide/show details" style="position: relative; top:5px;" /></a>
