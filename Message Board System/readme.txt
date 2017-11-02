  ================================
           README FILE
  ================================


  ==================
  	   SQL QUERY 
  ==================

  - With reagrds to the SQL file run these commands to insert a new user
  - md5 being encryption from PHP side to encrypt the password
  - I have only made a few, you can add as many as you want. 
  		+ INSERT INTO users VALUES("smith","' . md5("mypass") . '","John Smith","smith@cse.uta.edu")
  		+ INSERT INTO users VALUES("nishank","' . md5("1234") . '","Nishank Bhatnagar","nishank@cse.uta.edu")
  		+ INSERT INTO users VALUES("rahul","' . md5("hello") . '","Rahul Bhatnagar","rahul@cse.uta.edu")

  ==================
  	MESSAGE BOARD
  ==================

  - After writing the message in the text file two methods to execute them
  - By pressing the POST MESSAGE button
  		+ It will enter the posts with details in the database 
  		+ Will show the latest post on top
  		+ Each post box will have a reply button
  - By pressing the REPLY button on the individual post
  		+ Will store the details in the database with reply to id of the post replying to 
  		+ will show the latest reply message on top in the indiviual post