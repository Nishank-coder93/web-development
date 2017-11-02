<html>
<head>
	<title> Login </title>
	<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
	<style type="text/css">
	#login_box{
		height: 100%;
		margin: auto;	
	}
	#input_form{
		margin-right: auto;
		margin-left: 10%;
		margin-top: 30%;
	}
	input[name='username']{
		width: 40%;
		font-size: 18px;
	}
	input[name='password']{
		width: 40%;
		font-size: 18px;
	}
	</style>
</head>
<body>

	<?php

	// This script handles the login section 
		include("api/dbconnect.php");
		session_start();

		error_reporting(E_ALL);
		ini_set('display_errors','On');

		if($_SERVER["REQUEST_METHOD"] == "POST"){

			$username = $_POST['username'];
			$password = md5($_POST['password']);
			
			try{
				/* Checks the if the user entered credentials exist in database */
				$stmt = $dbh->prepare("select username,fullname from users where username = '$username' and password = '$password'");
  				$stmt->execute();
  				$row = $stmt->fetch();

  				// If a single row was retured then direct to Board.php else Stay in Login screen and indicate wrong input
  				if($row){
  					$_SESSION['login_username'] = $username;
  					$_SESSION['login_fullname'] = $row['fullname'];

  					header("location: board.php");
  				}else {
  					$error = "Sorry wrong username/password entered. Please Try again";
  				}
			}catch (PDOException $e) {
			  print "Error!: " . $e->getMessage() . "<br/>";
			  die();
			}	
		}
	?>

	<div id="wrong_input"> <?php if (!empty($error)){ print $error; } ?> </div>
	<div class='container' id='login_box'>
		<form action="" method="post" id='input_form' >
			<label style="font-size: 18px;"> Username </label>
			<input type='text' placeholder="Username" id='username_login' name='username'> </br>
			<label style="font-size: 18px;"> Password </label>
			<input type='password' placeholder="password" id='password_login' name='password'> </br>
			<input type='submit' value='Login' class='btn-primary'>
		</form>
	</div>

	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"> </script>
	<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>