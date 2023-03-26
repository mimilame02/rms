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
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
        header('location: ../login/login.php');
    }

    $db = new Database();
    $leases = new Leases($db);
    $penalty = new Penalty($db);
    // Fetch all leases
    $all_leases = $leases->fetch_all_leases();
    // Fetch all penalty
    $penalty_data = $penalty->show();


    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
      $invoice = new Invoice();

      if (isset($_POST['action']) && $_POST['action'] == 'fetch_lease_data' && isset($_POST['lease_unit_id'])) {
        $lease_unit_id = $_POST['lease_unit_id'];

        // Fetch lease unit data based on lease_unit_id using the existing lease_fetch function
        $lease_data = $leases->fetch_all_leases($lease_unit_id);

        // Calculate rent_due_date based on lease_end (3 months from lease_start)
        $lease_end = new DateTime($lease_data['lease_end']);
        $rent_due_date = $lease_end->sub(new DateInterval('P3M'))->format('m-d-Y');

        // Add rent_due_date to lease_data
        $lease_data['rent_due_date'] = $rent_due_date;

        // Return the data as JSON
        echo json_encode($lease_data);

      } elseif (isset($_POST['action']) && $_POST['action'] == 'fetch_penalty_amount' && isset($_POST['penalty'])) {
        $penalty_id = $_POST['penalty'];

        // Fetch penalty data based on penalty_id
        $penalty_data = $penalty->fetch_penalty($penalty_id);

        // Return the penalty_amount as JSON
        echo json_encode(['penalty_amount' => $penalty_data['amount']]);

      } elseif (isset($_POST['action']) && $_POST['action'] == 'generate_invoice') {
        // Set the invoice data from the submitted form
        $invoice->lease_unit_id = $_POST['lease_unit_id'];
        $invoice->tenant_id = $_POST['tenant_id'];
        $invoice->monthly_rent = $_POST['monthly_rent'];
        $invoice->rent_due_date = $_POST['rent_due_date'];
        $invoice->electricity = $_POST['electricity'];
        $invoice->water = $_POST['water'];
        $invoice->penalty_id = $_POST['penalty'];
        $invoice->rent_paid = 0; // Set to 0 initially, update when the tenant pays rent

        // Save the invoice
        if ($invoice->invoice_add()) {
          // Return success status as JSON
          echo json_encode(['status' => 'success']);
        } else {
          // Return error status as JSON
          echo json_encode(['status' => 'error']);
        }
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
                                    echo "<option value='{$lease['id']}'>Unit #{$lease['property_unit_id']}";
                                }
                            }
                          ?>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-12 pl-0">
                      <div class="form-group">
                        <label for="tenant_name">Tenant Name</label>
                        <input type="text" name="tenant_name" class="form-control" id="tenant_name" placeholder="(default)"
                        <?php
                            // Get lease units based on selected property unit
                            if(isset($_POST['property_unit'])) {
                              $property_unit_id = $_POST['property_unit'];
                              $lease_units = $ref_obj->get_lease_units_by_id($property_unit_id);
                              }
                              
                          $row = mysqli_fetch_assoc($result);
                        
                          if ($row) {
                            $tenant_name = $row['last_name'] . ", " . $row['first_name'];
                            echo "value='$tenant_name'";
                          }}
                        ?>
                        disabled>
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
                      <?php foreach ($penalty_data as $penalties): ?>
                        <option value="<?php echo $penalties['id']; ?>"><?php echo "&#8369;&nbsp;".$penalties['amount']."&nbsp;&nbsp;&nbsp;&gt;&nbsp;".$penalties['name']; ?></option>  
                      <?php endforeach; ?>
                    </select>
                  </div>
                  <!-- <div class="col-md-6">
                    <div class="form-group">
                      <label for="status">Status</label>
                      <select name="status" id="status" class="form-select form-control col-md-6">
                        <option class="btn btn-secondary btn-lg p-2" value="pending">Pending</option>
                        <option class="btn btn-primary btn-lg p-2" value="partial">Partial</option>
                        <option class="btn btn-success btn-lg p-2" value="paid">Paid</option>
                        <option class="btn btn-danger btn-lg p-2" value="overdue">Overdue</option>
                      </select>
                    </div>
                  </div> -->
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

      // Send AJAX request to generate_invoice.php with serialized form data
      $.ajax({
        url: 'generate_invoice.php',
        type: 'POST',
        data: $(this).serialize() + '&action=generate_invoice',
        dataType: 'json',
        success: function(response) {
          // Check the status field in the JSON response
          if (response.status === 'success') {
            // Handle successful invoice creation, e.g., display a success message, clear the form, or redirect
            console.log($(this).serialize() + '&action=generate_invoice');
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
    // Event listener for lease_unit_id select element change
    $('#lease_unit_id').on('change', function() {
      const lease_unit_id = $(this).val();

      if (lease_unit_id) {
        // Send AJAX request to fetch lease data
        $.ajax({
          url: 'generate_invoice.php',
          type: 'POST',
          data: { action: 'fetch_lease_data', lease_unit_id: lease_unit_id },
          dataType: 'json',
          success: function(response) {
            // Update input fields with fetched data
            $('#tenant_name').val(response.tenant_name);
            $('#rent_due_date').val(response.rent_due_date);
            $('#monthly_rent').val(response.monthly_rent);
            $('#electricity').val(response.electricity);
            $('#water').val(response.water);
          },
          error: function(xhr, textStatus, errorThrown) {
            console.log('Error: ' + textStatus + ', ' + errorThrown);
          }
        });
      } else {
        // Clear input fields if no lease unit is selected
        $('#tenant_name').val('');
        $('#rent_due_date').val('');
        $('#monthly_rent').val('');
        $('#electricity').val('');
        $('#water').val('');
      }
    });


  });
</script>
</body>


