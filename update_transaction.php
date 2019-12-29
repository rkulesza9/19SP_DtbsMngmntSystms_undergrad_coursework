<?php
  include 'dbconfig.php';
  include 'cookie.php';

  $cookie = $_COOKIE[$cookie_name];
  $submit = $_POST['submit'];
  $delete = $_POST['delete'];
  $notes = $_POST['notes'];
  $codes = $_POST['codes'];

  if(!isset($cookie)){
    echo "<h1><a href='login2.php'>Click Here</a> To Login!</h1>";
  } else if(!isset($submit)){
    echo "<h1>Please Go Back To Submit The Form</h1>";
  } else {
    $num_deleted = 0;
    $num_updated = 0;
    if(isset($delete)){
      $sql = "delete from CPS3740_2019S.Money_kuleszar where code=? ";
      $stmt = $conn->prepare($sql);
      foreach($delete as $code){
        $stmt->bind_param("s",$code);
        $stmt->execute();
        if($stmt->affected_rows > 0) echo "<p>The transaction with code <b>".$code."</b> has been deleted from the database.</p>";
        $num_deleted += $stmt->affected_rows;
        if($stmt->error != ""){
          echo "<p>Failed to update transaction with code $code.</p>";
          echo $stmt->error;
        }
      }
      $stmt->close();
    }
    if(isset($notes)){
      $sql = "update CPS3740_2019S.Money_kuleszar set note=? where code=?";
      $stmt = $conn->prepare($sql);
      for($x=0;$x<count($notes);$x++){
        $note = $notes[$x];
        $code = $codes[$x];
        $stmt->bind_param("ss",$note,$code);
        $stmt->execute();
        echo $stmt->error;
        if($stmt->affected_rows > 0) echo "<p>The transaction with code <b>$code</b> was updated.</p>";
        $num_updated += $stmt->affected_rows;
        if($stmt->error != ""){
          echo "<p>Failed to update transaction with code $code.</p>";
          echo $stmt->error;
        }
      }
      $stmt->close();
    }
    echo "<p>".$num_deleted." rows were deleted successfully!</p>";
    echo "<p>".$num_updated." rows were updated successfully!</p>";
  }
?>
<title>Update Transaction</title>
<p><a href='login2.php'>Click Here</a> to go back to the main page!</p>
