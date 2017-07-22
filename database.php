<?php
	
	function createConnection () {
		
		$servername = "localhost";
		$username = "root";
		$password = "root";
		$dbname = "smartbus";
		
		// Create connection
		$conn = mysqli_connect($servername, $username, $password, $dbname);
		
		// Check connection
		if (mysqli_connect_errno())
		  echo "Failed to connect to MySQL: " . mysqli_connect_error();
		
		return $conn;
	}

	function getQueryResult($sql) {
		$conn = createConnection ();
		$result = mysqli_query($conn, $sql);
		$conn -> close();
		return $result;
	}

?>