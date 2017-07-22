<?php
	session_start();
	
	include 'login_Function.php';
	include 'Booking_Function.php';
	include 'database.php';
	include 'lines_Function.php';
	include 'systemView_Function.php';
	include 'API_Function.php';
	
	//Custom Exception Class
	class customException extends Exception
	{
		public function error()
		{
			$errorMsg = $this->getMessage();
			return $errorMsg;
		}
	}
	
	//Logout function
	function logout()
	{
		//Destroy all session data.
		session_destroy();
	}
	
	
?>