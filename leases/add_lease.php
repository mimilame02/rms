<?php
  require_once '../includes/dbconfig.php';
  require_once '../tools/functions.php';
  require_once '../classes/leases.class.php';

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
  

    if(isset($_POST['save'])){

      $leases_obj = new Leases();
      //sanitize user inputs
      $leases_obj->property_unit_id = $_POST['property_unit'];
      $leases_obj->tenant_id = $_POST['tenant_name'];
      $leases_obj->monthly_rent = $_POST['monthly_rent'];
      $leases_obj->one_month_deposit = $_POST['one_month_deposit'];
      $leases_obj->one_month_advance = $_POST['one_month_advance'];
      $leases_obj->lease_start = $_POST['lease_start'];      
      $leases_obj->lease_end = $_POST['lease_end'];     
      $leases_obj->electricity = $_POST['electricity'];
      $leases_obj->water = $_POST['water'];
      $leases_obj->status = 'Occupied';

      $lease_docs = [];
      if (isset($_FILES['lease_doc'])) {
          $image_count = count($_FILES['lease_doc']['name']);

          for ($i = 0; $i < $image_count; $i++) {
              $image = $_FILES['lease_doc']['name'][$i];
              $target = "../img/leases/" . basename($image);

              if (move_uploaded_file($_FILES['lease_doc']['tmp_name'][$i], $target)) {
                  $lease_docs[$i] = $_FILES['lease_doc']['name'][$i];
              } else {
                  // handle file upload error
                  $msg = "Error uploading file";
              }
          }
      }
      if (!empty($lease_docs)) {
          $property_obj->lease_doc = json_encode($lease_docs);
      }


      // Add property to database
        if ($leases_obj->lease_add()) {
          $_SESSION['added_lease'] = true;
          // Redirect to the leases page after adding a new lease
          header('location: leases.php?add_success=1');
          exit; // always exit after redirecting
        } else {
          // handle property add error
          $msg = "Error uploading lease";
        }
      }
  

    
    require_once '../tools/variables.php';
    $page_title = 'RMS | Add Lease';
    $leases = 'active';
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
        <h3 class="font-weight-bolder">ADD LEASE</h3> 
      </div>
      <div class="add-page-container">
        <div class="col-md-2 d-flex justify-align-between float-right">
          <a href="leases.php" class='bx bx-caret-left'>Back</a>
        </div>
      </div>
    </div>
    <form action="add_lease.php" method="post">
      <div class="row">
        <div class="col-lg-12 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <div class="row">
                <h4 class="card-title">Create Lease
                <p class="text-muted text-break fs-8 mt-1 mb-0 pl-0 col-md-12">Note: Only the vacant and occupied units from rental property can be added to a lease</p></h4>
              </div>
              <div class="row g-3">
                <div class="col-md-12">
                  <div class="d-inline-flex w-100">
                    <div class="form-group pr-4 w-100">
                      <label for="property_name">Building Name</label>
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
                    <div class="form-group pr-4 w-75">
                      <label for="property_unit">Property Unit No.</label>
                      <select class="form-control form-control-sm mb-3 req" id="property_unit" name="property_unit" onchange="updateRent()" disabled>
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

                              $property_id = $row['property_id'];
                              $rent = $row['monthly_rent'];
                              $deposit = $row['one_month_deposit'];
                              $advance = $row['one_month_advance'];
                          }
                        ?>
                      </select>
                    </div>
                    <div class="form-group w-100">
                      <label for="tenant_name">Tenant Name</label>
                      <select name="tenant_name" id="tenant_name" class="form-control form-control-sm" disabled>
                        <option value="">-- Select --</option>
                        <?php
                          // Connect to the database and retrieve the list of property_units
                          $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM tenant ");
                          while ($row = mysqli_fetch_assoc($result)) {
                            echo "<option value='" . $row['id'] . "'>" . $row['last_name'] . "," .$row['first_name']."</option>";
                          }
                        ?>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="monthly_rent">Monthly Rent</label>
                    <input type="number" min="0" step="0.01" class="form-control form-control-sm" id="monthly_rent" placeholder="Monthly Rent (default)" name="monthly_rent" value = "<?php echo "$rent"?>" disabled>
                  </div>
                  <div class="form-group pt-2">
                    <div class="">
                      <label for="one_month_deposit">One Month Deposit Amount</label>
                      <input type="number" min="0" step="0.01" class="form-control form-control-sm" id="one_month_deposit" placeholder="One Month Deposit (default)" name="one_month_deposit" value="<?php echo "$deposit" ?>"disabled>
                    </div>
                  </div>
                  <div class="form-group pt-2">
                    <div class="">
                      <label for="one_month_advance">One Month Advance Amount</label>
                      <input type="number" min="0" step="0.01" class="form-control form-control-sm" id="one_month_advance" placeholder="One Month Advance (default)" name="one_month_advance" value="<?php echo "$advance" ?>"disabled>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="lease_start">Start Date</label>
                    <div class="input-group">
                      <input min="<?php echo date('Y-m-d', strtotime('-1 month')); ?>" type="date" class="form-control" id="lease_start" name="lease_start" placeholder="Start Date" aria-label="lease_start" onchange="updateEndDate()">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="lease_end">End Date</label>
                    <div class="input-group">
                      <input min="<?php echo date('Y-m-d', strtotime('-1 month')); ?>" type="date" class="form-control" id="lease_end" name="lease_end" placeholder="End Date" aria-label="lease_end">
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary float-right mr-2" name="save">Save Lease</button>
            </div>
          </div> 
        </div>
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Lease Contract</h4>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="lease_doc">Upload Lease Document</label><br>     
                  <div class="image-container" id="uploaded-images"></div>
                  <input type="file" class="form-control form-control-sm" name="lease_doc[]" id="lease_doc" accept=".jpg,.jpeg,.png" multiple>
                  <div class="image-container" id="uploaded-images"></div>
                </div>
              </div>
            </div>
          </div>
        </div>  
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Billing Inclusion<span class="text-muted text-break fs-7 mt-1 mb-0 float-right"> for fixed billing</span></h4>
              <div class="row">
                <div class="form-group col-12 col-sm-6">
                  <label for="electricity" class="d-flex align-items-center">Electricity</label>
                  <input type="number" class="form-control" id="electricity" name="electricity" placeholder="enter amount" disabled>
                </div>
                <div class="form-group col-12 col-sm-6">
                  <label for="water" class="d-flex align-items-center">Water</label>
                  <input type="number" class="form-control" id="water" name="water" placeholder="enter amount" disabled>
                </div> 
              </div>
            </div>
          </div>
        </div>          
      </div>       
    </form>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
  $(document).ready(function() {
    // Enable the Property Unit field when the Building Name field is changed
    $("#property_name").on("change", function() {
      if ($(this).val() !== "none") {
        $("#property_unit").prop("disabled", false);
      } else {
        $("#property_unit").prop("disabled", true);
      }
    });

    // Enable the Tenant Name field when the Property Unit field is changed
    $("#property_unit").on("change", function() {
      if ($(this).val() !== "") {
        $("#tenant_name").prop("disabled", false);
      } else {
        $("#tenant_name").prop("disabled", true);
      }
    });

    // Enable the Electricity and Water fields when the Tenant Name field is changed
    $("#tenant_name").on("change", function() {
      if ($(this).val() !== "") {
        $("#electricity").prop("disabled", false);
        $("#water").prop("disabled", false);
      } else {
        $("#electricity").prop("disabled", true);
        $("#water").prop("disabled", true);
      }
    });
  });
