<!DOCTYPE html>

<html>
<head>
    <meta charset="utf-8">
    <link rel="stylesheet" type="text/css" href="./css/mystyle.css">
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <title>Home Page</title>
</head>

<body>

	<div id="container">
		
		<?php include './include/top.inc';?>
		<main>
		
		    <div id="introduction">
		        <h1>Introduction:</h1>
		
				<p> Smart Bus System is currently running in Melbourne, Australia. This is project to try to improve the smart bus system even further. </p>
				<p> This project was done from Dec 2014 to Feb 2015 by Jiachen Yan and Ran Jing with two supervisors, Maria Spichkova and Margaret Hamilton. The system has three main functionalities, the system view, passenger view and driver view. </p>
				<ul>
					<li> The system view provides users a summary view of a whole system. Users can view all runs in one day of one direction of one bus line. Information of one run includes the total number of optional stops, the total number of booked optional stops, the number of passengers who booked the stops, and Google Map which shows the regualr stops, booked optional stops and unbooked optional stops. </li>
					<li> The passenger view is shown after a passenger log in. The view allows the passenger to book a optional stops of one direction of one line at a certain time. The view can also show the booking history of the passenger. </li>
					<li> The driver view is shown after a driver log in. The view is able to show the next run which the driver need to work, and also all the shifts the driver has in the future. </li>
				</ul>
				
		    </div>
		    
		    <div id="routetable">
		    	<table>
			    	<caption> <h1> Current Available Routes </h1> </caption>
			    	<tr>
				    	<td> 703 </td> <td> 900 </td> <td> 901 </td>
			    	</tr>
			    	<tr>
				    	<td> 902 </td> <td> 903 </td> <td> 905 </td>
			    	</tr>
			    	<tr>
				    	<td> 906 </td> <td> 907 </td> <td> 908 </td>
			    	</tr>
			    	
		    	</table>
		    </div>
		    
		</main>
		<?php include './include/footer.inc';?>
	</div>
    
</body>
</html>
