<?php
  include 'dbconfig.php';
  include 'cookie.php';

  $cookie = $_COOKIE[$cookie_name];

  if(!isset($cookie)){
    echo "<h1><a href='login2.php'>Click Here</a> To Login!</h1>";
  } else {
    $table_intro = "<div class='address' >You can only update Note column</div>";

    $sql = "select a.mid, a.code, a.amount, a.type, b.name, a.mydatetime, a.note from CPS3740_2019S.Money_kuleszar a, CPS3740.Sources b where a.sid=b.id and cid=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i",$cookie);
    $stmt->bind_result($mid,$code,$amount,$type,$source,$mydatetime,$note);
    $stmt->execute();

    $transactions = "";
    $balance = 0;
    while($stmt->fetch()){
      $balance += $amount;
      $color = $amount > 0 ? "blue" : "red";
      $transactions = $transactions.<<<HTML
        <tr><td class='no'>$mid</td><td>$code</td><td style='color:$color;'>$amount</td><td>$type</td><td>$source</td><td>$mydatetime</td><td><input type='text' value='$note' name='notes[]' style='background-color:yellow;'><input type='hidden' name='codes[]' value='$code' ></td><td><input type='checkbox' name='delete[]' value='$code'>&nbspDelete</td></tr>
HTML;
    }
    $stmt->close();
  }

  //$header, $table_intro, $balance, $transactions
?>
<html>
  <head>
    <title>Display and Update Transactions</title>
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
                    <form method='POST' action='update_transaction.php'>
                    <table border="0" cellspacing="0" cellpadding="0">
                        <thead>
                            <tr>
                              <th class='text-left'>ID</th>
                              <th class="text-left">CODE</th>
                              <th class="text-left">AMOUNT</th>
                              <th class="text-left">OPERATION</th>
                              <th class='text-left'>SOURCE</th>
                              <th class="text-left">DATETIME</th>
                              <th class="text-left">NOTE</th>
                              <th class='text-left'>DELETE</th>
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
                    <input type='submit' name='submit' value='Update Transaction' />
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
