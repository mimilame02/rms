<?php
  require_once '../tools/functions.php';
  require_once '../classes/database.php';
  require_once '../classes/leases.class.php';
  require_once '../classes/invoices.class.php';
  require_once '../classes/penalty.class.php';
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
    $leases = new Leases();
    // Fetch all leases
    $all_leases = $leases->fetch_all_leases();

    $penalty = new Penalty();
    // Fetch all penalty
    $penalty = $penalty->show();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $invoice_obj = new Invoice();
    
        // Set the invoice data from the submitted form
        $invoice_obj->lease_unit_id = $_POST['lease_unit_id'];
        $invoice_obj->tenant_id = $_POST['tenant_name'];
        $invoice_obj->monthly_rent = $_POST['monthly_rent'];
        $invoice_obj->rent_due_date = $_POST['rent_due_date'];
        $invoice_obj->electricity = $_POST['electricity'];
        $invoice_obj->water = $_POST['water'];
        $invoice_objs->penalty_id = $_POST['penalty_name'];
        $invoice_obj->rent_paid = 0; // Set to 0 initially, update when the tenant pays rent
/*         $invoice_obj->balance = $invoice_obj->monthly_rent + $invoice_obj->electricity + $invoice_obj->water + $invoice_obj->penalty_id - $invoice_obj->rent_paid; */
    
        // Save the invoice
        if ($invoice_obj->invoice_add()) {
            // Redirect to a success page or display a success message
            header('Location: invoices.php');
        } else {
            // Redirect to an error page or display an error message
            $msg = "Error uploading file";
        }
    }
    
    require_once '../tools/variables.php';
    $page_title = 'RMS | Generate Invoice';
    $leases = 'active';

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
        <form action="generate_invoice.php" method="post" onsubmit="submitForm(event)">
          <div class="row g-3">
            <div class="col-md-6 grid-margin">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Invoice Details</h4>
                  <div class="">
                    <div class="col-md-12 pl-0">
                      <div class="form-group">
                        <label for="lease_unit_id">Leased Unit</label><span class="req"> *</span>
                        <select name="lease_unit_id" id="lease_unit_id" class="form-select form-control">
                          <option value="">-- Select --</option>
                          <?php
                            if (isset($all_leases) && !empty($all_leases)) {
                                foreach ($all_leases as $lease) {
                                    echo "<option value='{$lease['id']}'>Unit #{$lease['property_unit_id']} - {$lease['tenant_id']}</option>";
                                }
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 pl-0">
                      <div class="form-group">
                        <label for="tenant_name">Tenant Name</label>
                        <input type="text" name="tenant_name" class="form-control" id="tenant_name" placeholder="(default)" disabled>
                      </div>
                    </div>
                    <div class="col-md-12 pl-0">
                      <div class="form-group">
                        <label for="rent_due_date">Rent Due Date</label>
                        <input type="date" name="rent_due_date" class="form-control" id="rent_due_date" placeholder="(default)" disabled>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Billing</h4>
                    <div class="form-group">
                      <label for="monthly_rent">Monthly Rent</label>
                      <input type="number" name="monthly_rent" class="form-control" id="monthly_rent" placeholder="(default)"disabled>
                    </div>
                    <div class="form-group">
                      <label for="electricity">Electricty</label>
                      <input type="number" name="electricity" class="form-control" id="electricity" placeholder="(default)"disabled>
                    </div>
                    <div class="form-group">
                      <label for="water">Water</label>
                      <input type="number" name="water" class="form-control" id="water" placeholder="(default)"disabled>
                    </div>
                    <div class="form-group">
                    <label for="penalty">Penalty</label>
                      <select name="penalty" id="penalty" class="form-select form-control">
                      <option value="">-- Select --</option>
                      <?php foreach ($penalty as $penalty): ?>
                        <option value="<?php echo $penalty['amount']; ?>"><?php echo $penalty['name']; ?></option>
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <button type="submit" class="btn btn-primary float-right mr-2">Save</button>
              </div>
            </div>
          </div>
        </form>
      </div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Event listener for form submission
    $('form').on('submit', function(e) {
      // Prevent default form submission behavior
      e.preventDefault();

      // Send AJAX request to save_invoice.php with serialized form data
      $.ajax({
        url: 'save_invoice.php',
        type: 'POST',
        data: $(this).serialize() + '&action=save_invoice',
        dataType: 'json',
        success: function(response) {
          // Check the status field in the JSON response
          if (response.status === 'success') {
            // Handle successful invoice creation, e.g., display a success message, clear the form, or redirect
            console.log($(this).serialize() + '&action=save_invoice');
            console.log(response);
            $('form').html(response);
          }
        },
        // Handle invoice creation errors, e.g., display an error message
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
          alert("Status: " + textStatus); alert("Error: " + errorThrown);
        }
      });
    });

    // Event listener for lease_unit_id change event
    $('#lease_unit_id').on('change', function() {
      let lease_unit_id = $(this).val();
      
      if (lease_unit_id) {
        // Send AJAX request to save_invoice.php to fetch lease data
        $.ajax({
          url: 'save_invoice.php',
          type: 'POST',
          data: {action: 'fetch_lease_data', lease_unit_id: lease_unit_id},
          dataType: 'json',
          success: function(data) {
            // Update form fields with fetched data
            $('#tenant_id').val(data.tenant_id);
            $('#tenant_name').val(data.tenant_name);
            $('#rent_due_date').val(data.rent_due_date);
            $('#monthly_rent').val(data.monthly_rent);
            $('#electricity').val(data.electricity);
            $('#water').val(data.water);
          }
        });
      } else {
        // Clear form fields if no lease_unit_id is selected
        $('#tenant_id').val('');
        $('#tenant_name').val('');
        $('#rent_due_date').val('');
        $('#monthly_rent').val('');
        $('#electricity').val('');
        $('#water').val('');
      }
    });

    // Event listener for penalty select element change
    $('#penalty').on('change', function() {
      // Get the selected penalty amount
      const penalty_amount = $(this).val();
      
      // Check if a penalty is selected
      if (penalty_amount) {
        // Update the penalty input field value
        $('#penalty').val(penalty_amount);
      } else {
        // Clear the penalty input field value
        $('#penalty').val('0');
      }
    });
  });
</script>
</body>


