<?php
	require_once("../API_Function.php");
	require_once("../database.php");
	
	$lineID = 7474;
	$dirID = 21;
	$time = "2015-02-08T09:00:00Z";
	
	function checkTimetable ($lineID, $dirID, $time){
		echo "start <br>";
		$check = 0;
		$conn = createConnection ();
	
		$sql = "select distinct date_mel from timetable ";

		$result = getQueryResult($sql);
		$tempDate = [];
		while($row = mysqli_fetch_assoc($result)){
			
			$temp = $row['date_mel'];
			echo $temp."<br>";
			array_push($tempDate, $temp);
		}
		print_r(array_values($tempDate));
		$checkTime = date('Y-m-d\TH:i:s\Z'.strtotime($time));
		$checkDate = date('Y-m-d',strtotime($time));
		echo "check time is".$checkTime."<br>";
		echo "checkdate is ".$checkDate."<br>";
		foreach($tempDate as $key => $value){
	
			
			$haveDate = $value;
			if($checkDate == $haveDate){
				echo "check <br>";
				$check = 1;
				continue ;
			}
		
			$check = 0;
		
		}
	
		return $check;
	
	}
	
	$check = checkTimetable ($lineID, $dirID, $time);

	echo $check."<br>";
	
	
	
	
	
	
	
	
	
?>