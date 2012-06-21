<?php
require_once("../classes/promoter.class.php");
require_once("../classes/waytag.class.php");
require_once("../classes/activity.class.php");

$function = $_POST["function"];
switch ($function)
{
	case "addActivityToPromoter":
		$promoter_id = $_POST["promoter_id"];
		$activity_id = $_POST["activity_id"];
		if (is_numeric($promoter_id) && is_numeric($activity_id))
		{
			$promoter = Promoter::find($promoter_id);
			$result = $promoter->addActivity($activity_id);
		}
		break;
	case "removeActivityFromPromoter":
		$result = false;
		$promoter_id = $_POST["promoter_id"];
		$activity_id = $_POST["activity_id"];
		if (is_numeric($promoter_id) && is_numeric($activity_id))
		{
			$promoter = Promoter::find($promoter_id);
			$result = $promoter->removeActivity($activity_id);
		}
		echo $result;
		break;
	case "getWaytagsSummary":
		$wayTags = Waytag::getMyWaytags();
		include("waytagsSummary.template.php");
		break;
}
?>