<?php
	require_once("dataReaderFunction.php");
	require_once("Common_Function.php");
?>

<!DOCTYPE html>
<html>

<head>
	<title> TESTING FOR TIMETABLE 905</title>
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
			$directionIDArray = getDirectionID($lineID);
			$runidArray = [];
			$linedirArray = [];
			$alltableArrayA = [];
			$alltableArrayB = [];
			$tableA = [];
			$content = "";
			$optionalLocation = readOptionalStopFile(905);
			$time123 = date('Y-m-d\Z'));
			$runids = getrunid($lineID,$dir,date('Y-m-d\Z'));
			print_r(array_values($runids));

			
			//echo "DATE : ".date("Y/m/d H:i:s");
			//echo "TIME ZONE: ".date_default_timezone_get()."\n";	

			echo "Start for lop below. <br>";
			//getting all stops for one direction
			
			for ($i=0; $i<count($stops); $i++) {
				
				$temp = StoppingPattern(43100,$stops[$i]["stop_id"],date('2015-01-27\Z'));
				
				$test = SpecificNextDepartures($lineID,$stops[$i]["stop_id"],$dir,date('2015-01-25\Z'));	
					
				$test = reset($test);
				
				foreach($test as $key => $value){
					
					if(count($value)!=0){
						
						//$content = $content.($value["platform"]["stop"]["stop_id"]."/t".$value["platform"]["stop"]["location_name"]."/t".$value["platform"]["stop"]["lat"]"\n");
						$content = $content.$value["platform"]["stop"]["stop_id"];
						$content = $content."\t".$value["platform"]["stop"]["location_name"];
						$content = $content."\t".$value["run"]["run_id"];					
						$timetableUTCTime = $value["time_timetable_utc"];
						$time = strtotime($timetableUTCTime);
						date_default_timezone_set("Australia/Melbourne");
						$dateInLocal = date('Y-m-d\TH:i:s\Z',$time);
						
						$content = $content."\t".$dateInLocal."\n";
					}
				}
			}
			
			
			$file = fopen("testing.xls",w);
			fwrite($file, $content);
			fclose($file);
			
			
			for ($i=0; $i<count($stops); $i++) {

				$tempDirectionIDArray = array_keys($directionIDArray);
				$oneStopID = $stops[$i]["stop_id"];
				$allDayDeparture = BroadNextDepartures($oneStopID);
				$allDayDeparture = reset($allDayDeparture);
				
				
				echo "one stop id is ", $oneStopID, "<br>";
				
				foreach ($allDayDeparture as $key => $value) {	

					$tempLineID = $value["platform"]["direction"]["line"]["line_id"];					
					$tempLinedirID = $value["platform"]["direction"]["linedir_id"];				
					
					
					if (in_array($tempLinedirID, $tempDirectionIDArray) && $tempLineID==$lineID) {
						
						if(!in_array($runid, $runidArray)){
							array_push($runidArray,$runid);
						}
						
						if(!in_array($tempLinedirID,$linedirArray)){						
							array_push($linedirArray,$tempLinedirID);
						}
					
						if($tempLinedirID == $linedirArray[0]){
                                   array_push($alltableArrayA, $value);
                              }
                        else if($tempLinedirID == $linedirArray[1]){
	                        array_push($alltableArrayB, $value);
						}
						
						$tempkey = array_search($tempLinedirID, $tempDirectionIDArray);
						unset($tempDirectionIDArray[$tempkey]);

					}
					
					if (count($tempDirectionIDArray)==0){
						continue 2;
					}
					
				}
				print_r(array_values($runidArray));

			}
			
			$temp123 = generateTimetable($lineID,$dir,$time123);
			
		?>

	</div>

	

</body>


</html>