<?php
  //error_reporting(E_ALL);
  //ini_set("display_errors",true);
  //header("Content-Type: text/plain");
  include 'dbconfig.php';
  include 'cookie.php';
  $login_in = $_POST['username'];
  $pswd_in = $_POST['password'];
  $submit = $_POST['submit'];
  $cookie = $_COOKIE[$cookie_name];

  //data to be inserted into html
  $header = ""; //ip, from kean, welcome, age, address
  $transactions = ""; //transactions for table
  $balance = 0; //total balance
  $table_intro = ""; //the transactions for customer Austin Huang are: SAving Account

  $cont = isset($cookie);
  if(!isset($cookie)){
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
    }
  }
    //login / password not empty
    //I use utf8-general-ci collation for login so that case sensitivity is not an issue
    if($cont){
      if(!isset($submit) && isset($cookie)){
        $sql = "SELECT * from Customers where id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i",$cookie);
      } else {
        $sql = "SELECT * from Customers where login=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s",$login_in);
      }

      $stmt->bind_result($id,$name,$login,$password,$DOB,$gender,$street,$city,$state,$zipcode);
      $stmt->execute();
      $login_exists = $stmt->fetch();
      $stmt->close();
      if(!isset($cookie) && !$login_exists) echo "<h1 style='color:red;'>Login $login_in Does Not Exist!</h1>";
      else {
        if(!isset($cookie) && $pswd_in!=$password){
          echo "<h1 style='color:red;'>Password Is Incorrect For User $login_in</h1>";
        }else{ //logged in successfully
          //set cookie
          setcookie($cookie_name,$id,$cookie_durr);
          //if($worked) echo("cookie");
          //name
          $header = $header."<h2 class='to'>".$name."</h2>";
          $table_intro = $table_intro."The transactions for customer ".$name." are: Saving Account";
          //Your IP: 100.100.100.100 (10.*.*.*) (131.125.*.*)
          //You are (Not) from Kean University
          $addr = $_SERVER['REMOTE_ADDR'];
          //echo "<p>Your IP: ".$addr.'<br>';
          $header = $header."<div class='address'>Your IP: ".$addr."</div>";
          //echo "You Are ";
          $header = $header."<div class='address'>You are ";
          if(!(substr($addr,0,3)=="10.") && !(substr($addr,0,8)=="131.125.")) $header = $header." NOT"; //echo "NOT ";
          $header = $header." from Kean University.</div>"; //echo "From Kean University.<br>";


          //Welcome Customer: name
          //echo "Wecome Customer: ".$name."<br>";

          //age: 10
          function getDays($start,$end) {
            return (int)((strtotime($start) - strtotime($end))/(60*60*24*30.44*12));
          }
          $header = $header."<div class='address'>Age: ".(getDays(date('Y-m-d'),$DOB))."</div>";//echo "age: ".(date('Y')-getYear($dob))."<br>";

          //Address: Address
          $header = $header."<div class='address'>"."Address: ".$street." ".$city.", ".$state." ".$zipcode."<br><p>"."</div>";//echo "Address: ".$street." ".$city.", ".$state." ".$zipcode."<br><p>";

          //<hr>
          //echo "<hr>";

          //transactions table
          //setup sql query
          $sql = "SELECT mid, code, type, amount, mydatetime, note FROM CPS3740_2019S.Money_kuleszar where cid=?";
          $stmt = $conn->prepare($sql);
          $stmt->bind_param("i",$id);

          if($stmt){
            $stmt->bind_result($id,$code,$operation,$amount,$datetime,$note);
            $stmt->execute();

            //echo "<p>The transactions for customer ".$name." are: Saving account</p>";
            //echo "<table>";
            //echo    "<tr><th>ID</th><th>Code</th><th>Operation</th><th>Amount</th><th>Date Time</th><th>Note</th></tr>";
            //loop through data from query
            $balance = 0;
            while($stmt->fetch()){
              $balance += $amount;
              if($amount < 0) $color = 'style="color:red;"';
              if($amount >= 0) $color = 'style="color:blue;"';
              $transactions = $transactions."<tr><td class='no'>".$id."</td><td class='qty'>".$code."</td><td class='qty'>".($operation=='W' ? "Withdrawal" : "Deposit")."</td><td class='qty' ".$color.">".$amount."</td><td class='qty'>".$datetime."</td><td class='text-left'>".$note."</td></tr>";
            }
            $stmt->close();
            //echo "</table>";
          }

        }
      }

    }

 ?>
