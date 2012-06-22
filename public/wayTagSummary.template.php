<p class="waytagsummary" id="waytagsummary_<?=$wayTag["dWayTagObj"]?>">
	<a class="waytaglink" id="waytaglink_<?=$wayTag["dWayTagObj"]?>" href="#" onclick="panMapToWayTag('<?=$wayTag["dWayTagObj"]?>'); showExtraInfo('<?=$wayTag["dWayTagObj"]?>');"><img src="images/locate.png" alt="Pan to location" title="Pan to location" style="position: relative; top:4px;" /><?=$wayTag["cCustomReference"]?></a><br />
	<?php if ($promoter) 
	{
		echo ($promoter->getLastname() . ", " . $promoter->getFirstname());
		$status = $promoter->getOptionalStatus();
		$max_len = 40;
		if (strlen($status) > $max_len)
		{
			$status = substr($status, 0, $max_len-3)."...";
		}
		if ($status && strlen($status) > 0)
		{
			echo ("<br /><q>" . $status) . "</q>";
		}
	} 
	?>
</p>