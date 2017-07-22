<?php
	//this code can be used to generate data of each stops
	
	require_once '../API_Function.php';
	include '../database.php';
	
	//703-943 900-1517 901-7531 902-7464 903-7458 905-7474 906-8088 907-7476 908-7477
	$lineIDs = [943, 1517, 7531, 7464, 7458, 7474, 8088, 7476, 7477];
	
	$conn = createConnection ();
	
	for ($i=0; $i<count($lineIDs); $i++) {
		
		$lineID = $lineIDs[$i];
		$stops = StopsOnLine($lineID);
		foreach ($stops as $key => $value) {
			$stopID = $value["stop_id"];
			$suburb = $value["suburb"];
			$location = $value["location_name"];
			$lat = $value["lat"];
			$lon = $value["lon"];
			
			$sql = "INSERT INTO stops (stop_id, suburb, location_name, lat, lon) VALUES ($stopID, '$suburb', '$location', $lat, $lon)";
			if (mysqli_query($conn, $sql)) {
				echo "New record created successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
			}
			
			$sql = "INSERT INTO stopsOfLine (stop_id, line_id) VALUES ($stopID, $lineID)";
			if (mysqli_query($conn, $sql)) {
				echo "New record created successfully<br>";
			} else {
				echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
			}
			
		}
		
	}
	
	mysqli_close($conn);
	
	
	
	
	
	
	
	
	
?>