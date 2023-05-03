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
    if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
        header('location: ../login/login.php');
    }
  
    //if the above code is false then html below will be displayed

    // Fetch year and month from the URL
    $year = isset($_GET['year']) ? intval($_GET['year']) : 0;
    $month = isset($_GET['month']) ? intval($_GET['month']) : 0;

    // Fetch the invoice data for the given month
    $sql = "SELECT invoice.id, tenant.id as tenant_id, CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name, invoice.lease_unit_id, 
    invoice.monthly_rent, invoice.electricity, invoice.water, penalty.amount as penalty_amount, (invoice.amount_paid - invoice.monthly_rent - (invoice.electricity + invoice.water)) as balance, invoice.total_due, invoice.amount_paid, invoice.rent_due_date, invoice.status, invoice.payment_date, invoice.fixed_bills, invoice.monthly_bills
    FROM invoice
    JOIN tenant ON invoice.tenant_id = tenant.id
    JOIN lease ON invoice.lease_unit_id = lease.id
    JOIN penalty ON invoice.penalty_id = penalty.id
    WHERE YEAR(rent_due_date) = ? AND MONTH(rent_due_date) = ?";

    
    // Prepare and bind the statement
    $query = $conn->prepare($sql);
    $query->bind_param("ii", $year, $month);

    // Execute the statement
    $query->execute();

    // Bind the result variables
    $query->bind_result($id, $tenant_id, $tenant_name, $lease_unit_id, $monthly_rent, $electricity, $water, $penalty_amount, $balance, $total_due, $amount_paid, $rent_due_date, $status, $payment_date, $fixed_bills, $monthly_bills);


    // Fetch the data
    while ($query->fetch()) {
      $invoice[] = [
        'id' => $id,
          'tenant_id' => $tenant_id,
          'tenant_name' => $tenant_name,
          'unit_name' => $lease_unit_id,
          'rent' => $monthly_rent,
          'electricity' => $electricity,
          'water' => $water,
          'penalty' => $penalty_amount,
          'balance' => $balance,
          'total_due' => $total_due,
          'rent_due_date' => $rent_due_date,
          'amount_paid' => $amount_paid,
          'status' => $status,
          'payment_date' => $payment_date,
          'fixed_bills' => $fixed_bills,
          'monthly_bills' => $monthly_bills
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
<div class="loading-screen">
  <img class="logo" src="../img/logo-edit.png" alt="logo">
  <?php echo $page_title; ?>
  <div class="loading-bar"></div>
</div>
<div class="container-scroller">
  <?php
    require_once '../includes/navbar.php';
  ?>
<div class="container-fluid page-body-wrapper">
<?php
        if (isset($_SESSION['user_type'])) {
            if ($_SESSION['user_type'] == 'landlord') {
                require_once '../alandlord-dash/landlord_sidebar.php';
            } elseif ($_SESSION['user_type'] == 'admin') {
                require_once '../includes/sidebar.php';
            }
            // Add more conditions for other user types if needed
        } else {
            // Redirect to login or show a default sidebar if the user type is not set
        }
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
        <th>Total Due</th>
        <th>Rent Due Date</th>
        <th>Amount Paid</th>
        <th>Status</th>
        <th>Action</th>
    </tr>
</thead>
<tbody>
    <?php
$sql = "SELECT invoice.id, property_units.unit_no, CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name, invoice.monthly_rent, invoice.rent_due_date, invoice.electricity, invoice.water, invoice.penalty_id, IFNULL(penalty.amount, 0) AS penalty, invoice.total_due, invoice.amount_paid, invoice.balance, invoice.status, invoice.fixed_bills, invoice.monthly_bills, invoice.tenant_id
FROM invoice
INNER JOIN lease ON lease.id = invoice.lease_unit_id
INNER JOIN property_units ON property_units.id = lease.property_unit_id
INNER JOIN tenant ON tenant.id = invoice.tenant_id
LEFT JOIN penalty ON penalty.id = invoice.penalty_id
ORDER BY invoice.id DESC";

    $result = mysqli_query($conn, $sql);
    $i = 1;
    if (mysqli_num_rows($result) > 0) {
        while ($invoice_data = mysqli_fetch_assoc($result)) {
            $invoice_data['monthly_bills'] = "";
            if ($invoice_data['fixed_bills']) {
                $invoice_data['monthly_bills'] .= "Electricity: ₱" . $invoice_data['electricity'] . "<br><br>";
                $invoice_data['monthly_bills'] .= "Water: ₱" . $invoice_data['water'] . "<br><br>";
                $invoice_data['monthly_bills'] .= "(Fixed)";
            } else {
                $invoice_data['monthly_bills'] .= "Electricity: ₱" . $invoice_data['electricity'] . "<br><br>";
                $invoice_data['monthly_bills'] .= "Water: ₱" . $invoice_data['water'] . "<br><br>";
            }

            echo '
            <tr>
                <td>' . $i . '</td>
                <td>' . $invoice_data['tenant_name'] . '</td>
                <td>' . $invoice_data['unit_no'] . '</td>
                <td>' . $invoice_data['monthly_rent'] . '</td>
                <td>' . $invoice_data['monthly_bills'] . ($invoice_data['fixed_bills'] ? ' (Fixed)' : '') . '</td>
                <td>' . $invoice_data['penalty'] . '</td>
                <td>' . $invoice_data['total_due'] . '</td>
                <td>' . $invoice_data['rent_due_date'] . '</td>
                <td>' . $invoice_data['amount_paid'] . '</td>
                <td>' . $invoice_data['status'] . '</td>
                <td><button class="show3" onclick="redirectTo(\'invoice_details.php?id=' . $invoice_data['id'] . '\')">Show Details</button></td>
            </tr>';
            $i++;
        }
    }
    ?>
</tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

<script>
function redirectTo(url) {
    window.location.href = url;
}
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


