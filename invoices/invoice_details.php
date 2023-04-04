<?php

require_once '../includes/dbconfig.php';
require_once '../classes/invoices.class.php';
require_once '../classes/account.class.php';

    //resume session here to fetch session values
    session_start();
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
        header('location: ../login/login.php');
    }

    if(isset($_GET['id'])){
        $invoices = $_GET['id'];
      }

// Fetch tenant information
$sql = "SELECT invoice.id, invoice.lease_unit_id, p.property_name, CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name, invoice.rent_due_date, invoice.amount_paid, invoice.monthly_rent, invoice.electricity, invoice.water, penalty.amount as penalty_amount, invoice.total_due, tenant.email, tenant.contact_no, invoice.payment_date, invoice.one_month_advance, invoice.balance, invoice.status
FROM invoice
JOIN tenant ON invoice.tenant_id = tenant.id
JOIN lease ON lease.id = invoice.lease_unit_id
JOIN penalty ON invoice.penalty_id = penalty.id
LEFT JOIN properties p ON invoice.property_id = p.id
WHERE invoice.id = ?";

// Prepare and bind the statement
$query = $conn->prepare($sql);
$query->bind_param("i", $invoices);

// Execute the statement
$query->execute();

// Bind the result variables
$query->bind_result($id, $lease_unit_id, $property_name, $tenant_name, $rent_due_date, $amount_paid, $rent, $electricity, $water, $penalty_amount, $total_due, $email, $contact_no, $payment_date, $one_month_advance, $balance, $status);

// Fetch the data
if ($query->fetch()) {
    $invoice = [
        'invoice_id' => $id,
        'property_name' => $property_name,
        'tenant_name' => $tenant_name,
        'unit_name' => $lease_unit_id,
        'rent' => $rent,
        'penalty' => $penalty_amount,
        'total_due' => $total_due,
        'rent_due_date' => $rent_due_date,
        'amount_paid' => $amount_paid,
        'electricity' => $electricity,
        'water' => $water,
        'email' => $email,
        'contact_no' => $contact_no,
        'one_month_advance' => $one_month_advance,
        'payment_date' => $payment_date,
        'balance' => $balance,
        'status' => $status
    ];
}


    //if the above code is false then html below will be displayed
    require_once '../tools/variables.php';
    $page_title = 'RMS | Invoices';
    $invoices = 'active';

    require_once '../includes/header.php';
?> 

