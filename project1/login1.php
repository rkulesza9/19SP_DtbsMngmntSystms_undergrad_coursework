<?php
  if(!isset($_POST['submit'])) header("location: index.html");
?>
<html>
  <head>
    <title>Login Authentication</title>
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
    <p><a href='index.html'>home</a></p>
    <?php
      include 'dbconfig.php';
      $login_in = $_POST['username'];
      $pswd_in = $_POST['password'];
      $submit = $_POST['submit'];

      if(isset($submit) && isset($login_in) && isset($pswd_in)) {
        $cont = true;
        //error messages
        if($login_in==''){
          echo "<h1 style='color:red;'>login field cannot be empty</h1>";
          $cont = false;
        }
        if($pswd_in==''){
          echo "<h1 style='color:red;' >password field cannot be empty</h1>";
          $cont = false;
        }

        //login / password not empty
        //I use utf8-general-ci collation for login so that case sensitivity is not an issue
        if($cont){
          $sql = "SELECT * from Customers where login=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("s",$login_in);
          $stmt->bind_result($id,$name,$login,$password,$DOB,$gender,$street,$city,$state,$zipcode);
          $stmt->execute();
          $login_exists = $stmt->fetch();
          $stmt->close();

          if(!$login_exists) echo "<h1 style='color:red;'>Login Does Not Exist!</h1>";
          else {
            if($pswd_in!=$password){
              echo "<h1 style='color:red;'>Password Is Incorrect</h1>";
            }else{ //logged in successfully
              //Your IP: 100.100.100.100 (10.*.*.*) (131.125.*.*)
              //You are (Not) from Kean University
              $addr = $_SERVER['REMOTE_ADDR'];
              echo "<p>Your IP: ".$addr.'<br>';
              echo "You Are ";
              if(!(substr($addr,0,3)=="10.") && !(substr($addr,0,8)=="131.125.")) echo "NOT ";
              echo "From Kean University.<br>";


              //Welcome Customer: name
              echo "Wecome Customer: ".$name."<br>";

              //age: 10
              function getDays($start,$end) {
                return (int)((strtotime($start) - strtotime($end))/(60*60*24*30*12));
              }
              echo "age: ".(getDays(date('Y-m-d'),$DOB))."<br>";

              //Address: Address
              echo "Address: ".$street." ".$city.", ".$state." ".$zipcode."<br><p>";

              //<hr>
              echo "<hr>";

              //transactions table
              //setup sql query
              $sql = "SELECT mid, code, type, amount, mydatetime, note FROM CPS3740_2019S.Money_kuleszar";
              $stmt = $conn->prepare($sql);

              if($stmt){
                $stmt->bind_result($id,$code,$operation,$amount,$datetime,$note);
                $stmt->execute();

                echo "<p>The transactions for customer ".$name." are: Saving account</p>";
                echo "<table>";
                echo    "<tr><th>ID</th><th>Code</th><th>Operation</th><th>Amount</th><th>Date Time</th><th>Note</th></tr>";
                //loop through data from query
                $balance = 0;
                while($stmt->fetch()){
                  $balance += $amount;
                  if($amount < 0) $color = 'style="color:red;"';
                  if($amount >= 0) $color = 'style="color:blue;"';
                  echo "<tr><td>".$id."</td><td>".$code."</td><td>".($operation=='W' ? 'Withdrawal' : 'Deposit')."</td><td ".$color.">".$amount."</td><td>".$datetime."</td><td>".$note."</td></tr>";
                }
                $stmt->close();
                echo "</table>";
                echo "Total Balance: ".$balance;
              }else{
                echo "table not found";
              }

            }
          }

        }
      }

     ?>
  </body>
</html>
