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
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
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

// Set a default value for lease_doc
$leases_obj->lease_doc = $_POST['lease_doc'];

if (isset($_FILES['lease_doc']) && $_FILES['lease_doc']['error'] === UPLOAD_ERR_OK) {
    $image = $_FILES['lease_doc']['name'];
    $target = "../img/leases/" . basename($image);
    
    // Check if the directory exists and create it if necessary
    if (!is_dir("../img/leases/")) {
        mkdir("../img/leases/", 0755);
    }

    // Check if the directory is writable
    if (is_writable("../img/leases/")) {
        if (move_uploaded_file($_FILES['lease_doc']['tmp_name'], $target)) {
            $leases_obj->lease_doc = $_FILES['lease_doc']['name'];
            $msg = "File uploaded successfully.";
        } else {
            $msg = "Error moving the uploaded file to the target directory.";
        }
    } else {
        $msg = "The target directory is not writable.";
    }
  } else {
      if (!isset($_FILES['lease_doc'])) {
        $msg = "lease_doc key not set in the _FILES array.";
      } else {
          // Get the error message based on the error code
          $upload_error_codes = [
              UPLOAD_ERR_INI_SIZE => "The uploaded file exceeds the upload_max_filesize directive in php.ini.",
              UPLOAD_ERR_FORM_SIZE => "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.",
              UPLOAD_ERR_PARTIAL => "The uploaded file was only partially uploaded.",
              UPLOAD_ERR_NO_FILE => "No file was uploaded.",
              UPLOAD_ERR_NO_TMP_DIR => "Missing a temporary folder.",
              UPLOAD_ERR_CANT_WRITE => "Failed to write the file to disk.",
              UPLOAD_ERR_EXTENSION => "A PHP extension stopped the file upload.",
          ];
          $msg = $upload_error_codes[$_FILES['lease_doc']['error']] ?? "Unknown error.";
      }
  }

  echo $msg; // Show the message for debugging


      // Add property to database
        if ($leases_obj->lease_add()) {
          header('Location: leases.php');
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
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="property_name">Building Name</label><span class="req"> *</span>
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
                  <div class="form-group">
                    <label for="property_unit">Property Unit No.</label><span class="req"> *</span>
                    <select class="form-control form-control-sm mb-3 req" id="property_unit" name="property_unit" onchange="updateRent()">
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
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="tenant_name">Tenant Name</label><span class="req"> *</span>
                    <select name="tenant_name" id="tenant_name" class="form-select form-control-sm">
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
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="monthly_rent">Monthly Rent</label>
                    <input type="number" class="form-control form-control-sm" id="monthly_rent" placeholder="Monthly Rent (default)" name="monthly_rent" value = "<?php echo "$rent"?>" disabled>
                  </div>
                  <div class="form-group pt-2">
                    <div class="">
                      <label for="one_month_deposit">One Month Deposit Amount</label><span class="req"> *</span>
                      <input type="number" class="form-control form-control-sm" id="one_month_deposit" placeholder="One Month Deposit (default)" name="one_month_deposit" value="<?php echo "$deposit" ?>"disabled>
                    </div>
                  </div>
                  <div class="form-group pt-2">
                    <div class="">
                      <label for="one_month_advance">One Month Advance Amount</label><span class="req"> *</span>
                      <input type="number" class="form-control form-control-sm" id="one_month_advance" placeholder="One Month Advance (default)" name="one_month_advance" value="<?php echo "$advance" ?>"disabled>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="lease_start">Start Date</label><span class="req"> *</span>
                    <div class="input-group">
                      <input type="date" class="form-control" id="lease_start" name="lease_start" placeholder="Start Date" aria-label="lease_start" onchange="updateEndDate()">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="lease_end">End Date</label><span class="req"> *</span>
                    <div class="input-group">
                      <input type="date" class="form-control" id="lease_end" name="lease_end" placeholder="End Date" aria-label="lease_end">
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
                  <div class="image-container float-right" style="display: none;">
                    <img id="uploaded-image" src="default-image.jpg" alt="Default Image" height="150px" width="400px">
                    <?php if (!empty($leases_obj->lease_doc)) { ?>
                    <p class="mt-2 file-name">File name: <?php echo basename($leases_obj->lease_doc); ?></p>
                  <?php } else { ?>
                    <p class="mt-2 ml-2 file-name text-break">No file selected yet</p>
                  <?php } ?>
                  </div>
                  <input type="file" class="form-control form-control-sm" name="lease_doc" id="lease_doc" accept=".jpg,.jpeg,.png">
                </div>
              </div>
            </div>
          </div>
        </div>  
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Billing Inclusion<span class="text-muted">*</span><span class="text-muted text-break fs-7 mt-1 mb-0 float-right"> for fixed billing</span></h4>
              <div class="row">
                <div class="form-group col-12 col-sm-6">
                  <label for="electricity" class="d-flex align-items-center">Electricity</label>
                  <input type="number" class="form-control" id="electricity" name="electricity" placeholder="enter amount">
                </div>
                <div class="form-group col-12 col-sm-6">
                  <label for="water" class="d-flex align-items-center">Water</label>
                  <input type="number" class="form-control" id="water" name="water" placeholder="enter amount">
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
        $('#lease_doc').on('change', function() {
          const input = this;
          
          if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
              $('#uploaded-image').attr('src', e.target.result);
              $('.image-container').show();
              $('#lease_doc').addClass('col-md-12');
              $('.image-container').css('display', 'block');

              // Set the file name in the <p> tag
              const fileName = input.files[0].name;
              $('.file-name').text('File name: ' + fileName);
              $('.file-name').addClass('col-md-12');
            };

            reader.readAsDataURL(input.files[0]);
          } else {
            $('#uploaded-image').attr('src', 'default-image.jpg');
            $('.image-container').hide();
            $('#lease_doc').removeClass('col-md-12');
            $('.image-container').css('display', 'none');

            // Clear the file name in the <p> tag
            $('.file-name').empty();
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
