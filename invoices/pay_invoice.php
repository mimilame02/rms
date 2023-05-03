<?php

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

    require_once '../tools/variables.php';
    $page_title = 'RMS | Invoices';
    $invoices = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
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
        <h3 class="font-weight-bolder">PAY INVOICE</h3> 
        <h6 class="font-weight-normal mb-0">Unpaid Invoice Listings</h6>
      </div>
      <div class="add-tenant-container">
        <div class="add-tenant-container">
        </div>
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
                    <th>Unit No.</th>
                    <th>Monthly Rent</th>
                    <th>Monthly Bills</th>
                    <th>Penalty</th>
                    <th>Rent Due Date</th>
                    <th>Total Due</th>
                    <th>Status</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  // Fetch the invoice data
                  $sql = "SELECT invoice.id, tenant.id as tenant_id, CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name, invoice.lease_unit_id, property_units.id as property_unit_id, 
                  invoice.monthly_rent, (invoice.electricity + invoice.water) as monthly_bills, penalty.amount as penalty_amount, invoice.total_due, invoice.rent_due_date, invoice.status
                  FROM invoice
                  JOIN tenant ON invoice.tenant_id = tenant.id
                  JOIN lease ON invoice.lease_unit_id = lease.id
                  JOIN property_units ON property_units.id = invoice.property_unit_id
                  JOIN penalty ON invoice.penalty_id = penalty.id
                  WHERE invoice.status = 'Unpaid'";

                  $result = mysqli_query($conn, $sql);
                  $i = 1;
                  if (mysqli_num_rows($result) > 0){
                    while ($row = mysqli_fetch_assoc($result)){
                      echo '
                        <tr>
                          <td>'.$i.'</td>
                          <td>'.$row['tenant_name'].'</td>
                          <td>'.$row['property_unit_id'].'</td>
                          <td>'.$row['monthly_rent'].'</td>
                          <td>'.$row['monthly_bills'].'</td>
                          <td>'.$row['penalty_amount'].'</td>
                          <td>'.$row['rent_due_date'].'</td>
                          <td>'.$row['total_due'].'</td>
                          <td>'.$row['status'].'</td>
                          <td><button class="show2" onclick="redirectTo(\'show_invoice.php?id='.$row['id'].'\')">PAY NOW</button></td>
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
    $('#example').DataTable( {
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
  }
} );
</script>