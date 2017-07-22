// wriiten by Jiachen Yan
$(document).ready(function(){
	
	$("#selectLine").change(function(){
		$("#selectDirection").empty();
		$("#selectRunDate").empty();
		document.getElementById("selectRunDate").disabled = true;
		$("#runs").empty();
		var lineID = $("#selectLine").val();
		$.ajax({
		    url: "./Booking_Function.php",
		    type: "POST",
		    data: {"selectDirection": lineID},
		    dataType: "JSON",
		    success: function(data) {  	
				$("#selectDirection").html(data);
			}
		});	
		
	})
	
	$("#selectDirection").change(function(){
		var lineID = $("#selectLine").val();
		var dirID = $("#selectDirection").val();
		$("#runs").empty();
		
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"selectDate": [lineID, dirID]},
		    dataType: "JSON",
		    success: function(data) { 
				$("#selectRunDate").html(data);
				document.getElementById("selectRunDate").disabled = false;
			}
		});		
		
	})
	
	$("#getRunsBtn").click(function(){
		var lineID = $("#selectLine").val();
		var dirID = $("#selectDirection").val();
		var runDate = $("#selectRunDate").val();
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"getRuns": [runDate, lineID, dirID]},
		    dataType: "JSON",
		    success: function(data) { 
				$("#runs").html(data);
			}
		});	
	})
	
	$("#runs").on("click", ".pure-button", function() {
		var lineID = $("#selectLine").val();
		var dirID = $("#selectDirection").val();
		var runDate = $("#selectRunDate").val();
		var runID = $(this).attr("name");
		var startTime = $(this).val();
		$("#runs").find(".pure-button").css({"background-color":"white", "color":"black"});
		$(this).css({"background-color":"#cc0000", "color":"white"});
		$("#view").empty();
		$("#view").append("<div id='runInfo'> </div>");
		$("#view").append("<div id='shiftInfo'> </div>");
		$("#view").append("<div id='allocation'><p> Choose a driver below to replace the driver above. </p> <p> <select id='drivers'> </select> </p> <input type='button' id='allocateBtn' class='pure-button pure-button-primary' value='Allocate'> </div>");
		$("#view").append("<div id='allocationFeedback'> </div>");
		$("#view").append("<div id='map' style='width: 95%; height: 700px; margin: auto;'> </div>");
		$("#view").append("<div id='legend' style='display: none;'> <img src='images/originalmarker.png'> <span> Regular Stops </span> <img src='images/greenmarker.png'> <span> Optional Stops is Booked </span> <img src='images/bluemarker.png'> <span> Optional Stops Not Booked Yet</span> </p> </div>");
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"viewWorkFlow": [lineID, dirID, runDate, runID, startTime]},
		    dataType: "JSON",
		    success: function(data) {
			    $("#runInfo").empty();
				$("#runInfo").append(data);
				loadmaps(lineID, dirID, runDate, runID);
				$("#legend").show();
			}
		});
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"shiftWorkFlow": [lineID, dirID, runDate, runID, startTime]},
		    dataType: "JSON",
		    success: function(data) {
			    $("#shiftInfo").empty();
			    $("#shiftInfo").append(data);
			}
		});
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"getDriver": "call"},
		    dataType: "JSON",
		    success: function(data) {
			    $("#drivers").append(data);
			}
		});
		$("#allocateBtn").click(function() {
			var driverUsername = $("#drivers").val();
			$.ajax({
			    url: "./systemView_Function.php",
			    type: "POST",
			    data: {"allocateOneDriver": [lineID, dirID, runDate, runID, driverUsername]},
			    dataType: "JSON",
			    success: function(data) {
				    $("#allocationFeedback").empty();
				    $("#allocationFeedback").append(data);
				}
			});
		})
	})
	
	function loadmaps (lineID, dirID, runDate, runID) {
		var dataReg, dataOptBooked, dataOptNotBooked;
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"loadRegStops": [lineID, dirID]},
		    dataType: "JSON",
		    async: false,
		    success: function(data) { 
				dataReg = data;
			}
		});
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"loadOptStopsBooked": [lineID, dirID, runDate, runID]},
		    dataType: "JSON",
		    async: false,
		    success: function(data) { 
				dataOptBooked = data;
			}
		});
		$.ajax({
		    url: "./systemView_Function.php",
		    type: "POST",
		    data: {"loadOptStopsNotBooked": [lineID, dirID, runDate, runID]},
		    dataType: "JSON",
		    async: false,
		    success: function(data) { 
				dataOptNotBooked = data;
			}
		});
		putMarkerOnMap(dataReg, dataOptBooked, dataOptNotBooked);
	}
	
	function putMarkerOnMap(dataReg, dataOptBooked, dataOptNotBooked){
		var sumlat=0, sumlon=0;
		$.each(dataReg, function(key, val){
			sumlat = sumlat + parseFloat(dataReg[key][1]);
			sumlon = sumlon + parseFloat(dataReg[key][2]);
		});
		var map = new google.maps.Map(document.getElementById('map'), {
		    zoom: 11,
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
		for (i = 0; i < dataOptBooked.length; i++) {  
	    	marker = new google.maps.Marker({
	        	position: new google.maps.LatLng(dataOptBooked[i][1], dataOptBooked[i][2]),
	        	map: map,
	        	icon: 'images/greenmarker.png'
	    	});
		    google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
	        		infowindow.setContent(dataOptBooked[i][0]);
	        		infowindow.open(map, marker);
	        	}
	    	})(marker, i));
		}
		for (i = 0; i < dataOptNotBooked.length; i++) {  
	    	marker = new google.maps.Marker({
	        	position: new google.maps.LatLng(dataOptNotBooked[i][1], dataOptNotBooked[i][2]),
	        	map: map,
	        	icon: 'images/bluemarker.png'
	    	});
		    google.maps.event.addListener(marker, 'click', (function(marker, i) {
		        return function() {
	        		infowindow.setContent(dataOptNotBooked[i][0]);
	        		infowindow.open(map, marker);
	        	}
	    	})(marker, i));
		}

	}

})