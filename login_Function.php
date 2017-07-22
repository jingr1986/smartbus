<?php

	session_start();
	
	//Passenger login function
	function passengerLogin($passengerUsername, $password) {
		
		$sql = "select count(username) as total from passenger where username='$passengerUsername' and password='$password'";
		$result = getQueryResult($sql);
		$row = mysqli_fetch_assoc($result);

		$_SESSION['passenger']['status'] = false;
		if ($row['total']==1) {
			$_SESSION['passenger']['username'] = $passengerUsername;
			$_SESSION['passenger']['status'] = true;
		} 
		else
			throw new customException("Invalid ID or Password!");
	}
	
	//Driver Login
	function driverLogin($driverUsername, $password) {
		
		$sql = "select count(username) as total from driver where username='$driverUsername' and password='$password'";
		$result = getQueryResult($sql);
		$row = mysqli_fetch_assoc($result);
		
		$_SESSION['driver']['status'] = false;
		if ($row['total']==1) {
			$_SESSION['driver']['username'] = $driverUsername;
			$_SESSION['driver']['status'] = true;
		}
		else
			throw new customException("Invalid ID or Password!");
	}

	//should move to driver_funtion.php later
	
	function loadShiftDate() {
		$driverUsername = $_SESSION['driver']['username'];
		$sql = "select distinct date_mel from shifts where driver_id=(select id from driver where username='$driverUsername') order by date_mel desc";
		$result = getQueryResult($sql);
		$html="";
		while ($row=mysqli_fetch_assoc($result))
			$html = $html."<option value=".$row['date_mel'].">".$row['date_mel']."</option>";
		echo $html;
	}
	
	function displayShiftsInit ($date) {
		$driverUsername = $_SESSION['driver']['username'];
		$html = "";
		$sql1 = "select * from shifts as s, line as l , direction as d
			where date_mel='$date' and driver_id=(select id from driver where username='D001') 
				and l.line_id=s.line_id and s.line_id=d.line_id and s.dir_id=d.dir_id
			order by s.line_id, s.dir_id, s.run_id";
		$result1 = getQueryResult($sql1);
		if (mysqli_num_rows($result1)>0) {
			while ($row1=mysqli_fetch_assoc($result1)) {
				$lineNumber = $row1['line_number'];
				$lineID = $row1['line_id'];
				$dirName = $row1['dir_name'];
				$dirID = $row1['dir_id'];
				$runID = $row1['run_id'];
				$sql2 = "select time(time_mel) as time from timetable where line_id=$lineID and dir_id=$dirID and date_mel='$date' and time_mel>'$date 03:00:00' and run_id=$runID order by time_mel limit 1";
				$result2 = getQueryResult($sql2);
				while ($row2 = mysqli_fetch_assoc($result2)) {
					$time = $row2['time'];
					$time = substr($time, 0, 5);
					$html= $html."<input type='button' class='pure-button' value='$lineNumber To $dirName start at $time'>";
				}
			}
		} else {
			$html = $html."<p> No Shifts Today. </p>";
		}
		echo $html;
	}
	
	function displayShifts ($date) {
		require_once("database.php");
		$driverUsername = $_SESSION['driver']['username'];
		$html = "";
		$sql1 = "select * from shifts as s, line as l , direction as d
			where date_mel='$date' and driver_id=(select id from driver where username='D001') 
				and l.line_id=s.line_id and s.line_id=d.line_id and s.dir_id=d.dir_id
			order by s.line_id, s.dir_id, s.run_id";
		$result1 = getQueryResult($sql1);
		while ($row1=mysqli_fetch_assoc($result1)) {
			$lineNumber = $row1['line_number'];
			$lineID = $row1['line_id'];
			$dirName = $row1['dir_name'];
			$dirID = $row1['dir_id'];
			$runID = $row1['run_id'];
			$sql2 = "select time(time_mel) as time from timetable where line_id=$lineID and dir_id=$dirID and date_mel='$date' and time_mel>'$date 03:00:00' and run_id=$runID order by time_mel limit 1";
			$result2 = getQueryResult($sql2);
			while ($row2 = mysqli_fetch_assoc($result2)) {
				$time = $row2['time'];
				$time = substr($time, 0, 5);
				$html= $html."<input type='button' class='pure-button' data-line-id=$lineID data-dir-id=$dirID data-run-id=$runID value='$lineNumber To $dirName start at $time'>";
			}
		}
		echo json_encode($html);
	}
	if (isset($_POST['displayShifts'])) { displayShifts($_POST['displayShifts']); }
	
	function displayShiftTimetable ($inputData) {
		require_once("database.php");
		$date = $inputData[0];
		$lineID = $inputData[1];
		$dirID = $inputData[2];
		$runID = $inputData[3];
		
		$html = $html."<table class='pure-table pure-table-bordered' style='margin: 20px auto 20px auto'>";
		
		$sql1 = "select * from stopsInOrder where line_id=$lineID and dir_id=21 order by order_id";
		$result1 = getQueryResult($sql1);
		while ($row1 = mysqli_fetch_assoc($result1)) {
			$html = $html."<tr>";
			if ($row1['regular']) {
				$stop_id = $row1['stop_id'];
				$sql2 = "select * from stops where stop_id=$stop_id";
				$result2 = getQueryResult($sql2);
				$row2 = mysqli_fetch_assoc($result2);
				$html = $html."<td>".$row2['location_name']."</td>";
			} else {
				$stop_id = $row1['stop_id'];
				$sql2 = "select * from booking where line_id=$lineID and dir_id=$dirID and stop_id=$stop_id and run_id=$runID and arrival_time like '%$date%';";
				$result2 = getQueryResult($sql2);
				if (mysqli_num_rows($result2)>0) {
					$sql3 = "select * from stopsOpt where stop_id=$stop_id";
					$result3 = getQueryResult($sql3);
					$row3 = mysqli_fetch_assoc($result3);
					$html = $html."<td style='background-color: #cc0000; color: white'>".$row3['location_name']."</td>";
				}
			}
			$html = $html."</tr>";
		}
		$html = $html."</table>";
		echo json_encode($html);
	}
	if (isset($_POST['displayShiftTimetable'])) { displayShiftTimetable($_POST['displayShiftTimetable']); }
?>