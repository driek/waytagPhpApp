<?php 
require_once("../classes/promoter.class.php");
require_once("../classes/activity.class.php");
?>
<fieldset>
	<legend>WayTags</legend>
	<span>Click on the Waytag to select it: </span>
	<div id="waytagslist" >
	<?php 
	foreach ($wayTags as $wayTag)
	{
		$promoter = Promoter::getPromoterByWaytagID($wayTag["dWayTagObj"]);
		if ($config["show_all_waytags"] || $promoter)
		{
			include("wayTagSummary.template.php");
		}
	}
	?>
	</div>
	<div>
	<!-- 
	Promoters withouth a WayTag;
	<ul>
	<?php
	foreach (Promoter::getPromotersWithoutWayTag() as $promoter)
	{
		?><li><?=$promoter["last_name"]?>, <?= $promoter["first_name"]?> <?php echo createWayTagOptionList(Waytag::getUnusedWaytags($wayTags)); ?></li>
		<?php 
	}
	?>
	</ul>
	-->
	Promoters withouth current Activities;
	<ul>
	<?php
	foreach (Promoter::getPromotersWithoutActivities() as $promoter)
	{
		?><li><?=$promoter["last_name"]?>, <?= $promoter["first_name"]?> <?php echo createActivitiesOptionList(Activity::getUnassignedActivites(), "promoter_".$promoter["ID"]);?></li>
		<?php 
	}
	?>
	</ul>
	Activities withouth current Promoters;
	<ul>
	<?php
	foreach (Activity::getUnassignedActivites() as $activity)
	{
		?><li><?=$activity["name"]?> <?php echo createPromotersOptionList(Promoter::getAllPromoters(), "activity_".$activity["ID"]);?></li>
		<?php 
	}
	?>
	</ul>
	</div>
</fieldset>
<?php 
function createWayTagOptionList($waytags, $name = "")
{
	$options = "<select name='$name'><option value=''>---</option>";
	foreach ($waytags as $waytag)
	{
		$options .= "<option value='" . $waytag['dWayTagObj'] . "'>".$waytag['cCustomReference']."</option>";
	}
	$options .= "</select>";
	return $options;
}

function createPromotersOptionList($promoters, $name = "")
{
	$options = "<select name='$name' onchange='onPromotersOptionlistChange(this);'><option value=''>---</option>";
	foreach ($promoters as $promoter)
	{
		$options .= "<option value='" . $promoter["ID"] . "'>".$promoter["last_name"] . ", " . $promoter["first_name"] ."</option>";
	}
	$options .= "</select>";
	return $options;
}

function createActivitiesOptionList($activities, $name = "")
{
	$options = "<select name='$name' onchange='onActivitiesOptionlistChange(this);'><option value=''>---</option>";
	foreach ($activities as $activity)
	{
		$options .= "<option value='" . $activity["ID"] . "'>".$activity["name"] . "</option>";
	}
	$options .= "</select>";
	return $options;
}
?>