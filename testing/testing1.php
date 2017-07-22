<!DOCTYPE html>
<html>
<head>
	<title>TESTING PAGE</title>
	<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
</head>
<body>

	<div id="map" style="width: 1500px; height: 800px;"></div>

  	<script type="text/javascript">

		var url = 'http://timetableapi.ptv.vic.gov.au/v2/mode/2/line/7474/stops-for-line?devid=1000326&signature=d33cf2b8fcfe1fbc31c56eb0aff23af68a2377b6';

		$.getJSON(url, function(data){

			var locations = [];
			var sumlat=0, sumlon=0;
			$.each(data, function(key, val){
				locations[key] = [val.location_name, val.lat, val.lon];
				sumlat = sumlat+val.lat;
				sumlon = sumlon+val.lon;
			})

			console.log(locations.length);

			var map = new google.maps.Map(document.getElementById('map'), {
			    zoom: 12,
			    center: new google.maps.LatLng(sumlat/locations.length, sumlon/locations.length),
			    mapTypeId: google.maps.MapTypeId.ROADMAP
	    	});

		    var infowindow = new google.maps.InfoWindow();

		    var marker, i;

		    for (i = 0; i < locations.length; i++) {  
		    	marker = new google.maps.Marker({
		        	position: new google.maps.LatLng(locations[i][1], locations[i][2]),
		        	map: map
		    	});

			    google.maps.event.addListener(marker, 'click', (function(marker, i) {
			        return function() {
		        		infowindow.setContent(locations[i][0]);
		        		infowindow.open(map, marker);
		        	}
		    	})(marker, i));
			}
		});
	</script>


</body>
</html>
