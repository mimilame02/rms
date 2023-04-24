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
if (!isset($_SESSION['user_type']) || ($_SESSION['user_type'] != 'admin' && $_SESSION['user_type'] != 'landlord')) {
  header('location: ../login/login.php');
}

//if the above code is false then html below will be displayed

if(isset($_GET['id'])){
  $invoices = $_GET['id'];
}


// Fetch tenant information
$sql = "SELECT invoice.id, invoice.lease_unit_id, p.property_name, CONCAT(tenant.first_name, ' ', tenant.last_name) as tenant_name, invoice.rent_due_date, invoice.amount_paid, invoice.monthly_rent, invoice.electricity, invoice.water, penalty.amount as penalty_amount, invoice.total_due, tenant.email, tenant.contact_no, invoice.payment_date, invoice.one_month_advance, invoice.balance
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
$query->bind_result($id, $lease_unit_id, $property_name, $tenant_name, $rent_due_date, $amount_paid, $rent, $electricity, $water, $penalty_amount, $total_due, $email, $contact_no, $payment_date, $one_month_advance, $payment_date);



// Fetch the data
while ($query->fetch()) {
    $invoice[] = [
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
        'payment_date' => $payment_date
    ];
}

$current_day = date('F', strtotime('now'));
$_SESSION['current_day'] = $current_day;


require_once '../tools/variables.php';
$page_title = 'RMS | Invoices';
$invoices = 'active';

require_once '../includes/header.php';
?>
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
          </div>
          <form action="update_invoice.php" method="post" id="pay-invoice-form">
            <div class="col-12 grid-margin">
              <div class="card">
                <div class="card-body">
                  <div class="col-lg-12">
                    <div class="row">
                      <div class="col-md-6 d-flex flex-column align-items-start">
                        <p class="fs-6">Tenant Name: <strong><?php echo "&nbsp;&nbsp;&nbsp;".$tenant_name; ?></strong></p>
                        <p class="fs-6">Email: <strong><?php echo "&nbsp;&nbsp;&nbsp;".$email; ?></strong></p>
                        <p class="fs-6">Contact No: <strong><?php echo "&nbsp;&nbsp;&nbsp;".$contact_no; ?></strong></p>
                      </div>
                      <div class="col-md-6 align-items-center justify-content-start">
                        <div class="col-md-12">
                          <h4 class="font-weight-bolder number-border">Lease No: <strong><?php echo "&nbsp;&nbsp;&nbsp;".$lease_unit_id; ?></strong></h4>
                        </div>
                        <div class="col-md-12">
                          <h4 class="font-weight-bolder number-border">Property Name: <strong><?php echo "&nbsp;&nbsp;&nbsp;".$property_name; ?></strong></h4>
                        </div>
                        <div class="ms-3 d-flex align-items-center justify-content-end col-md-10 float-right">
                          <label for="payment_date" class="font-weight-bolder number-border">Date:</label>
                          <input type="date" id="payment_date" class="form-control form-control-sm" name="payment_date" value="<?php echo date('F j, Y'); ?>" required>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row g-3">
              <div class="col-6 grid-margin">
                <div class="card">
                  <div class="card-body">
                  <input type="hidden" value="<?php echo $id; ?>" name="invoice_id">
                    <h4 class="card-title">Bills Payable</h4>
                    <!-- Change IDs for each input element -->
                    <div class="form-group">
                      <label for="monthly_rent">Monthly Rent</label>
                      <input type="number" class="form-control" id="monthly_rent" name="monthly_rent" placeholder="(default)" disabled>
                    </div>
                    <div class="form-group">
                      <label for="electricity">Electricity</label>
                      <input type="number" class="form-control" id="electricity" name="electricity" placeholder="(default)" disabled>
                    </div>
                    <div class="form-group">
                      <label for="water">Water</label>
                      <input type="number" class="form-control" id="water" name="water" placeholder="(default)" disabled>
                    </div>
                    <div class="form-group">
                      <label for="penalty">Penalty</label>
                      <input type="number" class="form-control" id="penalty" name="penalty_id" placeholder="(default)" disabled>
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
                      <input type="number" class="form-control" id="total_amount" name="total_amount" placeholder="(default)" disabled>
                    </div>
                    <div class="form-group">
                      <div class="form-check text-dark d-flex align-content-center float-right">
                        <input class="checkmark req" type="checkbox" value="" id="use_advance">
                        <label class="pl-2 mb-0 text-break fs-8" for="use_advance">
                          Use One Month Advance
                        </label>
                      </div>
                    </div>
                    <div class="form-group">
                      <label for="amount_paid">Amount Paid</label>
                      <!-- amount_paid minus the amount_paid will be the balance -->
                      <input type="number" class="form-control" id="amount_paid" name="amount_paid" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="balance">Balance</label>
                      <!-- amount_paid minus the amount_paid will be the balance -->
                      <input type="number" class="form-control" id="balance" name="balance" placeholder="(total balance)" readonly>
                    </div>
                    <input type="submit" class="btn btn-primary float-right mr-2" value="Pay Now" name="save">
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
      

  <script>


function updateFields() {
  const invoiceData = <?php echo json_encode($invoice[0]); ?>;
  
  document.getElementById('monthly_rent').value = invoiceData.rent || '';
  document.getElementById('electricity').value = invoiceData.electricity || '';
  document.getElementById('water').value = invoiceData.water || '';
  document.getElementById('penalty').value = invoiceData.penalty || '';
  document.getElementById('total_amount').value = invoiceData.total_due || '';
  document.getElementById('amount_paid').value = invoiceData.amount_paid || '';
}

// Call updateFields() when the page is loaded
updateFields();

// Calculate balance based on amount paid
function calculateBalance() {
  const rent = parseFloat(document.getElementById('monthly_rent').value) || 0;
  const electricity = parseFloat(document.getElementById('electricity').value) || 0;
  const water = parseFloat(document.getElementById('water').value) || 0;
  const totalAmount = parseFloat(document.getElementById('total_amount').value) || 0;
  const amountPaid = parseFloat(document.getElementById('amount_paid').value) || 0;
  const useAdvance = document.getElementById('use_advance').checked;
  const oneMonthAdvance = <?php echo $invoice[0]['one_month_advance']; ?> || 0;

  let balance;
  if (useAdvance) {
    balance = oneMonthAdvance - (rent + electricity + water);
  } else {
    balance = totalAmount - amountPaid;
  }

  // Set balance to absolute value
  balance = Math.abs(balance);

  document.getElementById('balance').value = balance.toFixed(2);
}



// Add event listeners
document.getElementById('amount_paid').addEventListener('input', calculateBalance);
document.getElementById('use_advance').addEventListener('change', calculateBalance);


</script>
