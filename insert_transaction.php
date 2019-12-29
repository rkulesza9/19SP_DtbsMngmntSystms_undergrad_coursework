<?php
  include 'dbconfig.php';
  include 'cookie.php';

  $cookie = $_COOKIE[$cookie_name];
  $submit = $_POST['submit'];
  $tcode = $_POST['tcode'];
  $ttype = $_POST['type'];
  $amount = $_POST['amount'];
  $source = $_POST['source'];
  $note = $_POST['note'];

  $continue = true;

  if(!isset($cookie)){
    echo <<<HTML
      <h1><a href='p2.html'>Click Here</a> to login!</h1>
HTML;
  $continue = false;
  }

  if(!isset($submit)){
    echo <<<HTML
        <h1><a href='login2.php'>Click Here</a> to resubmit the form!</h1>
HTML;
    $continue = false;
  }
  if($amount == ""){
    echo "<h1>Amount Cannot Be Empty!</h1>";
    $continue = false;
  }
  if(!is_numeric($amount)){
    echo "<h1>Amount Must Be A Number!</h1>";
    $continue = false;
  }
  if($amount != "" && $amount <= 0){
    echo "<h1>Amount Must Be Greater Than Zero!</h1>";
    $continue = false;
  }
  if(!isset($ttype)){
    echo "<h1>You Must Choose Deposit or Withdraw!</h1>";
    $continue = false;
  }
  if($source==""){
    echo "<h1>You Must Select A Source!</h1>";
    $continue = false;
  }

  if($continue){
    $sql = "Select sum(amount) From CPS3740_2019S.Money_kuleszar where cid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$cookie);
    $stmt->bind_result($balance);
    $stmt->execute();
    $stmt->fetch();
    $stmt->close();
    if($amount > $balance && $ttype=='W'){
      echo "<h1> Amount Cannot Be Greater Than Your Current Balance!</h1>";
    }else {
      $sql = "Select count(*) from CPS3740_2019S.Money_kuleszar where code=?";
      $stmt = $conn->prepare($sql);
      $stmt->bind_param("s",$tcode);
      $stmt->bind_result($tcode_num);
      $stmt->execute();
      $stmt->fetch();
      $stmt->close();
      if($tcode_num > 0){
        echo "<h1>Transaction with same code already exists.</h1>";
      }else{
        if($ttype == 'W') $amount = -1*$amount;
        $sql = "Insert into CPS3740_2019S.Money_kuleszar (code,cid,type,amount,mydatetime,sid,note) values(?,?,?,?,?,?,?)";
        $mydatetime = date('Y/m/d h:i:s a',time());
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sisdsis",$tcode,$cookie,$ttype,$amount,$mydatetime,$source,$note);
        $stmt->execute();
        if($conn->affected_rows > 0){
          $balance = $balance + $amount;
          echo("<p>Transaction was successful</p><p>Current Balance:$balance</p>");
          echo("<a href='login2.php'>Click Here</a> to go back to main page!");
        };
        $stmt->close();


      }
    }

  }

?>
