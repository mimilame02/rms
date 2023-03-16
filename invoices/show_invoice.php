<?php
  require_once '../includes/dbconfig.php';
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

  // Replace with the actual lease_unit_id
  $lease_unit_id = 1;

  // SQL query to fetch tenant information
  $sql = "SELECT invoice.lease_unit_id, tenant.name as tenant_name, tenant.email, tenant.contact_no
          FROM invoice
          JOIN tenant ON invoice.tenant_id = tenant.id
          WHERE invoice.lease_unit_id = ?";

  // Prepare and bind the statement
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("i", $lease_unit_id);

  // Execute the statement
  $stmt->execute();

  // Bind the result variables
  $stmt->bind_result($lease_unit_id, $tenant_name, $email, $contact_no);

  // Fetch the data
  if ($stmt->fetch()) {
      // The data is now available in the bound variables
  } else {
      // No data found for the given lease_unit_id
  }
  require_once '../tools/variables.php';
  $page_title = 'RMS | Invoices';
  $invoices = 'active';

  require_once '../includes/header.php';
?>
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
            <h3 class="font-weight-bolder">PAY INVOICE</h3>
          </div>
          <div class="col-12 grid-margin">
            <div class="card">
              <div class="card-body">
                <div class="col-md-6">
                  <div class="row">
                    <div class="col">
                      <h4 class="font-weight-bolder"><?php echo htmlspecialchars($lease_unit_id); ?></h4>
                      <p>Tenant Name: <?php echo htmlspecialchars($tenant_name); ?></p>
                      <p>Email: <?php echo htmlspecialchars($email); ?></p>
                      <p>Contact No: <?php echo htmlspecialchars($contact_no); ?></p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <form action="show_invoice.php" method="post" id="pay-invoice-form">
            <div class="col-6 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Bills Payable</h4>
                  <!-- Change IDs for each input element -->
                  <div class="form-group">
                    <label for="monthly_rent">Monthly Rent</label>
                    <input type="number" class="form-control" id="monthly_rent" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="electricity">Electricity</label>
                    <input type="number" class="form-control" id="electricity" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="water">Water</label>
                    <input type="number" class="form-control" id="water" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="penalty">Penalty</label>
                    <input type="number" class="form-control" id="penalty" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="balance">Balance</label>
                    <input type="number" class="form-control" id="balance" placeholder="(default)" disabled>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-6 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Pay Here</h4>
                  <div class="form-group">
                    <label for="total_amount">Total Amount to Pay</label>
                    <input type="number" class="form-control" id="total_amount" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="amount_paid">Amount Paid</label>
                    <!-- amount_paid minus the rent_paid will be the balance -->
                    <input type="number" class="form-control" id="amount_paid" placeholder="">
                  </div>
                  <button type="submit" class="btn btn-primary float-right mr-2">Pay Now</button>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
      