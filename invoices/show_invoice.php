<?php

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

    require_once '../tools/variables.php';
    $page_title = 'RMS | Invoices';
    $tenant = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
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
        <h3 class="font-weight-bolder">INVOICES</h3> 
      </div>

      <div class="col-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">INVOICE</h4>
                  <div class="row mb-3">
                  <div class="col-md-6">
                <h5>Invoice to:</h5>
                <address>
                  Name: Monica Geller<br>
                  Email:monica@gmail.com<br>
                  Contact No:09296837000<br>
                </address>
              </div>
              <div class="col-md-6 text-end">
                <h5>Billing Details:</h5>
                <address>
                  Unit Name: Pad 1<br>
                  Property Name: Property 1<br>
                  Invoice Due: 2022-01-01<br>
                  Status:<button class="show1">PAID</button>
                </address>
              </div>
            </div>

            <div class="row mt-4">
              <div class="card">
                <div class="card-body">
                  <div class="table-responsive pt-3 mb-5">
                  <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
        <thead>
            <tr>
                     <th>#</th>
                     <th>Description</th>
                     <th>Amount</th>
                     <th>Total</th>
                   
            </tr>
        </thead>
        <tbody>
        <tr>
        <td>1</td>
        <td>Rent</td>
        <td>5000.00</td>
        <td>₱ 5000.00</td>
          </tr>
        <td>2</td>
        <td>Electricity</td>
        <td>34.5</td>
        <td>₱ 492.76</td>
          </tr>
        <td>1</td>
        <td>Water</td>
        <td>305.00</td>
        <td>₱ 305.00</td>
          </tr>
        </tbody>
    </table>
</div>
<div class="row">
              <div class="col-md-6"></div>
              <div class="col-md-6">
                <div class="row">
                  <div class="col-md-6">SUB-TOTAL:</div>
                  <div class="col-md-6">₱ 5797.76</div>
                </div>
                <div class="row">
                  <div class="col-md-6">DISCOUNT:</div>
                  <div class="col-md-6">₱ 0.00</div>
                </div>
                <div class="row">
                  <div class="col-md-6">TOTAL:</div>
                  <div class="col-md-6">₱ 5797.76</div>
                </div>
              </div>
            </div>
            <div class="col-md-3 float-right mt-5">
            <button type="button" class="btn btn-dark btn-icon-text">
                          Edit
                          <i class="bx bx-receipt btn-icon-append"></i>                          
                        </button>
                        <button type="button" class="btn btn-success btn-icon-text">
                          Print
                          <i class="bx bx-printer btn-icon-append"></i>                                                                              
                        </button>
  </div>
</div>
</div>
</div>
</div>

