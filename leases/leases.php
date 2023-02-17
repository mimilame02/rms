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
    $page_title = 'RMS | Leases';
    $p_units = 'active';

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
        <h3 class="font-weight-bolder">LEASES</h3> 
      </div>
      <div class="add-tenant-container">
      <?php
                    if($_SESSION['user_type'] == 'admin'){ 
                ?>
      <a href="add_lease.php" class="btn btn-success btn-icon-text float-right">
          Add Lease</a>
          <?php
                    }
                ?>
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
                     <th>Unit</th>
                     <th>Type</th>
                     <th>Rent</th>
                     <th>Tenant Name</th>
                     <?php
                                if($_SESSION['user_type'] == 'admin'){ 
                            ?>
                            <th>Action</th>
                            <?php
                                }
                            ?>
            </tr>
        </thead>
        <tbody>
        <tr>
        <td>1</td>
        <td>Pad 1</td>
        <td>Pad</td>
        <td>₱ 5000</td>
        <td>Monica Geller</td>
        <td>
                      <div class="action">
                        <a class="me-2 green" href="view_tenant.php?id='.$row['id'].'"><i class="fas fa-eye"></i></a>
                        <a class="me-2 green" href="edit_tenant.php?id='.$row['id'].'"><i class="fas fa-edit"></i></a>
                        <a class="green" href="delete_tenant.php?id='.$row['id'].'"><i class="fas fa-trash"></i></a>
                      </div>
                    </td>
          </tr>
       
        </tbody>
    </table>
    </div>
    </div>

<script>
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