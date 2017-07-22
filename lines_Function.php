<?php
	
	// this file is written by Jiachen Yan
	function showLineNumber() {
		$sql = "select line_number from line order by line_number";
		$result = getQueryResult($sql);
		while ($row = mysqli_fetch_assoc($result)) {
			echo "<button class='pure-button'> <i class='fa fa-bus'></i> ".$row['line_number']." </button>";
		}
	}
	
	function getDirectionInfo($lineNumber) {
		require_once 'database.php';
		$sql ="select b.line_id, line_number, b.dir_id, b.dir_name from line as a, direction as b where a.line_id = b.line_id and line_number=$lineNumber";
		$result = getQueryResult($sql);
		$count = 0;
		while ($row = mysqli_fetch_assoc($result)) {
			if ($count==0)
				$dirinfo = $dirinfo."<p><button class='pure-button' id='dirA' value=".$row['dir_id']." name=".$row['line_id']."> <i class='fa fa-arrow-circle-right'></i>  ".$row['dir_name']." </button></p>";
			else
				$dirinfo = $dirinfo."<p><button class='pure-button' id='dirB' value=".$row['dir_id']." name=".$row['line_id']."> <i class='fa fa-arrow-circle-right'></i>  ".$row['dir_name']." </button></p>";
			$count++;
		}
		echo json_encode($dirinfo);
	}
	
	function getRegularStopsLocation($lineID, $dirID) {
		require_once 'database.php';
		$sql = "select location_name, lat, lon from stopsinorder, stops where regular=1 and line_id=$lineID and dir_id=$dirID and stopsinorder.stop_id=stops.stop_id order by order_id";
		$result = getQueryResult($sql);
		$stopsLocation = [];
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($stopsLocation, [$row['location_name'], floatval($row['lat']), floatval($row['lon'])]);
		}
		echo json_encode($stopsLocation);
	}
	
	function getOptionalStopsLocation($lineID, $dirID) {
		require_once 'database.php';
		$sql = "select location_name, lat, lon from stopsinorder, stopsOpt where regular=0 and line_id=$lineID and dir_id=$dirID and stopsinorder.stop_id=stopsOpt.stop_id order by order_id";
		$result = getQueryResult($sql);
		$stopsLocation = [];
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($stopsLocation, [$row['location_name'], floatval($row['lat']), floatval($row['lon'])]);
		}
		echo json_encode($stopsLocation);
	}
	
	if (isset($_POST['getDirectionInfo'])) { getDirectionInfo($_POST['getDirectionInfo']); }
	if (isset($_POST['getRegularStopsLocation'])) { getRegularStopsLocation($_POST['getRegularStopsLocation'][0], $_POST['getRegularStopsLocation'][1]); }
    if (isset($_POST['getOptionalStopsLocation'])) { getOptionalStopsLocation($_POST['getOptionalStopsLocation'][0], $_POST['getOptionalStopsLocation'][1]); }
	
	
	//temp use for testing
	function ran($lineID, $dirID) {
		$test;
		$filepath = "data/stops/regular/$lineID/$dirID.xls";
		$stopsLocation = [];
		if (file_exists($filepath)) {
			$file = fopen($filepath, rb);
			rewind($file);
			while(!feof($file)){
				$oneline = fgets($file);
				$temp = explode("\t", $oneline);
				array_push($stopsLocation, [$temp[0], floatval(trim($temp[3])), floatval(trim($temp[4]))]);
			}
			fclose($file);
		}
		echo json_encode($stopsLocation);
	}
	if (isset($_POST['ran'])) { ran($_POST['ran'][0], $_POST['ran'][1]); }
	
	
	
	
?>