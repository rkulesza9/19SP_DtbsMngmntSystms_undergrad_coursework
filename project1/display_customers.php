<html>
  <head>
    <title>Display Customers</title>
    <Style>
      table,th,td,tr{
        border-color:black;
        border-style:solid;
        border-width:thin;
        border-collapse:collapse;
      }
    </style>
  </head>
  <body>

    <p><a href="index.html">home</a></p>
    <h1>Display Customers</h1>
    <p>The following customers are in the bank system:</p>
    <table>
      <!-- header -->
      <tr><th>ID</th><th>login</th><th>password</th><th>Name</th><th>Gender</th><th>DOB</th><th>Street</th><th>City</th><th>State</th><th>Zipcode</th></tr>

      <?php
          include 'dbconfig.php';
          //mysql query for selecting stuff from table
          $sql = "SELECT * FROM Customers";
          $stmt = $conn->prepare($sql);
          $stmt->bind_result($id,$name,$login,$password,$DOB,$gender,$street,$city,$state,$zipcode);
          $stmt->execute();
          //fill table
          while($stmt->fetch()){
            echo "<tr>";
            echo "<td>".$id."</td><td>".$login."</td><td>".$password."</td><td>".$name."</td><td>".$gender."</td><td>".$DOB."</td><td>".$street."</td><td>".$city."</td><td>".$state."</td><td>".$zipcode."</td>";
            echo "</tr>";
          }

          $stmt->close();
       ?>
    </table>
  </body>
  <footer>
  </footer>
</html>
