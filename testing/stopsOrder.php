<!--writen by ran jing  -->
<?php
	require_once '../API_Function.php';
	include '../database.php';
?>

<!DOCTYPE html>
<html>

<head>
	<title> Generate Stops</title>
	<script type="text/javascript">
		console.log("HELLO");
	</script>
</head>

<body>

	


	<div> 
		<?php
			$lineID = 7474;
			$dir = 21;
			$stops = StopsOnLine($lineID);
			$stopsidArray = [];
			$orderID = 1;
			$temp = SpecificNextDepartures($lineID,$stops[0]["stop_id"],$dir,date('2015-02-02\Z'));

						
			echo "Start for lop below. <br>";
			
			$conn = createConnection ();
			
			$test = StoppingPattern(33829, $stops[0]["stop_id"], date('2015-02-02\Z'));									
			$test = reset($test);
							
			foreach($test as $key => $value){
				
				if(count($value)!=0 && !in_array($value["platform"]["stop"]["stop_id"],$stopsidArray)){
					
					array_push($stopsidArray, $value["platform"]["stop"]["stop_id"]);
					$stopID = $value["platform"]["stop"]["stop_id"];
					$dirID = $value["platform"]["direction"]["direction_id"];
					$dirName = $value["platform"]["direction"]["direction_name"];
				
				}
				
				$sql = "INSERT INTO lineStopsOrder (line_id, dir_id, dir_name, order_id, stop_id) VALUES ($lineID, $dirID, '$dirName', $orderID, $stopID)";
				
				if (mysqli_query($conn, $sql)) {
					echo "New record created successfully<br>";
				} else {
					echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
				}

				
				$orderID++;
			}
			
								
		?>

	</div>

	

</body>


</html>