<html>
  <head>
    <title>Login Authentication</title>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <!------ Include the above in your HEAD tag ---------->
    <style>
      #invoice{
        padding: 30px;
      }

      .invoice {
        position: relative;
        background-color: #FFF;
        min-height: 680px;
        padding: 15px
      }

      .invoice header {
        padding: 10px 0;
        margin-bottom: 20px;
        border-bottom: 1px solid #3989c6
      }

      .invoice .company-details {
        text-align: right
      }

      .invoice .company-details .name {
        margin-top: 0;
        margin-bottom: 0
      }

      .invoice .contacts {
        margin-bottom: 20px
      }

      .invoice .invoice-to {
        text-align: left
      }

      .invoice .invoice-to .to {
        margin-top: 0;
        margin-bottom: 0
      }

      .invoice .invoice-details {
        text-align: right
      }

      .invoice .invoice-details .invoice-id {
        margin-top: 0;
        color: #3989c6
      }

      .invoice main {
        padding-bottom: 50px
      }

      .invoice main .thanks {
        margin-top: -100px;
        font-size: 2em;
        margin-bottom: 50px
      }

      .invoice main .notices {
        padding-left: 6px;
        border-left: 6px solid #3989c6
      }

      .invoice main .notices .notice {
        font-size: 1.2em
      }

      .invoice table {
        width: 100%;
        border-collapse: collapse;
        border-spacing: 0;
        margin-bottom: 20px
      }

      .invoice table td,.invoice table th {
        padding: 15px;
        background: #eee;
        border-bottom: 1px solid #fff
      }

      .invoice table th {
        white-space: nowrap;
        font-weight: 400;
        font-size: 16px
      }

      .invoice table td h3 {
        margin: 0;
        font-weight: 400;
        color: #3989c6;
        font-size: 1.2em
      }

      .invoice table .qty,.invoice table .total,.invoice table .unit {
        text-align: right;
        font-size: 1.2em
      }

      .invoice table .no {
        color: #fff;
        font-size: 1.6em;
        background: #3989c6
      }

      .invoice table .unit {
        background: #ddd
      }

      .invoice table .total {
        background: #3989c6;
        color: #fff
      }

      .invoice table tbody tr:last-child td {
        border: none
      }

      .invoice table tfoot td {
        background: 0 0;
        border-bottom: none;
        white-space: nowrap;
        text-align: right;
        padding: 10px 20px;
        font-size: 1.2em;
        border-top: 1px solid #aaa
      }

      .invoice table tfoot tr:first-child td {
        border-top: none
      }

      .invoice table tfoot tr:last-child td {
        color: #3989c6;
        font-size: 1.4em;
        border-top: 1px solid #3989c6
      }

      .invoice table tfoot tr td:first-child {
        border: none
      }

      .invoice footer {
        width: 100%;
        text-align: center;
        color: #777;
        border-top: 1px solid #aaa;
        padding: 8px 0
      }

      @media print {
        .invoice {
            font-size: 11px!important;
            overflow: hidden!important
        }

        .invoice footer {
            position: absolute;
            bottom: 10px;
            page-break-after: always
        }

        .invoice>div:last-child {
            page-break-before: always
        }
      }
    </style>
  </head>
  <body>
    <div id="invoice">

        <!--<div class="toolbar hidden-print">
            <div class="text-right">
                <button id="printInvoice" class="btn btn-info"><i class="fa fa-print"></i> Print</button>
                <button class="btn btn-info"><i class="fa fa-file-pdf-o"></i> Export as PDF</button>
            </div>
            <hr>
        </div>-->
        <div class="invoice overflow-auto">
            <div style="min-width: 600px">
                <header>
                    <div class="row">
                        <!--<div class="col">
                            <a target="_blank" href="https://lobianijs.com">
                                <img src="http://lobianijs.com/lobiadmin/version/1.0/ajax/img/logo/lobiadmin-logo-text-64.png" data-holder-rendered="true" />
                                </a>
                        </div>-->
                        <div class="col company-details">
                            <h2 class="name">
                                <a href="p2.html">
                                CPS 3740 Project 2
                                </a>
                            </h2>
                            <div>by Robert Kulesza</div>
                            <div><a href="logout.php">logout</a></div>
                        </div>
                    </div>
                </header>
                <main>
                    <div class="row contacts">
                        <div class="col invoice-to">
                            <div class="text-gray-light">WELCOME CUSTOMER</div>
                            <?php echo $header; ?>
                        </div>
                        <div class="col invoice-details">
                            <h1 class="invoice-id">SAVINGS ACCOUNT</h1>
                        </div>
                    </div>
                    <?php echo $table_intro; ?>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                                <th class='text-right'>ID</th>
                                <th class="text-right">CODE</th>
                                <th class="text-right">OPERATION</th>
                                <th class="text-right">AMOUNT</th>
                                <th class="text-right">DATETIME</th>
                                <th class="text-left">NOTE</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php echo $transactions ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan=2></td>
                                <td>BALANCE</td>
                                <td><?PHP echo "$".$balance; ?></td>
                            </tr>
                        </tfoot><!-- asdf -->
                    </table>
                </main>
                <table>
                <form action='search.php' method='GET'>
                    <tr><th>Search:</th><td><input type='text' name='search' required><input type='submit' value='Search Transactions'></td></tr>
                </form>
                <form action='add_transaction.php' method='POST'>
                  <tr><td><input type='hidden' value='<?php echo $name; ?>' name='cname' /><input type='hidden' value='<?php echo $balance; ?>' name='cbal' /><input type='submit' name='addt' value='Add Transaction' /></td><td colspan=2><a href='display_update_transactions.php'>Display and Update Transactions</a></td></tr>
                </form>
                </table>
                <footer>
                </footer>
            </div>
            <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
            <div></div>
        </div>
    </div>
  </body>
  <footer>
  </footer>
</html>
