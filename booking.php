<?php
	require_once('Common_Function.php');
	require_once('API_Function.php');
?>


<!Doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<link rel="stylesheet" type="text/css" href="./css/mystyle.css">
		<link rel="stylesheet" type="text/css" href="./css/font-awesome-4.3.0/css/font-awesome.min.css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="js/booking.js"></script>
		<title>Booking</title>
	</head>

	<body>
		<div id="container">
			<?php include './include/top.inc';?>
			<p><h2 style="text-align: center">BOOKING</h2></p>
			<div id="searchdiv">
				<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" id='searchform'>
					<div>
						<label class="searchLabel"> Bus Line </label>
						<select class="searchSelect" id="selectLine"> 
							<option> Select A Bus Line </option>
							<?php selectLineNumber() ?>
						</select>
					</div>
					<div>
						<label class="searchLabel"> Direction </label>
						<select class="searchSelect" id="selectDirection">
							<option> Select A Direction </option>
						</select>
					</div>
					<div>
						<label class="searchLabel"> Optional Stop </label>
						<select class="searchSelect" id="selectStops">
							<option> Select An Optional Stop </option>
						</select>
					</div>
					<div>
						<label class="searchLabel"> Date </label>
						<select class="searchSelect" id="selectDate">
							<option> Select A Date </option>
							<option value=<?php echo utcToMelDate(date("Y-m-d\TH:i:s\Z", strtotime("-1 day"))); ?>> Yesterday </option>
							<option value=<?php echo utcToMelDate(date("Y-m-d\TH:i:s\Z")); ?> > Today </option>
							<option value=<?php echo utcToMelDate(date("Y-m-d\TH:i:s\Z", strtotime("+1 day"))); ?>> Tomorrow </option>
						</select>
					</div>
					<div>
						<label class="searchLabel"> Time </label>
						<div class="searchSelect">
							<select id="selectHour">
								<option value=00> 00 </option>
								<option value=01> 01 </option>
								<option value=02> 02 </option>
								<option value=03> 03 </option>
								<option value=04> 04 </option>
								<option value=05> 05 </option>
								<option value=06> 06 </option>
								<option value=07> 07 </option>
								<option value=08> 08 </option>
								<option value=09> 09 </option>
								<option value=10> 10 </option>
								<option value=11> 11 </option>
								<option value=12> 12 </option>
								<option value=13> 13 </option>
								<option value=14> 14 </option>
								<option value=15> 15 </option>
								<option value=16> 16 </option>
								<option value=17> 17 </option>
								<option value=18> 18 </option>
								<option value=19> 19 </option>
								<option value=20> 20 </option>
								<option value=21> 21 </option>
								<option value=22> 22 </option>
								<option value=23> 23 </option>
							</select>
							<label> : </label>
							<select id="selectMinute">
								<option value=00> 00 </option>
								<option value=15> 15 </option>
								<option value=30> 30 </option>
								<option value=45> 45 </option>
							</select>
						</div>
					</div>
					<div>
						<input type="button" id="searchBtn" class="pure-button pure-button-primary" value="Search">
			        </div>
				</form>	
			</div>
			<div id="timetable"> </div>
			<?php include './include/footer.inc';?>
			
		</div>
	</body>
</html>