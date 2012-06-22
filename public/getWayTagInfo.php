<?php 
require_once '../classes/promoter.class.php';
$wayTagID = $_POST["wayTagId"];
$wayTagTag = $_POST["wayTagTag"];
$xml = new XMLReader();
$xml->open("http://devzone.waytag.com/cgi-bin/wspd_cgi.sh/WService=wsb_wtdev/rest.w?rqDataMode=VAR/XML&rqAuthentication=user:".$config["waytag_user_name"]."|".$config["waytag_user_password"]."&rqversion=1&rqappkey=".$config["waytag_app_key"]."|".$config["waytag_app_password"]."&rqservice=wtutility:FindWaytags&ipcWayTagReference=".$wayTagTag);
$text = "";
$types = array();
$attributes = array();
$wayTags = array();
$attribute = "";
$found = false;
while ($xml->read()) {
	if ($xml->nodeType == XMLReader::END_ELEMENT && $xml->name == "ttResponseRow")
	{
		$wayTags[] = $attributes;
		if ($found)
		{
			break;
		}
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
		$attributes[$attribute] = $xml->value;
		if ($attribute == "dWayTagObj" && $xml->value == $wayTagID)
		{
			$found = true;
		}
	}
}
 
$promoter = Promoter::getPromoterByWaytagID($wayTagID);
$activities = $promoter?$promoter->getActivities():array();
include("wayTagInfo.template.php");
?>

