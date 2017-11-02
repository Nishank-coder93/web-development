<?php 

// On logout Destroys the session and sends to Login Screen
	session_start();

	if(session_destroy()){
		header("location: ../login.php");
	}
?>