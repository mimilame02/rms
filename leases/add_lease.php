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
      if (isset($_FILES['property_picture']) && $_FILES['property_picture']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['property_picture']['name'];
        $target = "../img/" . basename($image);
    
        if (move_uploaded_file($_FILES['property_picture']['tmp_name'], $target)) {
            $landlord_obj->property_picture = $_FILES['property_picture']['name'];
        } else {
            // handle file upload error
            $msg = "Error uploading file";
        }
      }
      // Add property to database
        if ($leases_obj->lease_add()) {
          header('Location: leases.php');
          exit; // always exit after redirecting
        } else {
          // handle property add error
          $msg = "Error uploading file";
        }
      }
  

    
    require_once '../tools/variables.php';
    $page_title = 'RMS | Add Lease';
    $properties = 'active';
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
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Lease Contract</h4>
              <div class="row">
                <div class="form-group col-md-12">
                  <label for="property_picture">Upload Lease Document</label><br>
                  <div class="image-container float-right" style="display: none;">
                    <img id="uploaded-image" src="default-image.jpg" alt="Default Image" height="100px" width="100px">
                  </div>
                  <input type="file" class="form-control form-control-sm" name="property_picture" id="property_picture" accept=".jpg,.jpeg,.png">
                </div>
              </div>
            </div>
          </div>
        </div>  
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Billing Inclusion</h4>
              <div class="row">
                <div class="form-group col-12 col-sm-6">
                  <label for="electricity" class="d-flex align-items-center">Electricity</label>
                  <input type="number" class="form-control" id="electricity" placeholder="enter amount">
                </div>
                <div class="form-group col-12 col-sm-6">
                  <label for="water" class="d-flex align-items-center">Water</label>
                  <input type="number" class="form-control" id="water" placeholder="enter amount">
                </div> 
              </div>
            </div>
          </div>
        </div>        
        <div class="col-md-6 grid-margin stretch-card">
          <div class="card">
            <div class="card-body">
              <h4 class="card-title">Create Lease</h4>
              <div class="form-group">
              <label for="landlord">Property Unit No.</label>
                    <select class="form-control form-control-sm mb-3 req" id="landlord" name="landlord" onchange="updateRent()">
                        <option class="col-md-6" value="" disabled selected>Select Unit No.</option>
                          <?php
                              // Connect to the database and retrieve the list of properties
                              $result = mysqli_query($conn, "SELECT pu.*, p.property_name
                              FROM property_units pu 
                              RIGHT JOIN properties p ON pu.property_id = p.id
                              WHERE status = 'Vacant'
                               ");
                                while ($row = mysqli_fetch_assoc($result)) {
                                  echo "<option value='" . $row['id'] . "' data-rent='" . $row['monthly_rent'] . "'>" . $row['unit_no'].",".$row['property_name']."</option>";
                                }
                                $rent = $row['monthly_rent'];
                          ?>
                    </select>
              </div>
              <div class="form-group">
                <label for="monthly_rent">Monthly Rent</label>
                <input type="number" class="form-control" id="monthly_rent" placeholder="Monthly Rent (default)" value = "<?php echo "$rent"?>"disabled>
              </div>
              <div class="form-group">
                <label for="tenant_name">Tenant Name</label><span class="req"> *</span>
                <select name="tenant_name" id="tenant_name" class="form-select">
                  <option value="">-- Select --</option>
                  <?php
                                  // Connect to the database and retrieve the list of landlords
                                  $result = mysqli_query($conn, "SELECT id, last_name, first_name FROM tenant ");
                                  while ($row = mysqli_fetch_assoc($result)) {
                                    echo "<option value='" . $row['id'] . "'>" . $row['last_name'] . "," .$row['first_name']."</option>";
                                  }
                                ?>
                </select>
              </div>
              <div class="form-group">
                <label for="start_date">Start Date</label><span class="req"> *</span>
                <div class="input-group">
                  <input type="date" class="form-control" id="start_date" placeholder="Start Date" aria-label="start_date" onchange="updateEndDate()">
                </div>
              </div>
              <div class="form-group">
                <label for="end_date">End Date</label><span class="req"> *</span>
                <div class="input-group">
                  <input type="date" class="form-control" id="end_date" placeholder="End Date" aria-label="end_date">
                </div>
              </div>
              <button type="submit" class="btn btn-primary float-right mr-2" name="save">Save Lease</button>
            </div>
          </div> 
        </div>  
      </div>       
    </form>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
      $(document).ready(function() {
        $('#property_picture').on('change', function() {
          const input = this;
          
          if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
              $('#uploaded-image').attr('src', e.target.result);
              $('.image-container').show();
              $('#property_picture').addClass('col-md-8');
              $('.image-container').css('display', 'block');
            };

            reader.readAsDataURL(input.files[0]);
          } else {
            $('#uploaded-image').attr('src', 'default-image.jpg');
            $('.image-container').hide();
            $('#property_picture').removeClass('col-md-8');
            $('.image-container').css('display', 'none');
          }
        });
      });

      function updateRent() {
        const landlordSelect = document.getElementById('landlord');
        const rentInput = document.getElementById('monthly_rent');
        const rent = landlordSelect.options[landlordSelect.selectedIndex].dataset.rent;

        rentInput.value = rent || '';
      }

      function updateEndDate() {
        const startDateInput = document.getElementById('start_date');
        const endDateInput = document.getElementById('end_date');

        const startDate = new Date(startDateInput.value);
        const endDate = new Date(startDate);

        endDate.setMonth(endDate.getMonth() + 3);

        const formattedEndDate = endDate.toISOString().split('T')[0];
        endDateInput.min = startDateInput.value;
        endDateInput.value = formattedEndDate;

      }
      
      document.addEventListener('DOMContentLoaded', updateEndDate);

    </script>
