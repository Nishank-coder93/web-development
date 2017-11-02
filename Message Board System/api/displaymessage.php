<?php  
 /* This script handles the Message Display area in the board */
try{
  /* SQL query to extract rows of post according to time in Descending order therefore the latest message will always show up first */
    $getmain = $dbh->prepare('SELECT * FROM posts WHERE replyto = "" ORDER BY datetime DESC');
    $getmain->execute();

    echo "<div class='container'>";

    while ($row = $getmain->fetch()){

      // Gets the Full Name of the user who posted the message 
      $name = $dbh->prepare("SELECT fullname FROM users WHERE username='" . $row['postedby'] . "'");
      $name->execute();
      $fullnamerow = $name->fetch();

      $replytoid = "board.php?replyto=" . $row["id"] ;

      echo "<div class='row message-post'>";
      echo "<b>[" . $row["datetime"] . "]</b> ID: " . $row['id'] . " [Name] <b>" . $fullnamerow['fullname'] . "</b> [Posted By username] <b>" . $row['postedby'] . "</b> <button type='submit' formaction=$replytoid class='btn-primary pull-right'> Reply </button> </br> Message: <b><i><span class='message_in_box'>". $row["message"] . "</span> </i></b></br>";

      $getreply = $dbh->prepare('SELECT * FROM posts WHERE replyto != "" ORDER BY datetime DESC');
      $getreply->execute();

      echo "<div class='reply-post'>";
      // For each main post it checks in database if there is a reply to this post by looking at the reply to id
      while ($replyrow = $getreply->fetch()) {
        if ( $replyrow['replyto'] == $row['id']){
          $name = $dbh->prepare("SELECT fullname FROM users WHERE username='" . $replyrow['postedby'] . "'");
          $name->execute();
          $fullnamerow = $name->fetch();
          echo "<b>[" . $replyrow["datetime"] . " ]</b> ID:" . $replyrow['id'] . " [Name] <b>" . $fullnamerow['fullname'] . "</b> [Posted By username] <b>" . $replyrow['postedby'] . " </b>   </br> Reply Message: <b><i><span class='message_in_box'>". $replyrow["message"] . "</span></i></b></br>";
        }
      }
      echo "</div>";
      echo "</div>";
  }
  echo "</div>";

  } catch (PDOException $e){
    print "Error !! : " . $e->getMessage() . " <br/>";
    die();
  }

?> 