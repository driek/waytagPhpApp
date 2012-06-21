<?php
class Activity
{
	public static function getUnassignedActivites()
	{
		$con = Database::connect();
		$result = $con->query("SELECT Activities.* FROM Activities LEFT JOIN Activities_Promoters ON Activities.ID = Activities_Promoters.Activity_ID WHERE Activities_Promoters.Activity_ID IS NULL GROUP BY Activities.ID");
		$return_result = array();
		while($result && $row = $result->fetch_array())
		{
			$return_result[] = $row;
		}
		$con->close();
		return $return_result;
	}
}
?>