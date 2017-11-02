<html>
<head><title>Message Board</title></head>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="style.css">
<body>
<?php
include("api/checksession.php");  // Checks if the session exist if not then send to login screen
include("api/dbconnect.php"); // Connect with DB

error_reporting(E_ALL);
ini_set('display_errors','On');

// Execute when POST method is requested
if ($_SERVER["REQUEST_METHOD"] == 'POST'){

  $message = $_POST['message_input'];
  $message_id = uniqid(true);
  $postedby_user = $_SESSION['login_username'];
 // if Reply To POST not requested, post on the message normally
 if(!empty($_GET['replyto'])){
  $replyto_id = $_GET['replyto'];
  try{
    /* Insert the post in the database */
    $replystmt = $dbh->prepare("INSERT INTO posts VALUES('$message_id', '$replyto_id', '$postedby_user', NOW(), '$message')");
    $replystmt->execute();
    $dbh->commit(); 

  } catch (PDOException $e){
    print "Error !! : " . $e->getMessage() . " <br/>";
    die();
  }
 }
 else {
  $replyto_id = null;
  try{
    /* Insert the post in the database */
    $stmt = $dbh->prepare("INSERT INTO posts VALUES('$message_id', '$replyto_id', '$postedby_user', NOW(), '$message')");
    $stmt->execute();
    $dbh->commit(); 

    // Incase an error catch here
  } catch (PDOException $e){
    print "Error !! : " . $e->getMessage() . " <br/>";
    die();
  }
 }
}
?>

<h1 class='text-center'> Welcome <?php print($_SESSION['login_fullname']); ?> To the Message Board</h1>
<form class='container'>
  <button type='submit' formaction='api/logout.php' class='btn-danger center-block'> Logout </button>
</form>

<div id='message_poster'>
  <form action="" method='post'>
    <div id='input-box' >
      <input type='textbox' name='message_input' placeholder="Enter your message here" id='message_input_box'>
      <button type='submit' formaction='board.php' class='btn-success' id='post_button'>Post Message </button>
    </div>
    <div id='message_box' class='container'> 
      <?php  include("api/displaymessage.php"); ?> 
    </div>
  </form>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"> </script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</body>
</html>
