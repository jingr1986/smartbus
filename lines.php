<!Doctype html>
<!-- written by Ran Jing -->
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
		<script src="js/lines.js"></script>
		<title>System View</title>
	</head>

	<body>
			
		<div id="container">
			
			<?php include './include/top.inc';?>
			<div style="text-align: center">
				<h2>Smart Bus Lines Stops Information</h2>
				<div id="routenumberinfo"> <?php showLineNumber(); ?> </div>
				<div id="directioninfo"> 
					<p><i class="fa fa-arrow-up"></i> Select a route above <i class="fa fa-arrow-up"></i></p> <p> and then select a direction </p> 
				</div>
				<div id="map" style="width: 1072px; height: 663px; margin: auto;"></div>
				<div id="legend">
					<img src="images/originalmarker.png">
					<span> Regular Stops </span>
					<img src="images/bluemarker.png">
					<span> Optional Stops </span>
				</div>
			</div>
			<?php include './include/footer.inc';?>
			
		</div>
	
	</body>
	
</html>