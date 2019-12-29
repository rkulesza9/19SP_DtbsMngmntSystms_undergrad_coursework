<?php
include 'dbconfig.php';
include 'cookie.php';

$customer = $_POST['cname'];
$balance = $_POST['cbal'];
$submit = $_POST['addt'];
$cookie = $_COOKIE[$cookie_name];

if(!isset($cookie)){
  echo <<<HTML
    <h1><a href='p2.html'>Click Here</a> to login!</h1>
HTML;

}elseif(!isset($submit)){
  ob_start();
  header("Location: login2.php");
  ob_end_flush();
  die();
}else {
  $header = <<<HTML
    <h2 class='To'>$customer</h2>
    <div class='address'><b>$customer</b> current balance is $balance</div>
HTML;

  //fill source select tags
  $sql = "SELECT id, name FROM Sources";
  $stmt = $conn->prepare($sql);
  $stmt->bind_result($id, $name);
  $stmt->execute();

  $options = "";
  while($stmt->fetch()){
    $options = $options."<option value='$id'>$name</option>";
  }

  $stmt->close();

}

?>
<html>
  <head>
    <title>Add Transaction</title>
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
                  <?php echo $header; ?><br>
                  <form action="insert_transaction.php" method="POST">
                    <div id='address'>Transaction Code: <input type='text' placeholder='Transaction Code' name='tcode'></div>
                    <div id='address'>Type of Transaction: <input type='radio' name='type' value='D'>Deposit&nbsp<input type='radio' name='type' value='W'>Withdraw</div>
                    <div id='address'>Amount: <input type='text' placeholder='Amount' name='amount'></div>
                    <div id='address'>Select Source: <select name='source'>
                          <option></option>
                          <?php echo $options; ?>
                        </select></div>
                    <div id='address'>Note: <input type='text' name='note' placeholder='Note'></div>
                    <div id='address'><input type='submit' name='submit' value='Submit'/></div>
                  </form>
                </main>
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
