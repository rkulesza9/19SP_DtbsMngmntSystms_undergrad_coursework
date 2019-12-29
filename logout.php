<?php
  include 'cookie.php';
  setcookie($cookie_name,-1,time()-1);
?>
<html>
  <head>
    <title>Logout</title>
  </head>
  <body>
    <h1>Logout Was Successful!</h1>
    <p><a href='p2.html'> Click Here</a> to login into another account.</p>
    <p><a href='index.html'>Click Here</a> to get to the Project(s) Home-Page.</p>
  </body>
  <footer>
  </footer>
  </html>
