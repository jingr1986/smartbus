<?php
	require_once("API_Function_testing.php");
	require_once("../database.php");
	
	$lineID = 7474;
	$dirID = 21;
	date_default_timezone_set("UTC");
	$time = date("Y-m-d\TH:i:s\Z");
	echo "time is ".$time."<br>";
	$temp = date("Y-m-d\TH:i:s\Z",strtotime($time));
	echo "temp is ".$temp."<br>";
	//StopsOnLine($lineID);
	//BroadNextDepartures(22066);

	//SpecificNextDepartures($lineID, 22066, $dirID, $time);

	
	generateTimetable($lineID,$dirID, '2015-02-10');

	echo "DONE";
	/*
	displayTimetable ($lineID, $dirID, $time);

	function displayTimetable ($lineID, $dirID, $time) {
		$file = fopen("$time.xls", r);
		rewind($file);
		echo "<table>";
		$stopsIDName = getStopNameArray ($lineID, $dirID);
		while (!feof($file)) {
			$oneline = fgets($file);
			$temp = explode("\t", $oneline);
			echo "<tr>";
			echo "<td>".$stopsIDName[$temp[0]]."</td>";
			$temp[2] = utcToMel ($temp[2]);
			echo "<td> $temp[2] </td>";
			echo "</tr>";
		}
		echo "</table>";
		fclose($file);
	}
	
	function getStopNameArray ($lineID, $dirID) {
		$file =fopen("data/stops/regular/$lineID/$dirID.xls", r);
		rewind($file);
		$stopsIDName = [];
		while (!feof($file)) {
			$oneline = fgets($file);
			$temp = explode("\t", $oneline);
			$stopsIDName[$temp[0]] = $temp[2];
		}
		fclose($file);
		return $stopsIDName;
	}
	*/
	
	
?>