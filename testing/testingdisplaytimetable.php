<?
	require_once("../API_Function.php");
	require_once("../database.php");

	$lineID = 7474;
	$dirID = 21;
	$optID = 4;
	$bookingTime = '2015-02-09 08:00:00';
	$conn = createConnection ();
	displayTimetable($lineID, $dirID, $optID, $bookingTime);
	
	function displayTimetable($lineID, $dirID, $optID, $bookingTime){
		
		$temptime = strtotime($bookingTime);
		$starttime = date('Y-m-d H:i:s',strtotime('-15 minutes', $temptime));
		$endtime = date('Y-m-d H:i:s',strtotime('+60 minutes', $temptime));
		echo $starttime, $endtime;
	
		$sql = "select stop_id from stopsInOrder where dir_id=$dirID and line_id=$lineID order by order_id";
		$result = getQueryResult($sql);
		$stopIDs = [];
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($stopIDs, $row['stop_id']);
		}
	
		echo "StopIDs <br>";
		print_r(array_values($stopIDs));
	
		$sql = "select run_id from timetable where line_id = $lineID and dir_id = $dirID 
			and time_mel < 'endtime'
			and time_mel > 'starttime'
			and stop_id in ( select stop_id
						 	from stopsInOrder
						 	where order_id in ( select order_id-1
						 						from stopsInorder
						 						where line_id = $lineID
						 						and dir_id = $dirID
						 						and stop_id = $optID
						 )
			)
			order by time_mel
			";
			$result = getQueryResult($sql);
			$runIDs = [];
		while ($row = mysqli_fetch_assoc($result) ) {
			if (!in_array($row['run_id'], $runIDs))
			array_push($runIDs, $row['run_id']);
		}
	
		echo "<br>runIDs <br>";
		print_r(array_values($runIDs));
	
		echo "<br>";
		echo "<br>";
	
		//display timetable
		echo "<table style='border: solid;'>";
		
		foreach ($stopIDs as $key => $value) {
			echo "<tr>";
			echo "<td>".getStopName($lineID,$dirID,$value)."</td>";
			$tempStopID = $value;
	
			foreach ($runIDs as $key2 => $value2) {
			

				$sql = "select t.stop_id, run_id, time_mel from timetable as t, stops as s where t.stop_id=s.stop_id and t.stop_id=$value and run_id=$value2";
				$result = getQueryResult($sql);
				if ($row = mysqli_fetch_assoc($result)) {	
										
					$tempTime = $row['time_mel'];
					$tempTime = date("H:i", strtotime($tempTime));
					echo "<td style='text-align: center'>".$tempTime."</td>";
				
				
				} else {
					//echo "<br>".$tempStopID."<br>";
					if($tempStopID == $optID){
						$preStopTime = getPreStopTime($lineID,$dirID,$value2,$optID);
						$preStopTime = date("H:i", strtotime($preStopTime));
						echo "<td style='text-align: center; background-color:red'>".$preStopTime."</td>";					
					}
					else echo "<td style='text-align: center'> --- </td>";
				}
			}
			echo "</tr>";
		}
		echo "</table>";
	}
	
	function getStopName ($lineID,$dirID,$stopID) {
		
		$sql = "select regular from stopsInOrder where line_id = $lineID and dir_id = $dirID and stop_id = $stopID";
		$result = getQueryResult($sql);
		$row = mysqli_fetch_assoc($result);
		$type = $row['regular'];
		
		if($type == 1){

			$sql = "select location_name from stops where stop_id=$stopID";
			$result = getQueryResult($sql);
			$row = mysqli_fetch_assoc($result);
			$stopName = $row['location_name'];
			return $stopName;
			
		}
		
		elseif($type == 0){

			$sql = "select location_name from stopsOpt where stop_id=$stopID";
			$result = getQueryResult($sql);
			$row = mysqli_fetch_assoc($result);
			$stopName = $row['location_name'];
			return $stopName;
			
		}
		
	}
	
	function getPreStopTime($lineID,$dirID,$runID,$optID){
		
		$sql = "select time_mel from timetable where line_id = $lineID and dir_id = $dirID 
				and run_id = $runID
				and stop_id in ( select stop_id
						 	from stopsInOrder
						 	where order_id in ( select order_id-1
						 						from stopsInorder
						 						where line_id = $lineID
						 						and dir_id = $dirID
						 						and stop_id = $optID
						 )
		)";
		$result = getQueryResult($sql);
		$row = mysqli_fetch_assoc($result);
		$preStopTime = $row['time_mel'];
		return $preStopTime;

	}
	
	function sortTimetable(){
		
		$sql = "select time_mel, run_id
			from timetable
			where line_id = $lineID
				and dir_id = $dirID
				and stop_id in (select stop_id 
								from lineStopsOrder 
								where line_id = $lineID 
								and dir_id = $dirID 
								and order_id = 1
								)
			order by time_mel					
		";
		$result = getQueryResult($sql);
		$row = mysqli_fetch_assoc($result);
		$orderRunID = $row['run_id'];
		return $orderRunID;
	}
	
	
	
?>