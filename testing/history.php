<?php
	
	require_once("../database.php");
	
	$passengerID = 0;
	
	
	getBookingHistory ($passengerID);
	
	function getBookingHistory ($passengerID) {
		require_once("../database.php");
		$sql = "select booking_time, line_number, dir_name, location_name, arrival_time from booking as b, passenger as p, line as l, stopsOpt as s, direction as d 
			where b.passenger_id=p.id and b.line_id=l.line_id and s.stop_id=b.stop_id and d.dir_id=b.dir_id and b.line_id=d.line_id and b.passenger_id=$passengerID";
		$result = getQueryResult($sql);
		$html = "<table>";
		$html = $html."<tr> <th>Booking Time</th><th>Line</th><th>Direction</th><th>Stops</th><th>Arrival Time</th> </tr>";
		while ($row=mysqli_fetch_assoc($result)) {
			$html = $html."<tr>";
			$html = $html."<td>".$row['booking_time']."</td>";
			$html = $html."<td>".$row['line_number']."</td>";
			$html = $html."<td>".$row['dir_name']."</td>";
			$html = $html."<td>".$row['location_name']."</td>";
			$html = $html."<td>".$row['arrival_time']."</td>";
			$html = $html."</tr>";
		}
		$html = $html."</table>";
		echo $html;
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
?>