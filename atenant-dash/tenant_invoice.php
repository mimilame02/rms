<?php

    //resume session here to fetch session values
    session_start();
    $user_id = $_SESSION['user_id'];
    $tenant_id = $_SESSION['tenant_id'];
    
    /*
        if user is not login then redirect to login page,
        this is to prevent users from accessing pages that requires
        authentication such as the dashboard
    */
    if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'tenant'){
        header('location: ../login/login.php');
    }
    //if the above code is false then html below will be displayed

    require_once '../tools/variables.php';
    $page_title = 'RMS | Leases';
    $leases = 'active';

    require_once '../includes/header.php';
    require_once '../includes/dbconfig.php';
?>
<body>
<div class="loading-screen">
  <img class="logo" src="../img/logo-edit.png" alt="logo">
  <?php echo $page_title; ?>
  <div class="loading-bar"></div>
</div>
<div class="container-scroller">
  <?php
    require_once 'tenant_navbar.php';
  ?>
<div class="container-fluid page-body-wrapper">
<?php
     require_once 'tenant_sidebar.php';
  ?>
<div class="main-panel">
  <div class="content-wrapper">
    <div class="row">
      <div class="col-12 col-xl-8 mb-4 mb-xl-0">
        <h3 class="font-weight-bolder">INVOICES</h3> 
      </div>
      <div class="add-tenant-container">
      </div>
      </div>
      <div class="row mt-4">
        <div class="card">
          <div class="card-body">
            <div class="table-responsive pt-3">
              <table id="example" class="table table-striped table-bordered dt-responsive nowrap" style="width:100%">
              <thead>
                  <tr>
                      <th>#</th>
                      <th>Unit No.</th>
                      <th>Total Due</th>
                      <th>Rent Due Date</th>
                      <th>Status</th>
                      <th>Action</th>
                  </tr>
              </thead>
              <tbody>
                
              </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
<script>

function redirectTo(url) {
  window.location.href = url;
}
    $('#example').DataTable( {
  responsive: {
    breakpoints: [
      {name: 'bigdesktop', width: Infinity},
      {name: 'meddesktop', width: 1480},
      {name: 'smalldesktop', width: 1280},
      {name: 'medium', width: 1188},
      {name: 'tabletl', width: 1024},
      {name: 'btwtabllandp', width: 848},
      {name: 'tabletp', width: 768},
      {name: 'mobilel', width: 480},
      {name: 'mobilep', width: 320}
    ]
  }
} );
</script>