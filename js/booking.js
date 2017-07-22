// wriiten by Jiachen Yan
$(document).ready(function(){
	
	$("#selectLine").change(function(){
		
		$("#selectDirection").empty();
		
		$("#selectStops").empty();
		$("#selectStops").append("<option> Select An Optional Stop </option>");
		
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
		
		$("#selectStops").empty();

		var lineID = $("#selectLine").val();
		var dirID = $("#selectDirection").val();
		
		$.ajax({
		    url: "./Booking_Function.php",
		    type: "POST",
		    data: {"selectStops": [lineID, dirID]},
		    dataType: "JSON",
		    success: function(data) {  	
				$("#selectStops").html(data);
			}
		});	
		
	})
	
	$("#searchBtn").click(function(){
		var lineID = $("#selectLine").val();
		var dirID = $("#selectDirection").val();
		var optID = $("#selectStops").val();
		var date = $("#selectDate").val();
		var hour = $("#selectHour").val();
		var minute = $("#selectMinute").val();
		var searchTime = date+"T"+hour+":"+minute+":00Z";
		$.ajax({
		    url: "./Booking_Function.php",
		    type: "POST",
		    data: {"timetableWorkflow": [lineID, dirID, optID, searchTime]},
		    dataType: "JSON",
		    async: false,
		    success: function(data) { 
			    $("#timetable").empty();
				$("#timetable").append(data);
			}
		});	
	})
	
	$("#timetable").on("click", "#bookChecked", function(){
		var lineID = $("#selectLine").val();
		var dirID = $("#selectDirection").val();
		var optID = $("#selectStops").val();
		var date = $("#selectDate").val();
		var bookingMsg = "";
		$(".optCheckbox:checked").each(function(){
			var runID = $(this).attr('name');
			var arrivaltime = date+"T"+$(this).val()+":00Z";
			$.ajax({
			    url: "./Booking_Function.php",
			    type: "POST",
			    data: {"createBooking": [lineID, dirID, optID, runID, arrivaltime]},
			    dataType: "JSON",
			    async: false,
			    success: function(data) { 
				    bookingMsg = bookingMsg+data;
				}
			});	
			
		})
		$("#bookingFeedback").empty();
		$("#bookingFeedback").append(bookingMsg);	
	})
		
	$("#history").click(function(){
		$("#universalLogin").empty();
		$.ajax({
		    url: "./Booking_Function.php",
		    type: "POST",
		    data: {"displayBookingHistory":"call"},
		    dataType: "JSON",
		    async: false,
		    success: function(data) { 
				$("#universalLogin").append(data);
			}
		});	

	})
		
	$("#shiftDate").change(function(){
		var date = $(this).val();
		if (date!=0) {
			$.ajax({
			    url: "./login_Function.php",
			    type: "POST",
			    data: {"displayShifts": date},
			    dataType: "JSON",
			    success: function(data) {
				    $("#shifts").empty();
					$("#shifts").append(data);
				}
			});
		} else {
			$("#shifts").empty();
		}
	})
	
	$("#shifts").on("click", ".pure-button", function(){
		$("#shifts").find(".pure-button").css({"background-color":"white", "color":"black"});
		$(this).css({"background-color":"#cc0000", "color":"white"});
		
		var date = $("#shiftDate").val();
		var lineID = $(this).attr("data-line-id");
		var dirID = $(this).attr("data-dir-id");
		var runID = $(this).attr("data-run-id");
		
		$.ajax({
		    url: "./login_Function.php",
		    type: "POST",
		    data: {"displayShiftTimetable": [date, lineID, dirID, runID]},
		    dataType: "JSON",
		    success: function(data) {
			    $("#shiftTimetable").empty();
			    $("#shiftTimetable").append(data);
			}
		});
		
		
	})
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
})