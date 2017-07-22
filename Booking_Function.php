<?php
	session_start();
	
	//written by Ran Jing
	function selectLineNumber() {
		$sql = "select * from line order by line_number";
		$result = getQueryResult($sql);
		$html = "";
		while ($row=mysqli_fetch_assoc($result)) {
			$html = $html."<option value=".$row['line_id']." >".$row['line_number']."</option>";
		}
		echo $html;
	}
	
	//written by Ran Jing
	function selectDirection ($lineID) {
		require_once('database.php');
		$sql = "select dir_id, dir_name from direction where line_id=$lineID";
		$result = getQueryResult($sql);
		$html = "<option> Select A Direction </option>";
		while ($row=mysqli_fetch_assoc($result)) {
			$html = $html."<option value=".$row['dir_id']."> To ".$row['dir_name']." </option>";
		}
		echo json_encode($html);
	}
	if (isset($_POST['selectDirection'])) { selectDirection($_POST['selectDirection']); }
	
	//written by Jiachen Yan
	function selectStops ($data) {
		$lineID = $data[0];
		$dirID = $data[1];
		require_once('database.php');
		$sql = "select stopsOpt.stop_id, location_name from stopsInOrder, stopsOpt where regular=0 and line_id=$lineID and dir_id=$dirID and stopsInOrder.stop_id=stopsOpt.stop_id order by order_id";
		$result = getQueryResult($sql);
		$html = "<option> Select An Optional Stop </option>";
		while ($row=mysqli_fetch_assoc($result)) {
			$html = $html."<option value=".$row['stop_id']."> ".$row['location_name']." </option>";
		}
		echo json_encode($html);
	}
	if (isset($_POST['selectStops'])) { selectStops($_POST['selectStops']); }
	
	//written by Ran Jing
	function timetableWorkflow ($inputdata){
			
		require_once('database.php');
		require_once('API_Function.php');
		
		$lineID = $inputdata[0];
		$dirID = $inputdata[1];
		$optID = $inputdata[2];		
		$bookingTime = $inputdata[3];
		date_default_timezone_set("UTC");
		$bookingTime = date('Y-m-d H:i:s',strtotime($bookingTime));		
		$bookingUTCTime = MelToutc($bookingTime);
		$html = "";
		
		$check = checkTimetable ($lineID, $dirID, $bookingTime); 
		
		if($check) {
			$html = displayTimetable($lineID, $dirID, $optID, $bookingTime);
		} else {
			generateTimetable($lineID, $dirID, $bookingUTCTime);
			autoDriverAllocation ($lineID, $dirID, $bookingTime);
			$html = displayTimetable($lineID, $dirID, $optID, $bookingTime);
		}
		
		echo json_encode($html);
	}
	if (isset($_POST['timetableWorkflow'])) {timetableWorkflow($_POST['timetableWorkflow']); }
	
	//written by Ran Jing
	function displayTimetable($lineID, $dirID, $optID, $bookingTime){
		
		$html = "";
		$conn = createConnection ();
		date_default_timezone_set("UTC");
		$time = $bookingTime;		
		$temptime = strtotime($time);
		$starttime = date('Y-m-d H:i:s',strtotime('-15 minutes', $temptime));
		$endtime = date('Y-m-d H:i:s',strtotime('+60 minutes', $temptime));
		
		
		$sql = "select stop_id from stopsInOrder where dir_id=$dirID and line_id=$lineID order by order_id";
		$result = getQueryResult($sql);
		$stopIDs = [];
		while ($row = mysqli_fetch_assoc($result)) {
			array_push($stopIDs, $row['stop_id']);
		}
		
		$sql = "select run_id from timetable where line_id = $lineID and dir_id = $dirID 
				and time_mel < '$endtime'
				and time_mel > '$starttime'
				and stop_id in (select stop_id
								from stopsInOrder
								where order_id in (select order_id-1
													from stopsInorder
													where line_id = $lineID
													and dir_id = $dirID
													and stop_id = $optID )	
									and dir_id = $dirID			
				)
				order by time_mel";
		$result = getQueryResult($sql);
		$runIDs = [];
		while ($row = mysqli_fetch_assoc($result) ) {
			if (!in_array($row['run_id'], $runIDs))
				array_push($runIDs, $row['run_id']);
		}

		//display timetable
		$html = "<br><table class='pure-table pure-table-bordered'>";
		foreach ($stopIDs as $key => $value) {
			$html = $html."<tr>";
			$html = $html."<td>".getStopName($lineID,$dirID,$value)."</td>";
			$tempStopID = $value;
			foreach ($runIDs as $key2 => $value2) {
				$sql = "select t.stop_id, run_id, time_mel from timetable as t, stops as s where t.stop_id=s.stop_id and t.stop_id=$value and t.run_id=$value2";
				$result = getQueryResult($sql);
				if ($row = mysqli_fetch_assoc($result)) {					
					$tempTime = $row['time_mel'];
					$tempTime = date("H:i", strtotime($tempTime));
					$html = $html."<td style='text-align: center'>".$tempTime."</td>";
				} else {
					if($tempStopID == $optID){
						$preStopTime = getPreStopTime($lineID,$dirID,$value2,$optID);
						$preStopTime = date("H:i", strtotime($preStopTime));
						$html = $html."<td style='text-align: center; background-color: #cc0000; color: white'><label><input class='optCheckbox' type='checkbox' name=$value2 value='$preStopTime'> ".$preStopTime."</label></td>";
					}
					else $html = $html."<td style='text-align: center'> --- </td>";
				}
			}
			$html = $html."</tr>";
		}
		$html = $html."</table> <br> <input id='bookChecked' type='button' class='pure-button pure-button-primary' value='Book Selected Stop(s)'> <br>";
		$html = $html."<div id='bookingFeedback'> </div>";
		return $html;
	}
 	
 	//written by Ran Jing
 	function checkTimetable ($lineID, $dirID, $time){
	 	require_once("database.php");
		$check = 0;
		$sql = "select distinct date_mel from timetable";
		$result = getQueryResult($sql);
		$tempDate = [];
		while($row = mysqli_fetch_assoc($result)){
			$temp = $row['date_mel'];
			array_push($tempDate, $temp);
		}
		$checkDate = date('Y-m-d',strtotime($time));
		foreach($tempDate as $key => $value){
			$haveDate = $value;
			if($checkDate == $haveDate){
				$check = 1;
				break;
			}
			$check = 0;
		}
		return $check;
	}
	
	
	//written by Ran Jing
	function getStopName ($lineID,$dirID,$stopID) {
		require_once('database.php');
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
	
	//written by Ran Jing
	function getPreStopTime($lineID,$dirID,$runID,$optID){	
		require_once('database.php');
		$sql = "select time_mel from timetable where line_id = $lineID and dir_id = $dirID 
				and run_id = $runID
				and stop_id in ( select stop_id
						 	from stopsInOrder
						 	where order_id in ( select order_id-1
						 						from stopsInorder
						 						where line_id = $lineID
						 						and dir_id = $dirID
						 						and stop_id = $optID)
						 		and dir_id = $dirID
		)";
		$result = getQueryResult($sql);
		$row = mysqli_fetch_assoc($result);
		$preStopTime = $row['time_mel'];
		return $preStopTime;
	}

	/*function sortTimetable($lineID,$dirID,$runID,){
		
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
		$return $orderRunID;
	}*/

	//written by Jiachen Yan
	function autoDriverAllocation ($lineID, $dirID, $bookingTime) {
		require_once('database.php');
		$date_mel = date("Y-m-d", strtotime($bookingTime));
		$sql = "select distinct run_id from timetable where date_mel='$date_mel' and line_id=$lineID and dir_id=$dirID order by time_mel";
		$runIDsResult = getQueryResult($sql);
		$sql = "select id from driver";
		$driverIDsResult = getQueryResult($sql);
		$driverIDs =[];
		while ($row = mysqli_fetch_assoc($driverIDsResult)){
			array_push($driverIDs, $row['id']);
		}
		$count = 0;
		$conn = createConnection();
		while ($row = mysqli_fetch_assoc($runIDsResult)) {
			$runID = $row['run_id'];
			$driverID = $driverIDs[$count];
			$sql = "INSERT shifts (date_mel, line_id, dir_id, run_id, driver_id) Values ('$date_mel', $lineID, $dirID, $runID, $driverID)";
			mysqli_query($conn, $sql);
			$count++;
			if ($count>=count($driverIDs))
				$count = 0;
		}
		$conn -> close();
	}


	//written by Ran Jing
	function createBooking($inputdata) {
		
		require_once('database.php');
		require_once('API_Function.php');
		$passengerUsername = $_SESSION['passenger']['username'];
		$lineID = $inputdata[0];
		$dirID = $inputdata[1];
		$stopID = $inputdata[2];
		$runID = $inputdata[3];
		$arrivaltime = $inputdata[4];
		
		date_default_timezone_set("UTC");
		$bookingTime_utc = date("Y-m-d\TH:i:s\Z");
		$constraint_utc = date("Y-m-d\TH:i:s\Z", strtotime("+15 minutes"));
		$bookingTime = utcToMel ($bookingTime_utc);
		$constraint = utcToMel ($constraint_utc);

		$sql1 = "select * from passenger where username='$passengerUsername'";
		$result = getQueryResult($sql1);
		$html ="";
		if ( strtotime($arrivaltime) > strtotime($constraint) ) {
			while ($row = mysqli_fetch_assoc($result)) {
				$passengerid = $row['id'];
				$sql3 = "select count(*) from booking where passenger_id=$passengerid and line_id=$lineID and dir_id=$dirID and stop_id=$stopID and run_id=$runID and arrival_time='$arrivaltime'";
				$result3 = getQueryResult($sql3);
				$row3 = mysqli_fetch_assoc($result3);
				if ($row3['count(*)']!=0) {
					$html = $html."<p style='color:#cc0000;'> <b><i class='fa fa-exclamation-triangle'></i> WARNING</b>: You have booked this stop before. This booking action is not successful.</p>";
				} else {
					$sql2 = "INSERT INTO booking (passenger_id, line_id, dir_id, stop_id, run_id, arrival_time, booking_time) VALUES ($passengerid, $lineID, $dirID, $stopID, $runID, '$arrivaltime', '$bookingTime')";
					$html = $html;
					$conn = createConnection ();
					if(mysqli_query($conn, $sql2)) {
						$html = $html."<p> <i class='fa fa-check-square-o'></i> ".getStopName ($lineID, $dirID, $stopID)." at ".date("H:i", strtotime($arrivaltime))." is booked successfully.</p>";
					} else {
						$html = $html."<p style='color:#cc0000;'> <b><i class='fa fa-exclamation-triangle'></i>WARNING</b>: ".getStopName ($lineID, $dirID, $stopID)." at ".date("H:i", strtotime($arrivaltime))." is booked <b>UNSUCCESSFULLY</b>.</p>";
					}
				}
			}
		} else {
			$html = $html."<p style='color:#cc0000;'> <b><i class='fa fa-exclamation-triangle'></i>WARNING</b>: The bus has passed this stop or the booking time is not 15 minutes in advance. Your booking is not successful.";
		}
		echo json_encode($html);
	}
	if (isset($_POST['createBooking'])) { createBooking($_POST['createBooking']); }
	
	//written by Ran Jing
	function displayBookingHistory () {
		
		require_once('database.php');
		
		$passengerUsername = $_SESSION['passenger']['username'];
		$sql1 = "select * from passenger where username='$passengerUsername'";
		$result = getQueryResult($sql1);
		$row = mysqli_fetch_assoc($result);
		$passengerid = $row['id'];
		
		$sql2 = "select booking_time, line_number, dir_name, location_name, arrival_time from booking as b, passenger as p, line as l, stopsOpt as s, direction as d 
			where b.passenger_id=p.id and b.line_id=l.line_id and s.stop_id=b.stop_id and d.dir_id=b.dir_id and b.line_id=d.line_id and b.passenger_id=$passengerid order by booking_time desc";
		$result = getQueryResult($sql2);
		$html =  "<div id='historydiv'><table class='pure-table pure-table-bordered'><caption><h2><i class='fa fa-history'></i> Booking History </h2></caption>";
		$html = $html."<thead style='text-align: center'> <tr> <th>Booking Time</th><th>Line</th><th>Direction</th><th>Stops</th><th>Arrival Time</th> </tr> </thead> <tbody>";
		while ($row=mysqli_fetch_assoc($result)) {
			$html = $html."<tr>";
			$html = $html."<td>".$row['booking_time']."</td>";
			$html = $html."<td>".$row['line_number']."</td>";
			$html = $html."<td>".$row['dir_name']."</td>";
			$html = $html."<td>".$row['location_name']."</td>";
			$html = $html."<td>".$row['arrival_time']."</td>";
			$html = $html."</tr>";
		}
		$html = $html."</tbody></table>";
		$html = $html."<input type='button' id='historyBack' class='button-success pure-button' onclick='location.href=\"login.php\";' value='Back'></div>";
		echo  json_encode($html);
	}
	if (isset($_POST['displayBookingHistory'])) { displayBookingHistory(); }
?>