<?php
class Waytag
{
	public static function getUnusedWaytags($currentwaytags)
	{
		$con = Database::connect();
		$result = $con->query("SELECT * FROM  Promoters");
		$return_result = array();
		$temp_results = array();
		while($result && $row = $result->fetch_array())
		{
			$temp_results[] = $row["waytag_ID"];
		}		
		$con->close();
		foreach ($currentwaytags as $key => $currentwaytag)
		{
			if (!in_array($key, $temp_results))
			{
				$return_result[$key] = $currentwaytag;
			}			
		}
		return $return_result;
	}
	
	public static function getMyWaytags()
	{
		return Waytag::makeRequest("GetMyWaytagsSQ");
	}
	
	public static function getMyMobileWaytag()
	{
		// cResultLog = NO_MOBILE_WAYTAG_FOUND
		return array_pop(Waytag::makeRequest("GetMyMobileWaytags"));
	}
	
	public static function getClosestBusinessWaytags($latitude, $longitude, $distance = 10)
	{
		$parameters["ipdSearchRange"] = $distance;
		$parameters["ipdRefLatitude"] = $latitude;
		$parameters["ipdRefLongitude"] = $longitude;
		return Waytag::makeRequest("FindWaytagsByProximitySrtSQ", $parameters);
	}
	
	public static function makeRequest($service, $parameters = array())
	{
		include("../config/config.inc.php");
		$xml = new XMLReader();
		$queryString = "http://devzone.waytag.com/cgi-bin/wspd_cgi.sh/WService=wsb_wtdev/rest.w?rqDataMode=VAR/XML&rqAuthentication=user:".$config["waytag_user_name"]."|".$config["waytag_user_password"]."&rqversion=1&rqappkey=".$config["waytag_app_key"]."|".$config["waytag_app_password"]."&rqservice=wtutility:".$service;
		foreach ($parameters as $key => $parameter)
		{
			$queryString .= "&" . urlencode($key) . "=" . urlencode($parameter);
		}
		$xml->open($queryString);
		$text = "";
		$types = array();
		$attributes = array();
		$wayTags = array();
		$attribute = "";
		$waytagIDs = array();
		while ($xml->read())
		{
			if ($xml->nodeType == XMLReader::END_ELEMENT && $xml->name == "ttResponseRow")
			{
				$wayTags[$attributes["dWayTagObj"]] = $attributes;
			}
			else if ($xml->nodeType == XMLReader::ELEMENT && $xml->name == "ttResponseRow")
			{
				$attributes = array();
			}
			else if ($xml->nodeType == XMLReader::ELEMENT)
			{
				$attribute = $xml->name;
			}
			if ($xml->nodeType == XMLReader::TEXT)
			{
				if ($attribute == "dWayTagObj")
				{
					$waytagIDs[] = $xml->value;
				}
				$attributes[$attribute] = $xml->value;
			}
		}
		return $wayTags;
	}
	
}
?>