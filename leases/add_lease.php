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

    require_once '../tools/variables.php';
    $page_title = 'RMS | Add Lease';
    $landlord = 'active';

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
          <div class="row">
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Create Lease</h4>
                  <form class="forms-sample">
                    <div class="form-group">
                      <label for="property_unit_name">Property Unit Name</label>
                      <select name="property_unit_name" id="property_unit_name" class="form-select">
                        <option value="">-- Select --</option>
                          <!-- Populate this select with the list of property units -->
                      </select>
                    </div>
                    <div class="form-group">
                    <label for="tenant_name">Tenant Name</label>
                      <select name="tenant_name" id="tenant_name" class="form-select">
                        <option value="">-- Select --</option>
                          <!-- Populate this select with the list of property units -->
                      </select>
                    </div>
                    <div class="form-group">
                    <label for="start_date">Start Date</label>
                    <div class="input-group">
                      <input type="date" class="form-control" placeholder="Start Date" aria-label="start_date">
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="end_date">End Date</label>
                    <div class="input-group">
                      <input type="date" class="form-control" placeholder="End Date" aria-label="end_date">
                    </div>
                  </div>
                    <div class="form-group">
                      <label for="monthly_rent">Monthly Rent</label>
                      <input type="number" class="form-control" id="monthly_rent" placeholder="Monthly Rent (default)">
                    </div>
                    <div class="form-group">
                      <label for="one_month_deposit">One Month Deposit</label>
                      <input type="number" class="form-control" id="one_month_deposit" placeholder="">
                    </div>
                    <div class="form-group">
                      <label for="one_month_advance">One Month Advance</label>
                      <input type="number" class="form-control" id="one_month_advance" placeholder="">
                    </div>
                    <button type="submit" class="btn btn-primary float-right mr-2">Save Lease</button>
                </div>
              </div>
            </div>
            <div class="col-md-6 grid-margin stretch-card">
              <div class="card smaller">
                <div class="card-body">
                  <h4 class="card-title">Bills</h4>
                  <p class="card-description">
                   Include/Exclude Bill when generating invoice                    
                  </p>
                  <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <div class="form-check">
                            <label class="form-check-lbl">
                              <input type="checkbox" class="form-check-input-ic">
                            Electricity
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>
                    <div class="form-row">
                  <div class="form-group col-md-6">
                  <label for="electricity-start-date">Start date</label>
                  <input type="date" class="form-control" id="electricity-start-date" disabled>
                    </div>
                  <div class="form-group col-md-6">
                  <label for="electricity-start-amount">Consumption</label>
                  <input type="number" class="form-control" id="electricity-start-amount" placeholder="Enter amount" disabled>
                  </div>
                  </div>
                  <div class="form-row">
                <div class="form-group col-md-6">
                <label for="electricity-end-date">End date</label>
                  <input type="date" class="form-control" id="electricity-end-date" disabled>
                  </div>
                  <div class="form-group col-md-6">
                    <label for="electricity-end-amount">Consumption</label>
                    <input type="number" class="form-control" id="electricity-end-amount" placeholder="Enter amount" disabled>
                    </div>
                    </div>
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <div class="form-check">
                            <label class="form-check-lbl">
                              <input type="checkbox" class="form-check-input-ic">
                            Water
                            </label>
                            <div class="form-group">
                              <label for="water-amount">Amount</label>
                          <input type="number" class="form-control" id="water-amount" placeholder="Enter amount" disabled>
                              </div>

                              <div>
                              <label for="property_picture">Upload Lease Document</label>
                              <input class="form-control form-control-lg" type="file" id="property_picture" name="property_picture">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
  </div>
  </div>

</body>