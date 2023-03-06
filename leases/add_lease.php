<?php
  require_once '../tools/functions.php';
  require_once '../classes/database.php';
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
    if(isset($_POST['save'])){
      $leases_obj = new Leases();
      //sanitize user inputs
      $leases_obj->property_unit_name = $_POST['property_unit_name'];
      $leases_obj->monthly_rent = $_POST['monthly_rent'];
      $leases_obj->tenant_name = $_POST['tenant_name'];
      $leases_obj->lease_start = $_POST['lease_start'];
      $leases_obj->lease_end = $_POST['lease_end'];
      $leases_obj->rent_paid = htmlentities($_POST['rent_paid']);
      $leases_obj->one_month_deposit = $_POST['one_month_deposit'];
      $leases_obj->one_month_advance = $_POST['one_month_advance'];
      $leases_obj->property_picture = $_POST['property_picture'];

      if (isset($_FILES['identification_document'])) {
        $image = $_FILES['identification_document']['name'];
        $target = "../img/" . basename($image);

        if (move_uploaded_file($_FILES['identification_document']['tmp_name'], $target)) {
            $leases_obj->identification_document = $_FILES['identification_document']['name'];
        } else {
            // handle file upload error
            $msg = "Error uploading file";
        }
      }
      // handle file upload error
      $msg = "Error uploading file";
      
        // Add product to database
/*         if(validate_add_landlord($_POST)){
 */       if ($leases_obj->lease_add()) {
            header('Location: leases.php');
            exit; // always exit after redirecting
        } else {
            // handle product add error
            $msg = "Error adding lease";
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
    <form class="forms-sample" action="add_lease.php" method="post">
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card h-100">
                <div class="card-body">
                  <h4 class="card-title">Create Lease</h4>
                    <div class="col-md-12">
                      <div class="d-flex">
                        <div class="col-6 pr-4">
                          <div class="form-group">
                            <label for="property_unit_name">Property Unit Name</label><span class="req"> *</span>
                            <select name="property_unit_name" id="property_unit_name" class="form-select w-100">
                              <option value="">-- Select --</option>
                              <!-- Populate this select with the list of property units -->
                            </select>
                          </div> 
                          <div class="form-group">
                            <label for="monthly_rent">Monthly Rent</label>
                            <input type="number" class="form-control w-100" id="monthly_rent" placeholder="Monthly Rent (default)" disabled>
                          </div>
                          <div class="form-group">
                            <label for="tenant_name">Tenant Name</label><span class="req"> *</span>
                            <select name="tenant_name" id="tenant_name" class="form-select w-100">
                              <option value="">-- Select --</option>
                              <!-- Populate this select with the list of property units -->
                            </select>
                          </div>
                        </div>
                        <div class="col-5">
                          <div class="form-group">
                            <label for="lease_start">Lease Start</label><span class="req"> *</span>
                            <div class="input-group">
                              <input type="date" class="form-control w-100" placeholder="Start Date" aria-label="lease_start">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="lease_end">Lease End</label><span class="req"> *</span>
                            <div class="input-group">
                              <input type="date" class="form-control w-100" placeholder="End Date" aria-label="lease_end">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="rent_paid">Rent Paid</label><span class="req"> *</span>
                            <input type="number" class="form-control w-100" id="rent_paid" placeholder="enter amount">
                          </div>
                          <div class="form-group">
                            <label for="one_month_deposit">One Month Deposit</label><span class="req"> *</span>
                            <input type="number" class="form-control w-100" id="one_month_deposit" placeholder="enter amount">
                          </div>
                          <div class="form-group">
                            <label for="one_month_advance">One Month Advance</label><span class="req"> *</span>
                            <input type="number" class="form-control w-100" id="one_month_advance" placeholder="enter amount">
                          </div>
                        </div>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary float-right mr-2">Save Lease</button>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card smaller">
                <div class="card-body">
                  <h4 class="card-title">Lease Contract</h4>
                  <div class="row">
                    <div class="form-group col-md-12">
                      <label for="property_picture">Upload Lease Document</label>
                      <input class="form-control form-control-lg" type="file" id="property_picture" name="property_picture">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
    </form>
  </body>