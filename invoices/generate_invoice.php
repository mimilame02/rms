<?php
require_once '../tools/functions.php';
require_once '../includes/dbconfig.php';
require_once '../classes/leases.class.php';
require_once '../classes/invoices.class.php';
require_once '../classes/tenants.class.php';
require_once '../classes/penalty.class.php';

//resume session here to fetch session values
session_start();
/*
    if user is not login then redirect to login page,
    this is to prevent users from accessing pages that requires
    authentication such as the dashboard
*/
if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin') {
    header('location: ../login/login.php');
}

$db = new Database();
$lease = new Leases($db);
$penalty = new Penalty($db);
$invoice = new Invoice();

// Fetch all leases
$all_leases = $lease->fetch_all_leases();

// Fetch all penalty
$penalty_data = $penalty->show();

/* print_r($_POST);
print_r($all_leases);
print_r($invoice); */


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $invoice = new Invoice();

    if (isset($_POST['action']) && $_POST['action'] == 'fetch_lease_data' && isset($_POST['lease_unit_id'])) {
        $lease_unit_id = $_POST['lease_unit_id'];

        // Fetch lease unit data based on lease_unit_id using the existing lease_fetch function
        $lease_data = $lease->lease_fetch($lease_unit_id);

        // Return the data as JSON
        echo json_encode($lease_data);

    } elseif (isset($_POST['action']) && $_POST['action'] == 'fetch_penalty_amount' && isset($_POST['penalty'])) {
        $penalty_id = $_POST['penalty'];

        // Fetch penalty data based on penalty_id using the existing fetch_penalty function
        $penalty_data = $penalty->fetch_penalty($penalty_id);

        // Return the penalty_amount as JSON
        echo json_encode(['penalty_amount' => $penalty_data['amount']]);

    } elseif(isset($_POST['save'])){
      //sanitize user inputs
      $invoice->lease_unit_id = $_POST['lease_unit_id'];
      $invoice->tenant_id = $_POST['tenant_name'];
      $invoice->property_id = $_POST['property_name'];
      $invoice->property_unit_id = $_POST['property_unit'];
      $invoice->monthly_rent = isset($_POST['monthly_rent']) ? $_POST['monthly_rent'] : 0;
      $invoice->one_month_deposit = isset($_POST['one_month_deposit']) ? $_POST['one_month_deposit'] : 0;
      $invoice->one_month_advance = isset($_POST['one_month_advance']) ? $_POST['one_month_advance'] : 0;
      
      $invoice->rent_due_date = $_POST['rent_due_date'];      
      $invoice->penalty_id = $_POST['penalty'];    
      $invoice->total_due = $_POST['total_due'];
      
      // Set values for the missing attributes
      $invoice->amount_paid = 0; // or whatever default value you want
      $invoice->status = 'Unpaid'; // or whatever default value you want
      $invoice->payment_date = '0000-00-00'; // or another default date value
      $invoice->balance = 0.00;

      // Set fixed_bills field and monthly_bills field based on input
      $invoice->fixed_bills = isset($_POST['fixed_bills']) && $_POST['fixed_bills'] == 'on' ? 1 : 0;

      if ($invoice->fixed_bills == 1 && !empty($lease_data['electricity']) && !empty($lease_data['water'])) {
        $invoice->electricity = $lease_data['electricity'] ?? 0;
        $invoice->water = $lease_data['water'] ?? 0;
    } else {
        $invoice->electricity = $_POST['electricity'];
        $invoice->water = $_POST['water'];
    }
    
      
      if ($invoice->fixed_bills == 1) {
          $invoice->monthly_bills = [
              'num_of_invoices' => $_POST['num_of_invoices'],
              'interval_in_months' => $_POST['interval_in_months']
          ];

          $num_of_invoices = isset($_POST['num_of_invoices']) ? (int)$_POST['num_of_invoices'] : 0;
          $interval_in_months = isset($_POST['interval_in_months']) ? (int)$_POST['interval_in_months'] : 0;

          // Loop through the number of invoices
          for ($i = 0; $i < $num_of_invoices; $i++) {
              // Update the rent_due_date based on the interval_in_months
              $invoice->rent_due_date = date('Y-m-d', strtotime("+" . $i * $interval_in_months . " months", strtotime($_POST['rent_due_date'])));

              // Add the invoice to the database
              if (!$invoice->invoice_add()) {
                  // Handle invoice add error
                  $msg = "Error uploading invoice";
                  break;
              }
          }
      }
      
      // Add property to database
      if ($invoice->invoice_add($tenant_id)) {
        header('Location: invoices.php');
        exit; // always exit after redirecting
      } else {
        // handle property add error
        $msg = "Error uploading lease";
      }
    }
}
require_once '../tools/variables.php';
$page_title = 'RMS | Generate Invoice';
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
            <h3 class="font-weight-bolder">GENERATE INVOICE</h3> 
          </div>
          <form action="generate_invoice.php" method="post" onchange="calculateTotal();">
            <div class="row g-3">
              <div class="col-md-6 grid-margin">
                <div class="card">
                  <div class="card-body">
                    <h4 class="card-title">Invoice Details</h4>
                    <div class="">
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="property_name">Property Name</label><span class="req"> *</span>
                          <select class="form-control form-control-sm" placeholder="" id="property_name" name="property_name"  onchange="filterPropertyUnits()" required>
                              <option value="none">--Select--</option>
                              <?php
                                  // Connect to the database and retrieve the list of properties
                                  $result = mysqli_query($conn, "SELECT * FROM properties;");
                                  while ($row = mysqli_fetch_assoc($result)) {
                                      $selected = "";
                                      if (isset($_POST['property_name']) && $_POST['property_name'] == $row['id']) {
                                          $selected = "selected";
                                      }
                                      echo "<option value='" . $row['id'] . "' " . $selected . ">" . $row['property_name'] . "</option>";
                                  }
                              ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="property_unit">Property Unit No.</label><span class="req"> *</span>
                          <select class="form-control form-control-sm mb-3 req" id="property_unit" name="property_unit" onchange="filterLeaseUnits()">
                              <option class="col-md-6" value="" disabled selected>Select Unit No.</option>
                              <?php
                                  // Connect to the database and retrieve the list of properties and property units using SQL JOIN
                                  $result = mysqli_query($conn, "SELECT pu.*, p.property_name
                                      FROM property_units pu 
                                      RIGHT JOIN properties p ON pu.property_id = p.id
                                      WHERE status IN ('Vacant', 'Occupied')
                                      ORDER BY p.property_name;");
                                  while ($row = mysqli_fetch_assoc($result)) {
                                      echo "<option value='" . $row['id'] . "' data-rent='" . $row['monthly_rent'] . "' data-deposit='" . $row['one_month_deposit'] . "' data-advance='" . $row['one_month_advance'] . "'data-property='" . $row['property_id'] . "'>" . $row['unit_no'] . "</option>";
                                  }
                              ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="lease_unit_id">Lease No.</label><span class="req"> *</span>
                          <select name="lease_unit_id" id="lease_unit_id" class="form-select form-control" onchange="updateFields()">
                            <option value="">-- Select --</option>
                            <?php
                              foreach ($all_leases as $lease) {
                                echo "<option value='" . $lease['id'] . "' data-property-unit='" . $lease['property_unit_id'] . "' data-tenant_id = '" . $lease['tenant_id'] . "' data-tenant_first_name = '" . $lease['first_name'] . "' data-tenant_last_name = '" . $lease['last_name'] . "' data-rent = '" . $lease['monthly_rent'] . "' data-deposit = '" . $lease['one_month_deposit'] . "' data-advance = '" . $lease['one_month_advance'] . "' data-electricity = '" . $lease['electricity'] . "' data-water = '" . $lease['water'] . "' data-lease_start= '" . $lease['lease_start'] . "'> Lease # " . $lease['id'] . "</option>";

                            }
                            
                              ?>
                          </select>
                        </div>
                      </div>
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="tenant_name">Tenant Name</label>
                          <!-- Add this element to your HTML where you want to store the tenant_id -->
                          <input type="hidden" id="tenant_name" name="tenant_name" readonly>
                          <!-- Add this element to your HTML where you want to display the tenant's full name -->
                          <input class="form-control" type="text" id="tenant_display_name" placeholder="(default)" readonly>
                        </div>
                      </div>    
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="rent_due_dates_container">Rent Due Date</label>
                          <input type="date" name="rent_due_date" min="<?php echo date('Y-m-d', strtotime('-1 month')); ?>" class="form-control" id="rent_due_dates_container" placeholder="(default)" required>
                        </div>
                      </div>
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="one_month_deposit">One Month Deposit</label>
                          <input type="number" class="form-control" name="one_month_deposit" placeholder="(default)" id="hidden_one_month_deposit" value="" readonly>
                        </div>
                      </div>
                      <div class="col-md-12 pl-0">
                        <div class="form-group">
                          <label for="one_month_advance">One Month Advance</label>
                          <input type="number" class="form-control" name="one_month_advance" placeholder="(default)" id="hidden_one_month_advance" value="" readonly>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <input type="hidden" id="current_invoice_number" name="current_invoice_number" value="0">
              <div class="col-md-6 grid-margin stretch-card d-block">
                <div class="cardbox mb-4">
                  <div class="card-body">
                    <div class="form-group">
                      <h5 class="w-100 font-weight-bold" for="total_due">Total Amount Due <span class="text-muted">*</span><span class="text-muted text-break fs-7 mt-1 mb-0 float-right"> should be received</span></h5>
                      <input type="number" name="total_due" class="form-control" id="total_due" placeholder="0.00" readonly>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body">
                  <h4 class="card-title">Billing
                    <!-- Create a CheckBox if bills for water and electricity is fixed before fetching from lease table default values on updateFields()  -->
                    <div class="d-flex float-right" style=" font-size: var(--fs-8);">
                      <input class="checkmark req" type="checkbox" id="fixed_bills">
                      <label class="pl-2 mb-0 text-break fs-8" for="fixed_bills">
                      Fixed Bills (Electricity and Water)
                      </label>
                    </div>
                  </h4>
                  <div class="form-group">
                    <label for="monthly_rent">Monthly Rent</label>
                    <input type="number" class="form-control" name="monthly_rent" id="hidden_monthly_rent" placeholder="(default)" value="" readonly >
                  </div>
                  <div class="form-group">
                    <label for="electricity">Electricity</label>
                    <input type="number" name="electricity" class="form-control" id="electricity" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="water">Water</label>
                    <input type="number" name="water" class="form-control" id="water" placeholder="(default)" disabled>
                  </div>
                  <div class="form-group">
                    <label for="penalty">Penalty (%)</label>
                    <div class="d-flex g-3">
                      <select name="penalty" id="penalty" class="form-select form-control col-md-6" onchange="updatePenalty()">
                        <option value="">-- Select --</option>
                        <?php foreach ($penalty_data as $penalties): ?>
                        <option value="<?php echo $penalties['id']; ?>" data-penalty_amount="<?php echo $penalties['amount']; ?>" data-description="<?php echo $penalties['description']; ?>"><?php echo $penalties['percentage'] * 100 . "%";  ?></option>
                        <?php endforeach; ?> 
                      </select>
                      <p type="text" id="penaltyDescription" class="form-control h-auto ml-2 d-none" readonly></p>
                    </div>
                  </div>
                  <div id="fixed-bill-fields" style="display: none;">
                    <div class="form-group">
                      <label for="num_of_invoices">Number of Invoices:</label>
                      <input type="number"  class="form-control col-md-6" id="num_of_invoices" name="num_of_invoices" onchange=" generateMonthlyBills()" placeholder=""  min="0">
                    </div>
                    <div class="form-group">
                      <label for="interval_in_months">Interval in Months</label>
                      <input type="number"  class="form-control col-md-6" id="interval_in_months" name="interval_in_months" placeholder="invoices per month"  min="1">
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-12">
                      <button type="submit" class="btn btn-primary btn-icon-text float-right" name="save">Save</button>
                    </div>
                  </div>
                </div>
              </div>
            </div>  
          </form>
        </div>
      </div>
    </div>
  </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.getElementById('fixed_bills').addEventListener('change', function () {
    var num_of_invoices = document.getElementById('num_of_invoices');
    var interval_in_months = document.getElementById('interval_in_months');

    if (this.checked) {
        num_of_invoices.style.display = 'inline';
        interval_in_months.style.display = 'inline';
    } else {
        num_of_invoices.style.display = 'none';
        interval_in_months.style.display = 'none';
    }
});


function updateFields() {
    const leaseUnitId = document.getElementById("lease_unit_id");
    const selectedOption = leaseUnitId.options[leaseUnitId.selectedIndex];
    const tenantId = selectedOption.getAttribute("data-tenant_id");

    const tenantFirstName = selectedOption.getAttribute("data-tenant_first_name");
    const tenantLastName = selectedOption.getAttribute("data-tenant_last_name");
    const rent = selectedOption.getAttribute("data-rent");
    const deposit = selectedOption.getAttribute("data-deposit");
    const advance = selectedOption.getAttribute("data-advance");

    const leaseUnitSelect = document.getElementById("lease_unit_id");
    const selectedLeaseOption = leaseUnitSelect.options[leaseUnitSelect.selectedIndex];
    const electricity = parseFloat(selectedLeaseOption.getAttribute("data-electricity"));
    const water = parseFloat(selectedLeaseOption.getAttribute("data-water"));

    document.getElementById("tenant_name").value = tenantId;
  
    // Set the tenant's full name for display purposes only (if needed)
    document.getElementById("tenant_display_name").textContent = tenantFirstName + ' ' + tenantLastName;
    document.getElementById("tenant_display_name").value = tenantFirstName + ' ' + tenantLastName;

    document.getElementById("hidden_monthly_rent").value = rent;
    document.getElementById("hidden_one_month_deposit").value = deposit;
    document.getElementById("hidden_one_month_advance").value = advance;

    // Call the handleFixedBills function
    handleFixedBills(electricity, water);

    // Update rent due date input
    const currentInvoiceNumber = parseInt(document.getElementById('current_invoice_number').value);
    const numOfInvoices = parseInt(document.getElementById('num_of_invoices').value);
    const selectedLease = leaseUnitSelect.options[leaseUnitSelect.selectedIndex];
    const rentDueDatesContainer = document.getElementById('rent_due_dates_container');


    if (numOfInvoices && selectedLease.lease_start) {
      const leaseStartDate = new Date(selectedLease.lease_start);
      const nextRentDueDate = new Date(leaseStartDate);

      nextRentDueDate.setMonth(nextRentDueDate.getMonth() + (numOfInvoices * currentInvoiceNumber));

      const formattedRentDueDate = nextRentDueDate.toISOString().split('T')[0];
      rentDueDatesContainer.min = leaseStartDate.toISOString().split('T')[0];
      rentDueDatesContainer.value = formattedRentDueDate;
    } else {
      rentDueDatesContainer.value = '';
    }
    
}

// Generate monthly bill array values
const intervalInMonths = parseInt(document.getElementById('interval_in_months').value);
const monthlyBills = generateMonthlyBills(numOfInvoices, intervalInMonths);

// Reset the current invoice number when a new lease is selected
document.getElementById('current_invoice_number').value = '0';

document.getElementById('fixed_bills').addEventListener('change', function () {
  const leaseUnitSelect = document.getElementById("lease_unit_id");
  const selectedLeaseOption = leaseUnitSelect.options[leaseUnitSelect.selectedIndex];
  const electricity = parseFloat(selectedLeaseOption.getAttribute("data-electricity"));
  const water = parseFloat(selectedLeaseOption.getAttribute("data-water"));
  
  handleFixedBills(electricity, water);
});


function handleFixedBills(electricity, water) {
  const fixedBillsCheckbox = document.getElementById("fixed_bills");
  const fixedBillFields = document.getElementById("fixed-bill-fields");

  if (electricity !== 0 && water !== 0) {
      fixedBillFields.style.display = "block";
      fixedBillsCheckbox.checked = true;

      if (fixedBillsCheckbox.checked) {
          document.getElementById("electricity").value = electricity;
          document.getElementById("water").value = water;
          document.getElementById("electricity").setAttribute("disabled", "disabled");
          document.getElementById("water").setAttribute("disabled", "disabled");
      } else {
          document.getElementById("electricity").value = "";
          document.getElementById("water").value = "";
          document.getElementById("electricity").removeAttribute("disabled");
          document.getElementById("water").removeAttribute("disabled");
      }
  } else {
      fixedBillFields.style.display = "none";
      fixedBillsCheckbox.checked = false;
      document.getElementById("electricity").removeAttribute("disabled");
          document.getElementById("water").removeAttribute("disabled");
  }
  calculateTotal();

}
document.getElementById("hidden_monthly_rent").addEventListener('input', calculateTotal);
document.getElementById("electricity").addEventListener('input', calculateTotal);
document.getElementById("water").addEventListener('input', calculateTotal);
document.getElementById("penalty").addEventListener('input', calculateTotal);


function generateMonthlyBills(numOfInvoices, intervalInMonths) {
  const monthlyBills = [];

  for (let i = 0; i <= numOfInvoices; i++) {
    const billMonth = i * intervalInMonths;
    monthlyBills.push(billMonth);
  }

  return monthlyBills;
}


function updatePenalty() {
    let penaltySelect = document.getElementById("penalty");
    let penaltyDescription = document.getElementById("penaltyDescription");

    let selectedOption = penaltySelect.options[penaltySelect.selectedIndex];
    let description = selectedOption.getAttribute('data-description');

    if (description) {
        penaltyDescription.textContent = description;
        penaltyDescription.classList.remove('d-none');
        penaltyDescription.classList.add('d-block');
    } else {
        penaltyDescription.textContent = '';
        penaltyDescription.classList.remove('d-block');
        penaltyDescription.classList.add('d-none');
    }
}

function calculateTotal() {
    const rent = parseFloat(document.getElementById("hidden_monthly_rent").value) || 0;
    const electricity = parseFloat(document.getElementById("electricity").value) || 0;
    const water = parseFloat(document.getElementById("water").value) || 0;

    const penalty = document.getElementById("penalty");
    const selectedPenalty = penalty.options[penalty.selectedIndex];
    const penaltyAmount = parseFloat(selectedPenalty.getAttribute('data-penalty_amount')) || 0;

    const total = rent + electricity + water + penaltyAmount;
    document.getElementById("total_due").value = total;

    updatePenalty(); // Call the updatePenalty function after calculating the total amount

    // Increment the current invoice number
    const currentInvoiceNumberInput = document.getElementById('current_invoice_number');
    const incrementedInvoiceNumber = parseInt(currentInvoiceNumberInput.value) + 1;
    currentInvoiceNumberInput.value = incrementedInvoiceNumber;
}


function filterPropertyUnits() {
    const propertyNameSelect = document.getElementById("property_name");
    const propertyUnitSelect = document.getElementById("property_unit");
    const selectedProperty = propertyNameSelect.value;

    for (let i = 1; i < propertyUnitSelect.options.length; i++) {
        const option = propertyUnitSelect.options[i];
        if (option.getAttribute("data-property") == selectedProperty) {
            option.hidden = false;
        } else {
            option.hidden = true;
        }
    }
    propertyUnitSelect.value = ""; // Reset selected value
}

function filterLeaseUnits() {
    const propertyUnitSelect = $("#property_unit");
    const leaseUnitSelect = $("#lease_unit_id");
    const selectedPropertyUnit = propertyUnitSelect.val();

    leaseUnitSelect.val(""); // Reset selected value

    leaseUnitSelect.find("option").each(function() {
        const option = $(this);
        if (option.data("property-unit") == selectedPropertyUnit) {
            option.show();
        } else {
            option.hide();
        }
    });
}

$("#property_unit").on("change", function() {
    filterLeaseUnits();
    updateRent();
});


</script>