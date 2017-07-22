<?php
	require_once('Common_Function.php');
	
	setcookie("username",$_POST['username'],time()+3600);
	
	$regexUserID = "/^[PDpd][0-9]{3}$/";
	$firstChar = $_POST['username'][0];
	
	$username = isset($_POST['username'])? $_POST['username'] : NULL;
	$password = isset($_POST['password'])? $_POST['password'] : NULL;
	
	$errorMsg = NULL;
	
	if(isset($_POST['login'])) {
		try {
			if($username == NULL){throw new customException("Empty ID!");}
			if($password == NULL){throw new customException("Empty password!");}
			if ($firstChar=="P")
				passengerLogin($username,$password);
			elseif ($firstChar=="D") {
				driverLogin($username,$password);
			} else {
				{throw new customException("Username must start with P or D!");}
			}
		}
		catch(customException $e) {
			$errorMsg = $e->error();
		}
	} 
	
	if(isset($_POST['logout'])) {
		logout();
		header('Location: login.php');
	}
?>


<!Doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<link rel="stylesheet" type="text/css" href="./css/mystyle.css">
		<link rel="stylesheet" type="text/css" href="./css/font-awesome-4.3.0/css/font-awesome.min.css"/>
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
		<script src="js/booking.js"></script>
		<link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.5.0/pure-min.css">
		<title>Passenger View</title>
	</head>
	
	<body>
		<div id="container">
			
			<?php include './include/top.inc';?>
			
			<?php
				if( $errorMsg == NULL && (!isset($_SESSION['passenger']['status']) || $_SESSION['passenger']['status']==false ) && ( !isset($_SESSION['driver']['status']) || $_SESSION['driver']['status']==false)){
			?>
				
				<div id="universalLogin">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="pure-form pure-form-aligned">
						<div class="pure-control-group">
							<label for="userid"> USER ID: </label>
							<input type="text" name="username" value="<?php echo $_COOKIE['userid'] ?>"/>
						</div>
						<div class="pure-control-group">
							<label for="password"> PASSWORD: </label>
							<input type="password" name="password"/>
						</div>
						<div class="pure-controls">
							<button type="submit" name="login" value="submit" class="pure-button pure-button-primary" style="background-color: #cc0000"> SIGN IN </div>
						</div>
					</form>
				</div>
				
			<?php } elseif($_SESSION['passenger']['status']) { ?>
				<div id="universalLogin">
					<style scoped>
						.button-success {
							color: white;
							border-radius: 4px;
							text-shadow: 0 1px 1px rgba(0,0,0,0.2);
						}
						.button-success{background: rgb(28,184,65);}
					</style>
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<div class="pure-control-group">
							HELLO, <?php echo $_SESSION['passenger']['username'] ?> ! <br>
							WELCOME BACK!
						</div>
						<input type="button" class="button-success pure-button" onclick="location.href='booking.php';" value="BOOK A STOP">
						<br>
						<input type="button" class="button-success pure-button" id="history" value="BOOKING HISTORY">
						<br>
						<button type="submit" name="logout" value="logout" class="pure-button pure-button-primary" style="background-color: #cc0000"> <i class="fa fa-sign-out"></i> LOGOUT </div>
					</form>
				</div>
			<?php } elseif($_SESSION['driver']['status']) { ?>
				<div id="universalLogin">
					<div class="pure-g">
						<div class="pure-u-1-3" id="driverLoginLeft">
							<div>
								<p> <b>Hello, <?php echo $_SESSION['driver']['username'] ?> </b> </p>
								<p> Choose a date to see your shift </p>
							</div>
							<div>
								<select id="shiftDate">
									<option value=0> Select A Date </option>
									<?php loadShiftDate() ?>
								</select>
								<br>
								<div id="shifts"> <?php displayShiftsInit(utcToMelDate(date("Y-m-d"))); ?> </div>
							</div>
							<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
								<button type="submit" name="logout" value="logout" class="pure-button pure-button-primary" style="background-color: #cc0000"> <i class="fa fa-sign-out"></i> LOGOUT </button>
							</form>
						</div>
						<div class="pure-u-2-3" id="shiftTimetable"> </div>
					</div>
				</div>
		
			<?php } else { ?>
				<div id="universalLogin">
					<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
						<?php echo $errorMsg; ?>
						<br/>
						<button class="button-success pure-button"> <a href='login.php'> Try again </a> </button>
					</form>
				</div>
			<?php } ?>
			
			<?php include './include/footer.inc';?>
			
		</div>
	
	</body>
</html>