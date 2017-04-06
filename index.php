<?php
	session_start();
	
	//   all database connection are put into another php file
	require "database.php";
	
	//   if usernme and password information is submitted, run login
	// function and save the session if login is valid
	$resp = "";
	if(isset($_POST["username"])){
		if(login($_POST["username"], $_POST["password"])){
			$resp = "login success";	
			$_SESSION["userlogin"] = $_POST["username"];
		} else {
			$resp = "invalid login";
		}
	} 
	
	//   redirect to dashboard when login session still exists
	if (isset($_SESSION["userlogin"])) {
		header("Location: dashboard.php");
	}
	
	//   login function. create connection to database and search 
	// through all existing username to find if it exists, then 
	// match their password. WARNING: this login is not secure.
	function login($user, $pass){
		
		$conn = connectDB();
		//   query the database to return username and password existence
		$sql = "SELECT email, password, role FROM tk_basdat.user WHERE email='$user' and password='$pass'";
		$result = pg_query($conn, $sql);
		if (!$result) {
			die("Error in SQL query: " . pg_last_error());
		}   
		
		$success = false;
		if (pg_num_rows($result) != 0) {
			$field = pg_fetch_array($result);
			$_SESSION["user_id"] = $user;
			$_SESSION["role"] = $field[2];
			$_SESSION["currentActivity"] = 0;
			$success = true;
		}
		
		pg_close($conn);
		return $success;
		
	}
	
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<!-- Latest compiled and minified CSS and js from CDN-->
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.2/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

	<!-- Own stylesheet -->
	<link rel="stylesheet" href="css/style_common.css">
	<link rel="stylesheet" href="css/style_login.css">
	<script src="js/script_common.js"></script>

	<title>FOODIE: Best Restaurant</title>
</head>

<body>
	<div id="maintitle" class="col-md-12">
		<h1><span class="glyphicon glyphicon-cutlery"></span> Foodie</h1>
	</div>

	<div id="loginform" class ="col-md-12">
		<form method="POST" action="index.php" class="login">
		  <h2><span class="entypo-login"></span> Admin Panel</h2>
		  <button class="submit"><span class="entypo-lock"></span></button>
		  <span class="entypo-user inputUserIcon"></span>
		  <input type="text" name="username" class="user" placeholder="username"/>
		  <span class="entypo-key inputPassIcon"></span>
		  <input type="password" name="password" class="pass" placeholder="password"/>
		</form>
		<?php echo $resp; ?>
	</div>
	
</body>
</html>