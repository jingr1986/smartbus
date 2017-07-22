<?php
	//Get Data from PTV timetable API
	function getData($_midURL)
	{
		//Set key, id and base url
		//$_devid = "1000284";
		//$_key = "77cce398-3fbf-11e4-8bed-0263a9d0b8a0";
		$_devid = "1000326";
		$_key = "48f9d380-84d1-11e4-a34a-0665401b7368";	
		$_baseURL = "http://timetableapi.ptv.vic.gov.au";
		
		/*
		 *Set signature. Using HMAC-SHA1 hash of the completed request(minus the
		 *base URL but including developer ID.
		 */
		if(strpos($_midURL, '?') > 0)
			$_midURL .= "&devid=".$_devid;
		else
			$_midURL .= "?devid=".$_devid;
		
		$_signature = hash_hmac("sha1",$_midURL,$_key);
		
		/*
		 *Set full request URL
		 */
		$_URL = $_baseURL.$_midURL."&signature=".$_signature;
		//echo "<br> $_URL <br>";

		//Get response as json object and return.
		$_content = file_get_contents($_URL);
		$_content = json_decode($_content,true);
		return $_content;
	}
	
	//find all stops for a specific line
	function StopsOnLine($_line_id)
	{
		$_midURL = "/v2/mode/2/line/$_line_id/stops-for-line";
		$_content = getData($_midURL);
		return $_content;
	}
	
	//
	function BroadNextDepartures($_stop_id)
	{
		$_midURL = "/v2/mode/2/stop/$_stop_id/departures/by-destination/limit/0";
		$_content = getData($_midURL);
		return $_content;
	}
	
	//
	function SpecificNextDepartures($_line_id,$_stop_id,$_direction_id,$_time)
	{
		$_midURL = "/v2/mode/2/line/$_line_id/stop/$_stop_id/directionid/$_direction_id/departures/all/limit/0?for_utc=$_time";
		$_content = getData($_midURL);
		return $_content;
	}
	
	function StoppingPattern($_run_id,$_stop_id,$_time)
	{
		$_midURL = "/v2/mode/2/run/$_run_id/stop/$_stop_id/stopping-pattern?for_utc=$_time";
		$_content = getData($_midURL);
		return $_content;
	}
	
	function MelToutc($time){
			
		$MEL = new DateTimeZone("Australia/Melbourne");
		$UTC = new DateTimeZone("UTC");				
		$date = new DateTime($time, $MEL );
		$date->setTimezone( $UTC );	
		return $date->format('Y-m-d\TH:i:s\Z');
	}	
	
	/*$time = "2015-02-09T03:00:00Z";
	$uts = MelToutc($time);
	echo $uts;*/
	
		
	function utcToMel ($time){
		
		$UTC = new DateTimeZone("UTC");
		$MEL = new DateTimeZone("Australia/Melbourne");
		$date = new DateTime($time, $UTC );
		$date->setTimezone( $MEL );
		return $date->format('Y-m-d\TH:i:s\Z');
		
	}
	function utcToMelDate ($time){
		
		$UTC = new DateTimeZone("UTC");
		$MEL = new DateTimeZone("Australia/Melbourne");
		$date = new DateTime($time, $UTC );
		$date->setTimezone( $MEL );
		return $date->format('Y-m-d');
		
	}
	
	function generateTimetable($lineID,$dirID,$time){
		
	
		$timetable = [];
		$sql="select stop_id from lineStopsOrder where dir_id = $dirID and line_id = $lineID order by order_id ";
		$result = getQueryResult($sql);
		while($row = mysqli_fetch_assoc($result)){
			$timetable[$row['stop_id']] = [];
		}
		
		$conn = createConnection ();
		foreach ($timetable as $key => $value) {
			$stopID = $key;
			$temp = SpecificNextDepartures($lineID, $stopID, $dirID, $time);
			$temp = reset($temp);
			//$timetable1Stop = [];
			foreach($temp as $key2 => $value2){				
				$stopID = $key;				
				$runID = $value2['run']['run_id'];
				$time_utc = $value2["time_timetable_utc"];
				$time_mel = utcToMel($time_utc);
				$utcToMelTime = utcToMel ($time);
				date_default_timezone_set("Australia/Melbourne");
				$date_mel = $utcToMelTime;
				//echo $time;
				$sql = "INSERT INTO timetable (line_id, dir_id, run_id, stop_id, date_mel, time_utc, time_mel) VALUES ($lineID, $dirID, $runID, $stopID, '$date_mel', '$time_utc', '$time_mel')";
				
				if (mysqli_query($conn, $sql)) {
					//echo "New record created successfully<br>";
				} else {
					//echo "Error: " . $sql . "<br>" . mysqli_error($conn). "<br>";
				}
			}
		}
		
	}
	
	
?>