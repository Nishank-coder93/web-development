<?php 
	// This script makes the inital connection with DB 
	  $dbh = new PDO("mysql:host=127.0.0.1:3306;dbname=board","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
	  $dbh->beginTransaction();
?>