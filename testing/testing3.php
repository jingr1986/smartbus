<!Doctype html>
<!-- written by Jiachen Yan -->
<?php require("../systemView_Function.php");  ?>

<html>
	<head>
		<meta charset="utf-8">
		
		<link rel="stylesheet" type="text/css" href="../css/mystyle.css"/>
		<link rel="stylesheet" type="text/css" href="../css/font-awesome-4.3.0/css/font-awesome.min.css"/>
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
		
		<title>System View</title>
	</head>

	<body>
			
		<div style="background-color: white">
			
			<?php include '../include/top.inc';?>
			<div style="text-align: center">
				<h2>System View</h2>
				<div id="map" style="width: 1072px; height: 663px; margin: auto;"></div>
				<div id="legend">
					<img src="../images/originalmarker.png">
					<span> Regular Stops </span>
					<img src="../images/bluemarker.png">
					<span> Optional Stops </span>
				</div>
				<script>
					var dirID = 33;
					var lineID = 7474;
					var stopsReg, stopsOpt;
					$.ajax({
					    url: "../systemView_Function.php",
					    type: "POST",
					    data: {"ran": [lineID, dirID]},
					    dataType: "JSON",
					    async: false,
					    success: function(data) {  	
							stopsReg = data;
						}
					});
					console.log(stopsReg);
					$.ajax({
					    url: "../systemView_Function.php",
					    type: "POST",
					    data: {"getOptionalStopsLocation": [lineID, dirID]},
					    dataType: "JSON",
					    async: false,
					    success: function(data) {  	
							stopsOpt = data;
						}
					});
					console.log(stopsOpt);
					putMarkerOnMap(stopsReg, stopsOpt);
					
					function putMarkerOnMap(dataReg, dataOpt){
						if (dataReg.length>0 && dataOpt.length>0) {
							var sumlat=0, sumlon=0;
							$.each(dataReg, function(key, val){
								sumlat = sumlat+dataReg[key][1];
								sumlon = sumlon+dataReg[key][2];
							});
							var map = new google.maps.Map(document.getElementById('map'), {
							    zoom: 12,
							    center: new google.maps.LatLng(sumlat/dataReg.length, sumlon/dataReg.length),
							    mapTypeId: google.maps.MapTypeId.ROADMAP
							});
							
							var infowindow = new google.maps.InfoWindow();
						    var marker, i;
						    for (i = 0; i < dataReg.length; i++) {  
						    	marker = new google.maps.Marker({
						        	position: new google.maps.LatLng(dataReg[i][1], dataReg[i][2]),
						        	map: map
						    	});
							    google.maps.event.addListener(marker, 'click', (function(marker, i) {
							        return function() {
						        		infowindow.setContent(dataReg[i][0]);
						        		infowindow.open(map, marker);
						        	}
						    	})(marker, i));
							}
							for (i = 0; i < dataOpt.length; i++) {  
						    	marker = new google.maps.Marker({
						        	position: new google.maps.LatLng(dataOpt[i][1], dataOpt[i][2]),
						        	map: map,
						        	icon: '../images/bluemarker.png'
						    	});
							    google.maps.event.addListener(marker, 'click', (function(marker, i) {
							        return function() {
						        		infowindow.setContent(dataOpt[i][0]);
						        		infowindow.open(map, marker);
						        	}
						    	})(marker, i));
							}
							
						}
					}
				</script>	
				
				
				
			</div>
			
		</div>
	
	</body>
	
</html>