<body>
<div class="container-scroller">
    <?php
        require_once '../includes/navbar.php';
    ?>
    <div class="container-fluid page-body-wrapper">
        <?php
            require_once '../includes/sidebar.php';
        ?>
        <div class="main-panel">
            <div class="content-wrapper">
                <div class="row">
                    <div class="col-12 col-xl-8 mb-4 mb-xl-0">
                        <h3 class="font-weight-bolder">INVOICE DETAILS</h3> 
                    </div>
                    <div class="add-page-container">
                        <div class="col-md-2 d-flex justify-align-between float-right">
                            <a href="" class='bx bx-caret-left'>Back</a>
                        </div>
                    </div>
                    <div id="receipt-main">
                        <div class="receipt-main mx-auto col-xs-10 col-sm-10 col-md-6 col-xs-offset-1 col-sm-offset-1">
                            <div class="row">
                                <div class="receipt-header">
                                    <div class="col-xs-12 col-sm-6 col-md-12 text-right">
                                        <div class="receipt-right">
                                            <img src="../img/logo.svg" alt="logo" style="max-width: 100px; height: auto;"/>
                                            <h5 class="text-uppercase">Sofiyyah Apartment Rental</h5>
                                            <p>+63 912 123 1234 <i class="fa fa-phone"></i></p>
                                            <p>sofiyyah@gmail.com <i class="fa fa-envelope-o"></i></p>
                                            <p>Zamboanga City <i class="fa fa-location-arrow"></i></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="receipt-header receipt-header-mid">
                                    <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                                        <div class="receipt-right">
                                            <h5><?php echo $tenant_name; ?> </h5>
                                            <p><b>Mobile :</b><?php echo $contact_no; ?> </p>
                                            <p><b>Email :</b><?php echo $email; ?> </p>
                                            <p><b>Rent Due Date :</b> <?php echo $rent_due_date; ?> </p>
                                            <p><b>STATUS:  <?php echo $status; ?></b></p>
                                        </div>
                                    </div>
                                    <div class="col-xs-4 col-sm-4 col-md-4">
                                        <div class="receipt-left">
                                            <h3>INVOICE # <?php echo $id; ?></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>		
                        <div>
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Description</th>
                                    <th>Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td class="col-md-9">Monthly Rent</td>
                                    <td class="col-md-3">₱ <?php echo $rent; ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9">Electricity</td>
                                    <td class="col-md-3">₱ <?php echo $electricity; ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9">Water</td>
                                    <td class="col-md-3">₱ <?php echo $water; ?></td>
                                </tr>
                                <tr>
                                    <td class="col-md-9">Penalty</td>
                                    <td class="col-md-3">₱ <?php echo $penalty_amount; ?></td>
                                </tr>
                                <tr>
                                    <td class="text-right">
                                    <p>
                                        <strong>Advance: </strong>
                                    </p>
                                    <p>
                                        <strong>Payable Amount: </strong>
                                    </p>
                                    <p>
                                        <strong>Balance Due: </strong>
                                    </p>
                                    </td>
                                    <td>
                                    <p>
                                        <strong>₱ <?php echo $one_month_advance; ?></strong>
                                    </p>
                                    <p>
                                        <strong>₱ <?php echo $amount_paid; ?></strong>
                                    </p>
                                    <p>
                                        <strong>₱ <?php echo $balance; ?></strong>
                                    </p>
                                    </td>
                                </tr>
                                <tr>
                                    
                                    <td class="text-right"><h2><strong>Total: </strong></h2></td>

                                    <td class="text-left text-danger"><h2><strong>₱  <?php echo $total_due; ?></strong></h2></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="row">
                            <div class="receipt-header receipt-header-mid receipt-footer">
                                <div class="col-xs-8 col-sm-8 col-md-8 text-left">
                                    <div class="receipt-right">
                                        <p><b>Date :</b> <?php echo $payment_date; ?></p>
                                        <h5 style="color: rgb(140, 140, 140);">Note: </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-secondary" onclick="window.print()">Print</button>
                                <button class="btn btn-success" onclick="downloadPDF()">Download</button>
                            </div>
                        </div>
                    </div>    
                </div>
            </div>


<script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.3.3/jspdf.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    function calculateBalance() {
  const rent = parseFloat(document.getElementById('monthly_rent').value) || 0;
  const electricity = parseFloat(document.getElementById('electricity').value) || 0;
  const water = parseFloat(document.getElementById('water').value) || 0;
  const penalty = parseFloat(document.getElementById('penalty').value) || 0;
  const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
  const useAdvance = document.getElementById('use_advance').checked;
  const oneMonthAdvance = <?php echo $invoice[0]['one_month_advance']; ?> || 0;

  let totalAmount = rent + electricity + water + penalty;
  let balance;
  let payableAmount;

  if (useAdvance) {
    payableAmount = totalAmount - oneMonthAdvance;
    balance = amountPaid - payableAmount;
  } else {
    balance = amountPaid - totalAmount;
    payableAmount = totalAmount;
  }
}

  document.getElementById('total_amount').value = totalAmount.toFixed(2);
  document.getElementById('balance').value = balance.toFixed(2);
  document.getElementById('payable_amount').value = payableAmount.toFixed(2); // Assuming you have an input field with the id 'payable_amount


  function downloadPDF() {
  // Use html2canvas to capture the div as an image
  html2canvas(document.querySelector("#receipt-main")).then(canvas => {
    // Convert the image to a data URL
    const imgData = canvas.toDataURL('image/png');

    // Initialize jsPDF with the portrait orientation
    const pdf = new jsPDF('p', 'mm', 'a4');

    // Add the image to the PDF
    pdf.addImage(imgData, 'PNG', 0, 0, 210, 297);

    // Download the PDF file
    pdf.save('receipt.pdf');
  });
}


</script>



