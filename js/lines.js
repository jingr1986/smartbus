// wriiten by Jiachen Yan
$(document).ready(function(){
	
	//init the map on the page
	var map = new google.maps.Map(document.getElementById('map'), {
	    zoom: 13,
	    center: new google.maps.LatLng(-37.813611, 144.963056),
	    mapTypeId: google.maps.MapTypeId.ROADMAP
	});
	
	$(".pure-button").click(function(){
		$(".pure-button").each(function(){
			$(this).css({"background-color":"#e6e6e6", "color":"black"});
		});
		$(this).css({"background-color":"#cc0000", "color":"white"});
		$("#directioninfo").empty();
		var lineNumber = parseInt($(this).text());
		$.ajax({
		    url: "./lines_Function.php",
		    type: "POST",
		    data: {"getDirectionInfo": lineNumber},
		    dataType: "JSON",
		    success: function(data) {  	
				$("#directioninfo").append(data);
			}
		});
	})
	
	$("#directioninfo").on("click", '#dirA', function(){
		$(this).css({"background-color":"#cc0000", "color":"white"});
		$("#dirB").css({"background-color":"#e6e6e6", "color":"black"});
		updateMapWithStops("dirA");
	})
	
	$("#directioninfo").on("click", '#dirB', function(){
		$(this).css({"background-color":"#cc0000", "color":"white"});
		$("#dirA").css({"background-color":"#e6e6e6", "color":"black"});
		updateMapWithStops("dirB");
	})
	
	function updateMapWithStops(btnID){
		var dirID = document.getElementById(btnID).value;
		var lineID = document.getElementById(btnID).name;
		var stopsReg, stopsOpt;
		$.ajax({
		    url: "./lines_Function.php",
		    type: "POST",
		    data: {"getRegularStopsLocation": [lineID, dirID]},
		    dataType: "JSON",
		    async: false,
		    success: function(data) {
				stopsReg = data;
			}
		});
		$.ajax({
		    url: "./lines_Function.php",
		    type: "POST",
		    data: {"getOptionalStopsLocation": [lineID, dirID]},
		    dataType: "JSON",
		    async: false,
		    success: function(data) {
				stopsOpt = data;
			}
		});
		putMarkerOnMap(stopsReg, stopsOpt);
	}
	
	function putMarkerOnMap(dataReg, dataOpt){
		if (dataReg.length>0 || dataOpt.length>0) {
			var sumlat=0, sumlon=0;
			$.each(dataReg, function(key, val){
				sumlat = sumlat + parseFloat(dataReg[key][1]);
				sumlon = sumlon + parseFloat(dataReg[key][2]);
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
		        	icon: 'images/bluemarker.png'
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
	
})