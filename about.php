<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./css/mystyle.css">

    <title>Home Page</title>
</head>

<body>
	
	<div style="background-color: white">
	
	    <?php include './include/top.inc';?>
	
	    <div id="about1">
	        <p><b>There are three main functions for this Project.</b></p>
			
			<p>
				<b> System View </b> <br><br>
				Users are able to select one run of one direction of one line on a particular day. <br><br>
				Once a run is selected, the information about this run will be shown, including the number of optional stops in this run, the number of booked optional stops and the number of non-booked optional stops as well as the driver who will drive this run. <br><br>
				All this information is also shown in the Google Map, which makes a easy understanding for users.
			</p>
			
	        <p>
		        <b>Passenger view:</b><br><br>
		        Passengers can able to book an optional stop, they can also view their booking history. <br><br>
		        Currently there are only two passenger accounts in the system.<br><br>
		        Username: P001 Password: 12345<br>
		        Username: P002 Password: 12345<br>
	        </p>
	    </div>
	
	    <div id="about2">
		    <br>
		    <br>
	        <p>
		        <b>Driver view:</b><br><br>
		        Driver view shows the shifts and its route information for the drivers. The information does not include the non-booked optional stops. <br><br>
		        Currently there are only two driver accounts in the system.<br><br>
		        Username: D001 Password: 12345<br>
		        Username: D002 Password: 12345<br>
		    </p>
	    </div>
	    
	    <?php include './include/footer.inc';?>
    
	</div>
</body>
</html>
