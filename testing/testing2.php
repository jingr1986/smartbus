<?php
	require_once("../API_Function.php");
	require_once("../dataReaderFunction.php");
?>

<!DOCTYPE html>
<html>

<head>
	<title> TESTING 2 FOR STOPS</title>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
	<script type="text/javascript">
		console.log("HELLO");
	</script>
</head>

<body>

	<div>
		<span> Line ID: </span>
		<select id="lineID">
			<option value="7474"> 905 </option>
			<option value="7477"> 908 </option>
		</select>
	 	<span> Direction: </span>
	 	<select id="lineDirection"></select>
	 	<button type="button"> Refresh </button>
 	</div>

	<div id="map" style="width: 1500px; height: 800px;"></div>

	<div> 
		<?php
			$lineID = 7474;
			$stops = StopsOnLine($lineID);
			$directionIDArray = getDirectionID($lineID);
			$optionalLocation = readOptionalStopFile(905);

			echo "Start for lop below. <br>";
			//getting all stops for one direction
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
						array_push($directionIDArray[$tempLinedirID], $stops[$i]);
						$tempkey = array_search($tempLinedirID, $tempDirectionIDArray);
						unset($tempDirectionIDArray[$tempkey]);
					}
					if (count($tempDirectionIDArray)==0){
						continue 2;
					}

				}
			}
			print_r(array_values($directionIDArray));
		?>

	</div>

	<script type="text/javascript"> 

		var optional_stops = [22067, 29010, 22187, 22070, 22189, 22188, 22068, 14123, 28137, 22083, 22089, 22165, 22090, 19187, 21358, 12427, 13952, 12438, 25418];
		var direction = <?php echo json_encode(reset(array_slice($directionIDArray, 0, 1))); ?>;
		var sumlat=0, sumlon=0;

		$.each(direction, function(key, val){
			direction[key] = [val.location_name, val.stop_id, val.lat, val.lon];
			sumlat = sumlat+val.lat;
			sumlon = sumlon+val.lon;
		});
		var map = new google.maps.Map(document.getElementById('map'), {
		    zoom: 12,
		    center: new google.maps.LatLng(sumlat/direction.length, sumlon/direction.length),
		    mapTypeId: google.maps.MapTypeId.ROADMAP
    	});
    	var infowindow = new google.maps.InfoWindow();
	    var marker, i;
	    for (i = 0; i < direction.length; i++) {  
	    	var tempStopID = direction[i][1];
	    	if ($.inArray(tempStopID, optional_stops)>=0) {
	    		marker = new google.maps.Marker({
		        	position: new google.maps.LatLng(direction[i][2], direction[i][3]),
		        	map: map,
		        	icon: '../images/bluemarker.png'
		    	});
	    	} else {
		    	marker = new google.maps.Marker({
		        	position: new google.maps.LatLng(direction[i][2], direction[i][3]),
		        	map: map
		    	});
		    }
		    google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
	        		infowindow.setContent(direction[i][0]);
	        		infowindow.open(map, marker);
	        	}
	    	})(marker, i));
		}

		var optionalLocation = <?php echo json_encode($optionalLocation); ?>;
		for (i = 0; i < optionalLocation.length; i++) {  
	    	marker = new google.maps.Marker({
		        	position: new google.maps.LatLng(optionalLocation[i][1], optionalLocation[i][2]),
		        	map: map,
		        	icon: '../images/bluemarker.png'
		    });
		    google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
	        		infowindow.setContent(optionalLocation[i][0]);
	        		infowindow.open(map, marker);
	        	}
	    	})(marker, i));
		}

	</script>

</body>


</html>