<?php
	
	echo "start create booking <br>";
	
	session_start();
	echo "session array: ";
	print_r($_SESSION);
	echo "<br>";
	
	createBooking([7474, 21, 4, 42195, "2015-02-02T09:28:00Z"]);
	
	function createBooking($inputdata) {
		
		require_once('../database.php');
		
		$passengerUsername = $_SESSION['passenger']['username'];
		$lineID = $inputdata[0];
		$dirID = $inputdata[1];
		$stopID = $inputdata[2];
		$runID = $inputdata[3];
		$arrivaltime = $inputdata[4];
		$bookingTime_utc = date("Y-m-d\TH:i:s\Z");

		$sql1 = "select * from passenger where username='$passengerUsername'";
		$result = getQueryResult($sql1);
		$html ="";
		while ($row = mysqli_fetch_assoc($result)) {
			$passengerid = $row['id'];
			$passengerid = 9;
			$sql2 = "INSERT INTO booking (passenger_id, line_id, dir_id, stop_id, run_id, arrival_time, booking_time) VALUES ($passengerid, $lineID, $dirID, $stopID, $runID, '$arrivaltime', '$bookingTime_utc')";
			$conn = createConnection ();
			if(mysqli_query($conn, $sql2)) {
				$html = $html."<p> The stop $stopID at ".date("H:i", strtotime($arrivaltime))." is booked successful.  </p>";
			} else {
				$html = $html."<p style='color: rgb(202, 60, 60);'> The stop $stopID at ".date("H:i", strtotime($arrivaltime))." is booked unsuccessful.  </p>";
			}
		}
		echo json_encode($html);
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>