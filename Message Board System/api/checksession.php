<?php 
// This script redirects to login screen if not logged in Ensures no one without credential can see the message
session_start();
if (!isset($_SESSION['login_username'])){
	header("location: login.php");
}
?>