<?php
    require_once '../includes/dbconfig.php';

    $invoice = [];

    require_once '../classes/invoices.class.php';

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
    //if the above code is false then html below will be displayed

    // Fetch year and month from the URL
    $year = isset($_GET['year']) ? intval($_GET['year']) : 0;
    $month = isset($_GET['month']) ? intval($_GET['month']) : 0;

    // Fetch the invoice data for the given month
    $sql = "SELECT tenant.id as tenant_id, CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name, invoice.lease_unit_id, 
            invoice.monthly_rent, (invoice.electricity + invoice.water) as monthly_bills, penalty.amount as penalty_amount, (invoice.rent_paid - invoice.monthly_rent - (invoice.electricity + invoice.water)) as balance, (invoice.monthly_rent + (invoice.electricity + invoice.water) + penalty.amount + (invoice.rent_paid - invoice.monthly_rent - (invoice.electricity + invoice.water))) as total_due, invoice.rent_paid, invoice.status
            FROM invoice
            JOIN tenant ON invoice.tenant_id = tenant.id
            JOIN lease ON invoice.lease_unit_id = lease.id
            JOIN penalty ON invoice.penalty_id = penalty.id
            WHERE YEAR(invoice_date) = ? AND MONTH(invoice_date) = ?";
    
    // Prepare and bind the statement
    $query = $conn->prepare($sql);
    $query->bind_param("ii", $year, $month);

    // Execute the statement
    $query->execute();

    // Bind the result variables
    $query->bind_result($tenant_id, $tenant_name, $lease_unit_id, $monthly_rent, $monthly_bills, $penalty_amount, $balance, $total_due, $rent_paid, $status);

    // Fetch the data
    while ($query->fetch()) {
      $invoice[] = [
        'tenant_id' => $tenant_id,
        'tenant_name' => $tenant_name,
        'unit_name' => $lease_unit_id,
        'rent' => $monthly_rent,
        'monthly_bills' => $monthly_bills,
        'penalty' => $penalty_amount,
        'balance' => $balance,
        'total_due' => $total_due,
        'rent_paid' => $rent_paid,
        'status' => $status
      ];
    }

  // Close the statement
  $query->close();


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
        <h3 class="font-weight-bolder">INVOICES</h3> 
        <h6 class="font-weight-normal mb-0">Invoice Listing for the Month of <?php echo date('F', mktime(0, 0, 0, $month, 1)).' '.$year; ?></h6>                
      </div>
      <div class="add-tenant-container">
      </div>
      <div class="row mt-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive pt-3">
              <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Tenant Name</th>
                    <th>Unit Name</th>
                    <th>Rent</th>
                    <th>Monthly Bills</th>
                    <th>Penalty</th>
                    <th>Balance</th>
                    <th>Total Due</th>
                    <th>Amount Paid</th>
                    <th>Status</th>
                    <th>Action</th>
                        
                  </tr>
                </thead>
                <tbody>
                  <?php
                  $i = 1;
                  if (count($invoice) > 0) {
                    foreach ($invoice as $invoice_data) {
                      echo '
                        <tr>
                          <td>' . $i . '</td>
                          <td>' . $invoice_data['tenant_name'] . '</td>
                          <td>' . $invoice_data['unit_name'] . '</td>
                          <td>' . $invoice_data['rent'] . '</td>
                          <td>' . $invoice_data['monthly_bills'] . '</td>
                          <td>' . $invoice_data['penalty'] . '</td>
                          <td>' . $invoice_data['balance'] . '</td>
                          <td>' . $invoice_data['total_due'] . '</td>
                          <td>' . $invoice_data['rent_paid'] . '</td>
                          <td>' . $invoice_data['status'] . '</td>
                          <td><button class="show3" onclick="redirectTo(\'invoice_details.php?tenant_id=' . $invoice_data['tenant_id'] . '\')">Show Details</button></td>
                        </tr>';
                      $i++;
                    }
                  } else {
                    echo '
                      <tr>
                        <td colspan="11">No records found.</td>
                      </tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

<script>
// Initialize DataTables
$('#example').DataTable({
  responsive: {
    breakpoints: [
      {name: 'bigdesktop', width: Infinity},
      {name: 'meddesktop', width: 1480},
      {name: 'smalldesktop', width: 1280},
      {name: 'medium', width: 1188},
      {name: 'tabletl', width: 1024},
      {name: 'btwtabllandp', width: 848},
      {name: 'tabletp', width: 768},
      {name: 'mobilel', width: 480},
      {name: 'mobilep', width: 320}
    ]
  },
  language: {
    emptyTable: "No data available in table"
  }
});

</script>


