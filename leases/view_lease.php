<?php
require_once '../includes/dbconfig.php';
require_once '../classes/leases.class.php';
require_once '../classes/account.class.php';


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
    $lease_id = $_GET['id'];

    $sql = "SELECT l.*, CONCAT(tenant.first_name, ' ', tenant.last_name) AS tenant_name, p.property_name, pu.unit_no 
    FROM lease l 
    JOIN tenant ON l.tenant_id = tenant.id 
    JOIN property_units pu ON l.property_unit_id = pu.id 
    JOIN properties p ON pu.property_id = p.id 
    WHERE l.id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $lease_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    

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
            <h3 class="font-weight-bolder">VIEW LEASE</h3> 
          </div>
        </div>
        <div class="row">
        <div class="add-page-container">
              <div class="col-md-2 d-flex justify-align-between float-right ">
                <a href="leases.php" class='bx bx-caret-left'>Back</a>
              </div>
            </div>

            <div class="row">
              <div class="col-xl-4">
          
                 <div class="card mb-4 mb-xl-0">
                  <div class="card-header">Lease Contract</div>
                <div class="card-body">
                <div class="mb-3">
                            <label class="large mb-1">Contract</label>
                            <img class="form-control" src="" alt="Contract File" class="img-thumbnail" width="200">
                            </div>
                            </div>
                            </div>
                            </div>
            <div class="col-xl-8">
            <div class="card mb-4">
                <div class="card-header">Unit Details</div>
                <div class="card-body">
                     
                        <div class="mb-3">
                            <label class="small mb-1">Tenant Name</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['tenant_name'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Building Name</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['property_name'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Unit No.</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['unit_no'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Monthly Rent</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['monthly_rent'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">One Month Deposit Amount Paid</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['one_month_deposit'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">One Month Advance Amount Paid</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['one_month_advance'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">Start Date</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['lease_start'] ?>"readonly>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1">End Date</label>
                            <input class="form-control" id="" type="text"  value="<?= $row['lease_end'] ?>"readonly>
                        </div>
                      