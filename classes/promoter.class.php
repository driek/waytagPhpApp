<?php
include("../config/config.inc.php");
require_once 'database.class.php';

class Promoter
{
	private $id, $waytag_id, $last_name, $first_name;

	/**
	 * @param unknown_type $id
	 * @param unknown_type $waytag_id
	 * @param unknown_type $last_name
	 * @param unknown_type $first_name
	 */
	private function __construct($id, $waytag_id, $last_name, $first_name)
	{
		$this->id = $id;
		$this->waytag_id = $waytag_id;
		$this->last_name = $last_name;
		$this->first_name = $first_name;
	}

	/**
	 * @param unknown_type $promoterId
	 * @return Promoter
	 */
	public static function find($promoterId)
	{
		$attributes = Promoter::getPromoter($promoterId);
		if (!$attributes)
			return null;
		$promoter = new Promoter($attributes["ID"], $attributes["waytag_ID"], $attributes["last_name"], $attributes["last_name"]);
		return $promoter;
	}

	/**
	 * @param integer $promoter_ID
	 * @return array
	 */
	public static function getActivities($promoter_ID)
	{
		$con = Database::connect();
		$return_result = array();
		if (is_numeric($promoter_ID))
		{
			$result = $con->query("SELECT Activities.* FROM Activities JOIN Activities_Promoters ON Activities_Promoters.Activity_ID = Activities.ID WHERE Activities_Promoters.Promoter_ID = " . $promoter_ID );
			while($result && $row = $result->fetch_array())
			{
				$return_result[] = $row;
			}
		}
		$con->close();
		return $return_result;
	}

	/**
	 * @param string $wayTagID
	 * @return boolean
	 */
	public static function getPromoterByWaytagID($wayTagID)
	{
		$con = Database::connect();
		$result = $con->query("SELECT * FROM Promoters WHERE waytag_ID LIKE '" . $con->real_escape_string($wayTagID) . "'");
		$return_result = false;
		if($result && $result->num_rows > 0)
		{
			$return_result = $result->fetch_array();
		}
		$con->close();
		return $return_result;
	}

	/**
	 * @return array
	 */
	public static function getAllPromoters()
	{
		$con = Database::connect();
		$result = $con->query("SELECT * FROM Promoters");
		$return_result = array();
		while($result && $row = $result->fetch_array())
		{
			$return_result[] = $row;
		}
		$con->close();
		return $return_result;
	}

	/**
	 * @return multitype:unknown
	 */
	public static function getPromotersWithoutActivities()
	{
		$con = Database::connect();
		$result = $con->query("SELECT Promoters.* FROM Promoters LEFT JOIN Activities_Promoters ON Promoters.ID = Activities_Promoters.Promoter_ID WHERE Activities_Promoters.Promoter_ID IS NULL GROUP BY Promoters.ID");
		$return_result = array();
		while($result && $row = $result->fetch_array())
		{
			$return_result[] = $row;
		}
		$con->close();
		return $return_result;
	}

	/**
	 * @return multitype:unknown
	 */
	public static function getPromotersWithoutWayTag()
	{
		$con = Database::connect();
		$result = $con->query("SELECT * FROM  Promoters WHERE LENGTH( TRIM( waytag_id ) ) = 0");
		$return_result = array();
		while($result && $row = $result->fetch_array())
		{
			$return_result[] = $row;
		}
		$con->close();
		return $return_result;
	}

	/**
	 * @param unknown_type $promoter_id
	 * @return boolean
	 */
	public static function getPromoter($promoter_id)
	{
		if (!is_numeric($promoter_id))
			return false;
		$con = Database::connect();
		$result = $con->query("SELECT * FROM Promoters WHERE ID = $promoter_id");
		$return_result = array();
		if($result && $result->num_rows > 0)
		{
			$return_result = $result->fetch_array();
		}
		$con->close();
		return $return_result;
	}

	/**
	 * @param integer $activityId : The new Activities_Promoters.ID, otherwise 0 for invalid activity_id, -1 for existing activity for the promoter.
	 */
	public function addActivity($activityId)
	{
		if (!is_numeric($activityId))
			return 0;
		$con = Database::connect();
		$query = "SELECT * FROM Activities_Promoters WHERE Activity_ID = $activityId AND Promoter_ID = " . $this->id;
		$result = $con->query($query);
		if ($result->num_rows > 0)
			return -1;
		$query = "INSERT INTO Activities_Promoters (Activity_ID, Promoter_ID) VALUES ($activityId, ".$this->id.")";
		$con->query($query);
		$new_id = $con->insert_id;
		$con->close();
		return $new_id;
	}

	public function removeActivity($activity_id)
	{
		if (is_null($activity_id))
			return false;
		$con = Database::connect();
		$query = "DELETE FROM Activities_Promoters WHERE Activity_ID = $activity_id AND Promoter_ID = $this->id";
		return $con->query($query);
	}

}
?>