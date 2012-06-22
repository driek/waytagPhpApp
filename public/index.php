<?php 
require_once("../classes/promoter.class.php");
require_once("../classes/waytag.class.php");
require_once("../classes/activity.class.php");
include("../config/config.inc.php");
?>
<html>
<head>
<meta charset="UTF-8">
<title>WayTag</title>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<link rel="stylesheet" type="text/css" href="styles/styles.css" />
</head>
<body>
<div id="debug"></div>
<?php 
$wayTags = Waytag::getMyWaytags();
?>
<div id="map" style="float:left"></div>
<fieldset style="float:left; width: 400px;">
	<legend>WayTag information</legend>
	<div id="info_pane"></div>
</fieldset>
<div id="waytags_summary">
<?php include("waytagsSummary.template.php"); ?>
</div>
<!-- http://devzone.waytag.com/th/waytag/img/mrk/num/1.png -->
<script type="text/javascript">
lat = 51.758537;
lon = 5.50724;
latlon=new google.maps.LatLng(lat, lon);
mapholder=document.getElementById('map');
mapholder.style.height="400px";
mapholder.style.width="500px";

var myOptions={
center:latlon,zoom:14,
mapTypeId:google.maps.MapTypeId.ROADMAP,
mapTypeControl:false,
navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}};
var previousSelected, previousSelectedWaytagId;
var map=new google.maps.Map(mapholder, myOptions);
var wayTags = new Array();
var markers = new Array();
var wayTag, marker;
<?php 
foreach ($wayTags as $key => $wayTag)
{
	$promoter = Promoter::getPromoterByWaytagID($wayTag["dWayTagObj"]);
	if (!$config["show_all_waytags"] && !$promoter)
	{
		continue;
	}
?>
wayTag = new Array();
wayTag["displayName"] = "<?= $wayTag["cDisplayName"]?>";
wayTag["latitude"] = "<?= $wayTag["dWayTagLatitude"]?>";
wayTag["longitude"] = "<?= $wayTag["dWayTagLongitude"]?>";
wayTag["waytagTag"] = "<?= $wayTag["cCustomReference"]?>";

makerlatlon=new google.maps.LatLng(<?=$wayTag["dWayTagLatitude"]?>, <?=$wayTag["dWayTagLongitude"]?>);
marker=new google.maps.Marker({position:makerlatlon,map:map,title:"<?=$wayTag["cDisplayName"]?>"});
markers['<?=$wayTag["dWayTagObj"]?>'] = marker;
wayTag["marker"] = marker;
google.maps.event.addListener(marker, 'click', function() {
showExtraInfo('<?=$wayTag["dWayTagObj"]?>');
});
wayTags['<?=$wayTag["dWayTagObj"]?>'] = wayTag;
<?php 
}
?>

function highlightMarker(wayTagId)
{
	if (previousSelected)
	{
		previousSelected.setIcon("http://google.com/mapfiles/ms/micons/red-dot.png");
	}
	wayTags[wayTagId]["marker"].setIcon("http://google.com/mapfiles/ms/micons/blue-dot.png");
	previousSelected = wayTags[wayTagId]["marker"];
}

function showExtraInfo(wayTagId)
{
	highlightMarker(wayTagId);
	highlightWaytagInSummary(wayTagId);
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=function()
	{
	  if (xmlhttp.readyState==4 && xmlhttp.status==200)
	  {
	    document.getElementById("info_pane").innerHTML=xmlhttp.responseText;
	  }
	};
	xmlhttp.open("POST","getWayTagInfo.php",true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("wayTagId="+wayTagId+"&wayTagTag="+wayTags[wayTagId]["waytagTag"]);
}

function panMapToWayTag(wayTagID)
{
	var markerlatlon = new google.maps.LatLng(wayTags[wayTagID]["latitude"], wayTags[wayTagID]["longitude"]);
	map.panTo(markerlatlon);
}

function highlightWaytagInSummary(waytagid)
{
	if (previousSelectedWaytagId)
	{
		document.getElementById("waytaglink_"+previousSelectedWaytagId).className = "waytaglink";
		document.getElementById("waytagsummary_"+previousSelectedWaytagId).className = "waytagsummary";
	}
	document.getElementById("waytaglink_"+waytagid).className = "waytag_selected";
	document.getElementById("waytagsummary_"+waytagid).className = "waytagsummary_selected";
	previousSelectedWaytagId = waytagid;
}

function onPromotersOptionlistChange(optionlist)
{
	activity_id = optionlist.name.split("_")[1];
	promoter_id = optionlist.options[optionlist.selectedIndex].value;
	addActivityToPromoter(promoter_id, activity_id);
	refreshWaytagsSummary();
}

function onActivitiesOptionlistChange(optionlist)
{
	promoter_id = optionlist.name.split("_")[1];
	activity_id = optionlist.options[optionlist.selectedIndex].value;
	addActivityToPromoter(promoter_id, activity_id);
	refreshWaytagsSummary();
}

function addActivityToPromoter(promoter_id, activity_id)
{
	var parameters = {"function":"addActivityToPromoter", "promoter_id" : promoter_id, "activity_id" : activity_id};
	var resultFunction=function()
	{
	  if (this.readyState==4 && this.status==200)
	  {
	    //document.getElementById("debug").innerHTML=xmlhttp.responseText;
		showExtraInfo(previousSelectedWaytagId);
	  }
	};
	ajaxCall("waytags.php", parameters, resultFunction);
}

function refreshWaytagsSummary()
{
	var resultFunction=function()
	{
	  if (this.readyState==4 && this.status==200)
	  {
	    document.getElementById("waytags_summary").innerHTML=this.responseText;
	    if (previousSelectedWaytagId)
		    highlightWaytagInSummary(previousSelectedWaytagId);
	  }
	};
	ajaxCall("waytags.php", {"function":"getWaytagsSummary"}, resultFunction);
}

function removeActivityFromPromoter(promoter_id, activity_id, tableRow)
{
	//alert("promoter_id: " + promoter_id + ", activity_id: " + activity_id);
	var parameters = {"function" : "removeActivityFromPromoter", "promoter_id" : promoter_id, "activity_id" : activity_id};
	var resultFunction = function(){
	  if (this.readyState==4 && this.status==200)
	  {
		//document.getElementById("debug").innerHTML = xmlhttp.responseText;
		if (this.responseText)
		{
			//document.getElementById("debug").innerHTML = this.responseText;
			tableRow.parentNode.removeChild(tableRow);
			refreshWaytagsSummary();
		}
	  }
	};
	ajaxCall("waytags.php", parameters, resultFunction);
}

function ajaxCall(url, parameters, resultFunction)
{
	//alert("promoter_id: " + promoter_id + ", activity_id: " + activity_id);
	var xmlhttp;
	if (window.XMLHttpRequest)
	{// code for IE7+, Firefox, Chrome, Opera, Safari
	  xmlhttp=new XMLHttpRequest();
	}
	else
	{// code for IE6, IE5
	  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}
	xmlhttp.onreadystatechange=resultFunction;
	xmlhttp.open("POST",url,true);
	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	parametersUrl = "";
	count = 0;
	for (i in parameters)
	{
		if (count > 0)
			parametersUrl += "&";
		parametersUrl += i + "=" +parameters[i];
		count++;
	}
	xmlhttp.send(parametersUrl);
}

</script>
</body>
</html>