</script>

<script>
$(document).ready(function() {                
  $('#lease_doc').on('change', function() {
    const input = this;
    const imagesContainer = $('#uploaded-images');
    imagesContainer.empty();

    if (input.files && input.files.length <= 6) {
      let currentRow;

      for (let i = 0; i < input.files.length; i++) {
        // Create a new row every 3 images
        if (i % 3 === 0) {
          currentRow = $('<div>')
            .addClass('d-flex flex-wrap')
            .css('margin-inline', '10%');
          imagesContainer.append(currentRow);
        }

        const file = input.files[i];
        const reader = new FileReader();
        reader.onload = function(e) {
          const img = $('<img>')
            .attr('src', e.target.result)
            .attr('alt', 'Lease Document')
            .css({width: '150px', height: '150px'})
            .addClass('mr-2 mb-2');
          currentRow.append(img);
        };
        reader.readAsDataURL(file);
      }
    }
  });
});




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
  
  function updateRent() {
    const property_unitSelect = document.getElementById('property_unit');
    const rentInput = document.getElementById('monthly_rent');
    const depositInput = document.getElementById('one_month_deposit');
    const advanceInput = document.getElementById('one_month_advance');

    const selectedOption = property_unitSelect.options[property_unitSelect.selectedIndex];
    const rent = selectedOption.dataset.rent;
    const deposit = selectedOption.dataset.deposit;
    const advance = selectedOption.dataset.advance;

    rentInput.value = rent || '';
    depositInput.value = deposit || '';
    advanceInput.value = advance || '';

    // Enable the input fields
    rentInput.disabled = false;
    depositInput.disabled = false;
    advanceInput.disabled = false;
  }


  function updateEndDate() {
    const startDateInput = document.getElementById('lease_start');
    const endDateInput = document.getElementById('lease_end');

    const startDate = new Date(startDateInput.value);
    const endDate = new Date(startDate);

    endDate.setMonth(endDate.getMonth() + 3);

    const formattedEndDate = endDate.toISOString().split('T')[0];
    endDateInput.min = startDateInput.value;
    endDateInput.value = formattedEndDate;

  }
  
  document.addEventListener('DOMContentLoaded', updateEndDate);

</script>
