<html>
  <head>
    <title>Display Customers</title>
    <link href="//netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
    <script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
    <!-- <Style>
      table,th,td,tr{
        border-color:black;
        border-style:solid;
        border-width:thin;
        border-collapse:collapse;
      }
    </style> -->
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
    <table>
      <?php
          include 'dbconfig.php';
          //mysql query for selecting stuff from table
          $sql = "SELECT * FROM Customers";
          $stmt = $conn->prepare($sql);
          $stmt->bind_result($id,$name,$login,$password,$DOB,$gender,$street,$city,$state,$zipcode);
          $stmt->execute();

          //fill table
          $insidetable = "";
          while($stmt->fetch()){
            $insidetable = $insidetable."<tr>";
            $insidetable = $insidetable."<td class='no'>".$id."</td><td class='qty'>".$login."</td><td class='qty'>".$password."</td><td class='qty'>".$name."</td><td class='qty'>".$gender."</td><td class='qty'>".$DOB."</td><td class='qty'>".$street."</td><td class='qty'>".$city."</td><td class='qty'>".$state."</td><td class='qty'>".$zipcode."</td>";
            $insidetable = $insidetable."</tr>";
          }

          $stmt->close();
       ?>
    </table>
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
                                <a href="p1.html">
                                CPS 3740 Project 1
                                </a>
                            </h2>
                            <div>by Robert Kulesza</div>

                        </div>
                    </div>
                </header>
    <main>
        <div class="row contacts">
          <div class="col invoice-to">

          </div>
            <div class="col invoice-details">
                <h1 class="invoice-id">DISPLAY CUSTOMERS</h1>
            </div>
        </div>
        The following customers are in the banking system:
    <table border="0" cellspacing="0" cellpadding="0">
        <thead>
            <tr>
                <th class="text-right">ID</th>
                <th class="text-right">LOGIN</th>
                <th class="text-right">PASSWORD</th>
                <th class="text-right">NAME</th>
                <th class="text-right">GENDER</th>
                <th class="text-right">DOB</th>
                <th class="text-right">STREET</th>
                <th class="text-right">CITY</th>
                <th class="text-right">STATE</th>
                <th class="text-right">ZIPCODE</th>
            </tr>
        </thead>
        <tbody>
            <?php echo $insidetable ?>
        </tbody>
        <tfoot>
        </tfoot>
    </table>
  </main>
  </body>
  <footer>
  </footer>
</html>
