<?php
	$servername = "localhost";
	$username = "root";
	$password = "root";
	$dbname = "smartbus";
	
	// Create connection
	$conn = mysqli_connect($servername, $username, $password, $dbname);
	
	// Check connection
	if (mysqli_connect_errno()) {
	  echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	
	$sql = "select count(username) from passenger";
	$result = mysqli_query($conn, $sql);
	
	// Associative array
	while ($row = mysqli_fetch_assoc($result) ) {
		print_r(array_values($row));
	}
	
	// Free result set
	mysqli_free_result($result);
	
	mysqli_close($conn);
	
	echo "END";
?>