<!Doctype html>
<!-- written by Jiachen Yan -->
<?php 
	require_once('Common_Function.php');
?>

<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="./css/mystyle.css"/>
		<link rel="stylesheet" type="text/css" href="./css/font-awesome-4.3.0/css/font-awesome.min.css"/>
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
		<script src="js/systemView.js"></script>
		<title>System View</title>
	</head>

	<body>
			
		<div id="container">
			
			<?php include './include/top.inc';?>
			<div style="text-align: center">
				<h3 id="adminTitle"> ADMINISTRATION </h3>
				<div class="pure-g" id="systemView">
					<div class="pure-u-1-4" id="infoSelect">
						<div id="svform">
							<form>
								<div>
									<label class="searchLabel"> Lines </label> 
									<select class="searchSelect" id="selectLine">
										<option> Select A Line </option>
										<?php selectLineNumber(); ?>
									</select>
								</div>
								<div>
									<label class="searchLabel"> Direction </label>
									<select class="searchSelect" id="selectDirection">
										<option> Select A Direction </option>
									</select>
								</div>
								<div>
									<label class="searchLabel"> Date </label> 
									<select class="searchSelect" id="selectRunDate" disabled="true"> </select>
								</div>
								<input class="pure-button pure-button-primary" id="getRunsBtn" value="&#xf002; Get Runs" style="font-family: Arial, FontAwesome;">
							</form>
						</div>
						<div id="runs"> </div>
					</div>
					<div class="pure-u-3-4" id="view">
						<div id="runInfo"> </div>
						<div id="map"> </div>
						<div id="legend" style="display: none;">
							<img src='images/originalmarker.png'> <span> Regular Stops </span> 
							<img src='images/greenmarker.png'> <span> Optional Stops is Booked </span> 
							<img src='images/bluemarker.png'> <span> Optional Stops Not Booked Yet</span> </p>
						</div>
					</div>
				</div>
			</div>
			<?php include './include/footer.inc';?>
			
		</div>
	
	</body>
	
</html>