<?php
	
	require_once '../API_Function.php';
	require_once '../database.php';
	
	$conn = createConnection ();
	
	$lineIDs = [943, 1517, 7531, 7464, 7458, 7474, 8088, 7476, 7477];
	$allDirections = [];
	
	for($j=0; $j<count($lineIDs); $j++){
		
		$dirIDs =[];
		$lineID = $lineIDs[$j];
		$stops = StopsOnLine($lineID);
		$tempdirID = 0;
		
		for ($i=0; $i<count($stops); $i++) {
			$stopID = $stops[$i]['stop_id'];
			echo "<br> stop id is ".$stopID."<br>";
			$bnd = BroadNextDepartures($stopID);
			$bnd = reset($bnd);
			foreach ($bnd as $key => $value) {
				$dirID = $value["platform"]["direction"]["direction_id"];
				$dirName = $value["platform"]["direction"]["direction_name"];
				if ($value['platform']['direction']['line']['line_id']==$lineIDs[$j] && !in_array($dirID, array_keys($dirIDs)) ) {
					$dirIDs[$dirID] = $dirName;					
					$sql = "select dir_id from direction where line_id = $lineIDs[$j] and dir_name = '$dirName'";
					$result = getQueryResult($sql);
					while($row = mysqli_fetch_assoc($result)){
						$tempdirID = $row['dir_id'];
					}
					echo "<br>dir id is ".$tempdirID."<br>";
					if($dirID != $tempdirID){
						$sql = "update direction set dir_id = $dirID where line_id = $lineIDs[$j] and dir_name = '$dirName'";
						if (mysqli_query($conn, $sql)) {
							echo "update successfully<br>";
							} else {
							echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
						}
					}
					if (count($dirIDs)==2){
						$allDirections[$lineIDs[$j]]=$dirIDs;
						print_r(array_values($dirIDs));
						continue 3;
					}
				}	
			}
		}	
	}
	
	print_r(array_values($allDirections));
	
	//generate direction table
	/*foreach($allDirections as $key => $value){
		
		foreach($value as $key2 => $value2){
			$lineID = $key;
			$dirID = $key2;
			$dirName = $value2;
			
			$sql = "INSERT INTO direction ( dir_id,line_id,dir_name) VALUES ($dirID, $lineID, '$dirName')";
			echo $sql;
			if (mysqli_query($conn, $sql)) {
					echo "New record created successfully<br>";
				} else {
					echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
				}
		}
	}*/
	
	
				
	
	
?>